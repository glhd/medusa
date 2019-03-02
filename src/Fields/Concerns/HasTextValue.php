<?php

namespace Galahad\Medusa\Fields\Concerns;

trait HasTextValue
{
	public function getGraphQLType() : string
	{
		return 'String!';
	}
	
	protected function defaultInitialValue()
	{
		return '';
	}
}
