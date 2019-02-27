<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Fields\Concerns\HasScalarValue;

class Text extends Field
{
	use HasScalarValue;
	
	protected function defaultInitialValue()
	{
		return '';
	}
}
