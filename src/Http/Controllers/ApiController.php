<?php

namespace Galahad\Medusa\Http\Controllers;

use Galahad\Medusa\Http\Middleware\Authenticate;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Schema;
use GraphQL\Server\StandardServer;
use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;

class ApiController extends Controller
{
	/**
	 * @var \Galahad\Medusa\Schema
	 */
	protected $loader;
	
	public function __construct(Schema $loader)
	{
		$this->loader = $loader;
		
		$this->middleware(array_merge(
			[DispatchMedusaEvent::class],
			config('medusa.middleware', []),
			[Authenticate::class, Authorize::class]
		));
	}
	
	public function __invoke(ServerRequestInterface $request)
	{
		$server = new StandardServer([
			'schema' => $this->loader->schema(),
			// 'fieldResolver' => [$this, 'resolve'],
			'queryBatching' => true,
			'debug' => app()->isLocal(),
		]);
		
		// Calling toArray() on the collection will force all Arrayable
		// items in the result to also have toArray() called on them
		$result = collect($server->executePsrRequest($request))->toArray();
		
		return response()->json($result);
	}
	
	/*
	public function resolve($source, $args, $context, ResolveInfo $info)
	{
		$field_name = $info->fieldName;
		$method = 'resolve'.Str::studly($field_name);
		
		$response = method_exists($this, $method)
			? $this->$method($source, $args, $context, $info)
			: $this->defaultFieldResolver($field_name, $source);
		
		return $this->prepareResponseForClient($response);
	}
	*/
}
