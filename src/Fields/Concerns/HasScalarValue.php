<?php

namespace Galahad\Medusa\Fields\Concerns;

use Illuminate\Validation\ValidationRuleParser;
use InvalidArgumentException;

trait HasScalarValue
{
	public function setRules($rules) : self
	{
		if (is_string($rules)) {
			$rules = (new ValidationRuleParser([$this->getName() => $this->getInitialValue()]))
				->explode([
					$this->getName() => $rules,
				])
				->rules;
		}
		
		if (!is_array($rules)) {
			throw new InvalidArgumentException(static::class.'::setRules() must be passed a string or array.');
		}
		
		$this->rules = $rules;
		
		return $this;
	}
}
