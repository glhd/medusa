<?php

namespace Galahad\Medusa;

use Galahad\Medusa\Events\ServingMedusa;
use Illuminate\Contracts\Events\Dispatcher;

class Medusa
{
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
}
