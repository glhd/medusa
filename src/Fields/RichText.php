<?php

namespace Galahad\Medusa\Fields;

class RichText extends MultilineText
{
	public function setKey($key) : self
	{
		$this->config['key'] = (string) $key;
		
		return $this;
	}
}
