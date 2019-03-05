<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Fields\Concerns\HasIntValue;

class Section extends Field
{
	use HasIntValue;
	
	protected function defaultConfig() : array
	{
		return [
			'level' => 3,
		];
	}
}
