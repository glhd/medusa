<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Fields\Concerns\HasIntValue;

class Number extends Text
{
	use HasIntValue;
	
	protected function configureRules()
	{
		$this->addRules('numeric');
	}
}
