<?php

namespace Galahad\Medusa\Fields;

class YesNo extends Field
{
	public function defaultInitialValue()
	{
		return false;
	}
	
	protected function defaultConfig() : array
	{
		return [
			'yes_label' => 'Yes',
			'no_label' => 'No',
		];
	}
	
	protected function configureRules()
	{
		$this->addRules(['boolean']);
	}
}
