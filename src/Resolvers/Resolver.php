<?php

namespace Galahad\Medusa\Resolvers;

use GraphQL\Deferred;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

abstract class Resolver
{
	/**
	 * Automatically defer the resolution
	 *
	 * @var bool
	 */
	protected $deferred = false;
	
	/**
	 * @var mixed
	 */
	protected $source;
	
	/**
	 * @var array
	 */
	protected $args;
	
	/**
	 * @var mixed
	 */
	protected $context;
	
	/**
	 * @var \GraphQL\Type\Definition\ResolveInfo
	 */
	protected $info;
	
	abstract protected function resolve();
	
	public function __construct($source, $args, $context, ResolveInfo $info)
	{
		$this->source = $source;
		$this->args = $args;
		$this->context = $context;
		$this->info = $info;
	}
	
	public function __invoke()
	{
		if ($this->deferred) {
			$this->prepareDeferred();
			
			return new Deferred(function() {
				return $this->resolve();
			});
		}
		
		return $this->resolve();
	}
	
	/**
	 * @param $ability
	 * @param array $arguments
	 * @throws \GraphQL\Error\UserError
	 */
	protected function authorize($ability, $arguments = []) : void
	{
		if (!app(Gate::class)->allows($ability, $arguments)) {
			throw new UserError('Not authorized.', Response::HTTP_UNAUTHORIZED);
		}
	}
	
	protected function arg($key, $default = null)
	{
		return Arr::get($this->args, $key, $default);
	}
	
	protected function getFieldSelection($depth = 0) : array
	{
		return $this->info->getFieldSelection($depth);
	}
	
	protected function prepareDeferred() : void
	{
		// Override where necessary
	}
}
