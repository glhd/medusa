<?php

namespace Galahad\Medusa\Fields;

class Slug extends Text
{
	protected function configureRules()
	{
		$this->addRules('regex:/^[[a-z0-9_-]+$/i');
	}
}
