<?php

namespace Galahad\Medusa\Fields;

class MultilineText extends Text
{
	protected function defaultConfig() : array
	{
		return [
			'rows' => 10,
			'placeholder' => '',
		];
	}
}
