<?php

namespace Galahad\Medusa\Fields\Concerns;

trait HasIntValue
{
	public function getGraphQLType() : string
	{
		return 'Int!';
	}
	
	protected function defaultInitialValue()
	{
		return 0;
	}
}
