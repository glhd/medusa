<?php

if (!function_exists('medusa')) {
	function medusa() : Galahad\Medusa\Medusa
	{
		return app('galahad.medusa');
	}
}
