<?php

namespace Galahad\Medusa\Fields;

use Illuminate\Support\Arr;

class AdministrativeArea extends Field
{
	public function defaultInitialValue()
	{
		return [
			'code' => null,
			'name' => null,
		];
	}
	
	protected function configureRules()
	{
		$country_ref = '';
		if ($country_field = Arr::get($this->getConfig(), 'country_field')) {
			$country_ref = ":{$country_field}.code";
		}
		
		// TODO: Check if administrative_area_code rule exists first
		
		$this->addRules([
			'code' => "administrative_area_code{$country_ref}",
		]);
	}
}
