<?php

namespace Galahad\Medusa\Fields;

class Section extends Field
{
	public function defaultInitialValue()
	{
		return 0;
	}
	
	protected function defaultConfig() : array
	{
		return [
			'level' => 3,
		];
	}
}
