<?php

namespace Galahad\Medusa\Http\Middleware;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;

class Authorize
{
	/**
	 * @var \Illuminate\Contracts\Auth\Access\Gate
	 */
	protected $gate;
	
	public function __construct(Gate $gate)
	{
		$this->gate = $gate;
	}
	
	public function handle(Request $request, callable $next)
	{
		if (!app()->isLocal()) {
			$this->gate->authorize('_viewMedusa');
		}
		
		return $next($request);
	}
}
