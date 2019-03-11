<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Fields\Concerns\HasIntValue;

class Relationship extends Field
{
	use HasIntValue;
	
	protected function configureRules()
	{
		$table = config('medusa.content_table');
		
		$this->addRules("integer|exists:$table,id");
	}
	
	/**
	 * @param ContentType|string $content_type
	 * @return $this
	 */
	public function setContentType($content_type) : self
	{
		$this->config['content_type_id'] = $content_type instanceof ContentType
			? $content_type->getId()
			: (string) $content_type;
		
		return $this;
	}
}
