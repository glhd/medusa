<?php

namespace Galahad\Medusa\Http\Controllers;

use ArrayAccess;
use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentResolver;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeResolver;
use Galahad\Medusa\Exceptions\ValidationException;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Serializers\ContentSerializer;
use Galahad\Medusa\Serializers\ContentTypeSerializer;
use Galahad\Medusa\Validation\ContentValidator;
use GraphQL\Error\UserError;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Utils\BuildSchema;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;

class ApiController extends Controller
{
	public function __construct()
	{
		$this->middleware([
			DispatchMedusaEvent::class,
			Authorize::class,
		]);
	}
	
	public function __invoke(ServerRequestInterface $request)
	{
		// TODO: Cache this: https://webonyx.github.io/graphql-php/type-system/type-language/
		
		$contents = file_get_contents(medusa()->basePath('resources/schema.graphql'));
		$schema = BuildSchema::build($contents);
		
		$server = new StandardServer([
			'schema' => $schema,
			'fieldResolver' => [$this, 'resolve'],
			'queryBatching' => false, // TODO
			'debug' => app()->isLocal(),
		]);
		
		$result = $server->executePsrRequest($request);
		
		$status = count($result->errors)
			? Response::HTTP_BAD_REQUEST
			: Response::HTTP_OK;
		
		return response()->json($result, $status);
	}
	
	public function resolve($source, $args, $context, ResolveInfo $info)
	{
		$field_name = $info->fieldName;
		$method = 'resolve'.Str::studly($field_name);
		
		return method_exists($this, $method)
			? $this->$method($source, $args, $context, $info)
			: $this->defaultFieldResolver($field_name, $source);
	}
	
	protected function resolveAllContent($source, $args, $context, ResolveInfo $info)
	{
		if (!Gate::allows('view', config('medusa.content_model', Content::class))) {
			throw new UserError('Unauthorized', 401);
		}
		
		$page = $args['page'] ?? 1;
		$paginator = app(ContentResolver::class)->paginate(20, $page);
		$selection = $info->getFieldSelection(2);
		
		return [
			'total' => $paginator->total(),
			'per_page' => $paginator->perPage(),
			'current_page' => $paginator->currentPage(),
			'last_page' => $paginator->lastPage(),
			'content' => collect($paginator->items())
				->map(function(Content $content) use ($selection) {
					return (new ContentSerializer($content))->setKeys($selection['content'])->toArray();
				})
				->toArray(),
		];
	}
	
	protected function resolveGetContent($source, $args, $context, ResolveInfo $info)
	{
		$selection = $info->getFieldSelection(2);
		$content = app(ContentResolver::class)->resolve($args['id']);
		
		if (!Gate::allows('view', $content)) {
			throw new UserError('Unauthorized', 401);
		}
		
		return (new ContentSerializer($content))->setKeys($selection)->toArray();
	}
	
	protected function resolveAllContentTypes($source, $args, $context, ResolveInfo $info)
	{
		$selection = $info->getFieldSelection(1);
		
		return medusa()->allContentTypes()
			->toBase()
			->map(function(ContentType $content_type) use ($selection) {
				return (new ContentTypeSerializer($content_type))->setKeys($selection);
			})
			->values()
			->toArray();
	}
	
	protected function resolveGetContentType($source, $args, $context, ResolveInfo $info)
	{
		$selection = $info->getFieldSelection(1);
		$content_type = app(ContentTypeResolver::class)->resolve($args['id']);
		return (new ContentTypeSerializer($content_type))->setKeys($selection)->toArray();
	}
	
	protected function resolveCreateContent($source, $args, $context, ResolveInfo $info)
	{
		$content_type = medusa()->resolveContentType($args['content_type_id']);
		$data = json_decode($args['data'], true);
		
		if (!Gate::allows('create', [Content::class, $content_type])) {
			throw new UserError('Unauthorized', 401);
		}
		
		$validator = new ContentValidator(app(Translator::class), $data, $content_type);
		
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}
		
		$content = $this->newContentModel();
		$content->setContentType($content_type);
		$content->setData($data);
		$content->save();
		
		return (new ContentSerializer($content))
			->setKeys($info->getFieldSelection(2))
			->toArray();
	}
	
	protected function resolveUpdateContent($source, $args, $context, ResolveInfo $info)
	{
		$content = app(ContentResolver::class)->resolve($args['id']);
		$data = json_decode($args['data'], true);
		
		if (!Gate::allows('update', $content)) {
			throw new UserError('Unauthorized', 401);
		}
		
		$validator = new ContentValidator(app(Translator::class), $data, $content->getContentType());
		
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}
		
		$content->setData($data);
		$content->save();
		
		return (new ContentSerializer($content))
			->setKeys($info->getFieldSelection(2))
			->toArray();
	}
	
	protected function defaultFieldResolver($field_name, $source)
	{
		if ((is_array($source) || $source instanceof ArrayAccess) && isset($source[$field_name])) {
			return $source[$field_name];
		}
		
		if (is_object($source) && isset($source->{$field_name})) {
			return $source->{$field_name};
		}
		
		return null;
	}
	
	protected function newContentModel() : Content
	{
		$content_class = config('medusa.content_model');
		return new $content_class();
	}
}
