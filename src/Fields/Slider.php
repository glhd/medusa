<?php

namespace Galahad\Medusa\Fields;

use Illuminate\Support\Arr;

class Slider extends Field
{
	public function defaultInitialValue()
	{
		return 0;
	}
	
	protected function configureRules()
	{
		$config = $this->getConfig();
		$rules = ['integer'];
		
		$min = Arr::get($config, 'min');
		if (null !== $min) {
			$rules[] = "min:{$min}";
		}
		
		$max = Arr::get($config, 'max');
		if (null !== $max) {
			$rules[] = "max:{$max}";
		}
		
		$steps = Arr::get($config, 'steps');
		if (null !== $steps) {
			$rules[] = 'in:'.implode(',', array_keys($steps));
		}
		
		$this->addRules($rules);
	}
}
