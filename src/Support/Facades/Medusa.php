<?php

namespace Galahad\Medusa\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Medusa extends Facade
{
	protected static function getFacadeAccessor() : string
	{
		return 'galahad.medusa';
	}
}
