<?php

namespace Galahad\Medusa\Fields;

class Cloudinary extends Field
{
	protected function defaultInitialValue()
	{
		return ($this->getConfig()['allow_multiple'] ?? false)
			? []
			: null;
	}
}
