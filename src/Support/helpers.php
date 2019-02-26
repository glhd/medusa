<?php

use Galahad\Medusa\Medusa;

if (!function_exists('medusa')) {
	function medusa() : Medusa
	{
		return app(Medusa::class);
	}
}
