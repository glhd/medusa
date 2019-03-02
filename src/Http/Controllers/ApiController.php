<?php

namespace Galahad\Medusa\Http\Controllers;

// use Galahad\Medusa\Http\Middleware\Authorize;
use Closure;
use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentResolver;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Serializers\ContentSerializer;
use Galahad\Medusa\Serializers\ContentTypeSerializer;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Utils\BuildSchema;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;

class ApiController extends Controller
{
	public function __construct()
	{
		$this->middleware([
			DispatchMedusaEvent::class,
			// Authorize::class, // FIXME
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
		
		$result = method_exists($this, $method)
			? $this->$method($source, $args, $context, $info)
			: $this->defaultFieldResolver($field_name, $source);
		
		return $result instanceof Closure
			? $result($source, $args, $context, $info)
			: $result;
	}
	
	protected function resolveAllContent($source, $args, $context, ResolveInfo $info)
	{
		$page = $args['page'] ?? 1;
		
		$paginator = app(ContentResolver::class)->paginate(20, $page);
		
		return [
			'total' => $paginator->total(),
			'per_page' => $paginator->perPage(),
			'current_page' => $paginator->currentPage(),
			'last_page' => $paginator->lastPage(),
			'content' => collect($paginator->items())
				->map(function(Content $content) {
					return (new ContentSerializer($content))->toArray();
				})
				->toArray(),
		];
	}
	
	protected function resolveGetContent($source, $args, $context, ResolveInfo $info)
	{
		$content = app(ContentResolver::class)->resolve($args['id']);
		
		return (new ContentSerializer($content))->toArray();
	}
	
	protected function resolveAllsContentTypes($source, $args, $context, ResolveInfo $info)
	{
		return medusa()->allContentTypes()
			->toBase()
			->map(function(ContentType $content_type) {
				return new ContentTypeSerializer($content_type);
			})
			->values()
			->toArray();
	}
	
	protected function defaultFieldResolver($field_name, $source)
	{
		$result = null;
		
		if (is_array($source) || $source instanceof \ArrayAccess) {
			if (isset($source[$field_name])) {
				$result = $source[$field_name];
			}
		} else if (is_object($source)) {
			if (isset($source->{$field_name})) {
				$result = $source->{$field_name};
			}
		}
		
		return $result;
	}
}
