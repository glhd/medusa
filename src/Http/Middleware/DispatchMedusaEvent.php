<?php

namespace Galahad\Medusa\Http\Middleware;

use Galahad\Medusa\Events\ServingMedusa;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

class DispatchMedusaEvent
{
	/**
	 * @var \Illuminate\Contracts\Events\Dispatcher
	 */
	protected $dispatcher;
	
	public function __construct(Dispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}
	
	public function handle(Request $request, callable $next)
	{
		$this->dispatcher->dispatch(new ServingMedusa($request));
		
		return $next($request);
	}
}
