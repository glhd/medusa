<?php

namespace Galahad\Medusa\Fields;

class MetaDescription extends MultilineText
{
	protected function configureRules()
	{
		$this->addRules('max:160');
	}
	
	protected function defaultConfig() : array
	{
		return [
			'height' => 200,
		];
	}
}
