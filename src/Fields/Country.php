<?php

namespace Galahad\Medusa\Fields;

class Country extends Text
{
	public function defaultInitialValue()
	{
		return [
			'code' => 'US',
			'name' => 'United States',
		];
	}
	
	protected function configureRules()
	{
		// TODO: Check if country_code rule exits
		
		$this->addRules([
			'code' => 'max:2|country_code',
		]);
	}
}
