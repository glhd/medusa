<?php

namespace Galahad\Medusa\Http\Middleware;

use Illuminate\Http\Request;

class ServeInterface
{
	public function handle(Request $request, callable $next)
	{
		if (!$request->wantsJson()) {
			// TODO: Serve interface instead
		}
		
		return $next($request);
	}
}
