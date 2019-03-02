<?php

namespace Galahad\Medusa;

use Galahad\Medusa\Concerns\ResolvesContentTypes;
use Galahad\Medusa\Events\ServingMedusa;
use Illuminate\Contracts\Events\Dispatcher;

class Medusa
{
	use ResolvesContentTypes;
	
	/**
	 * @var array
	 */
	protected $config;
	
	/**
	 * @var \Illuminate\Contracts\Events\Dispatcher
	 */
	protected $dispatcher;
	
	public function __construct(Dispatcher $dispatcher, array $config)
	{
		$this->dispatcher = $dispatcher;
		$this->config = $config;
	}
	
	/**
	 * Run callback before serving the Medusa interface
	 *
	 * @param string|callable|array $callback
	 * @return $this
	 */
	public function serving($callback) : self
	{
		$this->dispatcher->listen(ServingMedusa::class, $callback);
		
		return $this;
	}
	
	public function basePath(string $path = null) : string
	{
		$base_path = rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR);
		
		return null === $path
			? $base_path
			: $base_path.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
	}
}
