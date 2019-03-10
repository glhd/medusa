<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Fields\Concerns\HasIntValue;

class Relationship extends Field
{
	use HasIntValue;
	
	protected function configureRules()
	{
		$table = config('medusa.content_table');
		
		$this->addRules("integer|exists:$table,id");
	}
}
