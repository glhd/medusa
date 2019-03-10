<?php

namespace Galahad\Medusa\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseMiddleware;

class Authenticate extends BaseMiddleware
{
	public function handle($request, Closure $next, ...$guards)
	{
		try {
			$this->_authenticate($request, $guards);
		} catch (AuthenticationException $exception) {
			// Allow access for local development
			if (app()->isLocal()) {
				return $next($request);
			}
			
			throw $exception;
		}
		
		return $next($request);
	}
	
	protected function _authenticate($request, array $guards)
	{
		if (empty($guards)) {
			$guards = [config('medusa.guard')];
		}
		
		if (version_compare(app()->version(), '5.7.0', '>=')) {
			parent::authenticate($request, $guards);
		} else {
			parent::authenticate($guards);
		}
	}
}
