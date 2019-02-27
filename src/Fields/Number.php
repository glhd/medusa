<?php

namespace Galahad\Medusa\Fields;

class Number extends Text
{
	public function defaultInitialValue()
	{
		return 0;
	}
	
	protected function configureRules()
	{
		$this->addRules('numeric');
	}
}
