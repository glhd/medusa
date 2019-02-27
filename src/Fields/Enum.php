<?php

namespace Galahad\Medusa\Fields;

use Illuminate\Support\Arr;

class Enum extends Field
{
	protected $options;
	
	public function __construct($name, array $options, $multiple = false)
	{
		$this->config = ['allow_multiple' => $multiple];
		$this->options = $this->normalizeOptions($options);
		
		parent::__construct($name);
	}
	
	/**
	 * @return mixed
	 */
	public function defaultInitialValue()
	{
		return ($this->getConfig()['allow_multiple'] ?? false)
			? []
			: '';
	}
	
	public function getMessages() : array
	{
		return [
			'size' => 'You may only choose one option',
			'in' => 'That is not a valid selection',
		];
	}
	
	protected function configureRules()
	{
		$multiple = ($this->getConfig()['allow_multiple'] ?? false);
		$rules = [];
		
		if ($multiple) {
			$rules[] = 'array';
		}
		
		$rules[] = 'in:'.implode(',', array_keys($this->options));
		
		$this->addRules($rules);
	}
	
	protected function normalizeOptions(array $options) : array
	{
		return Arr::isAssoc($options)
			? $options
			: array_combine($options, $options);
	}
}
