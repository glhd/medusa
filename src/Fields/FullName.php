<?php

namespace Galahad\Medusa\Fields;

class FullName extends Field
{
	public function defaultInitialValue()
	{
		return [
			'given' => '',
			'family' => '',
		];
	}
	
	public function setRequired() : Field
	{
		// TODO: There's probably a better way to apply all rules to each sub-item
		
		$this->addRules([
			'given' => ['required'],
			'family' => ['required'],
		]);
	}
}
