<?php

namespace Galahad\Medusa\Fields;

class Slug extends Text
{
	public function setUrlPrefix($prefix) : self
	{
		$this->config['url_prefix'] = (string) $prefix;
		
		return $this;
	}
	
	protected function configureRules()
	{
		$this->addRules('regex:/^[[a-z0-9-]+$/i');
	}
}
