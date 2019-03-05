<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\Field;

class FieldSerializer extends Serializer
{
	/**
	 * @var \Galahad\Medusa\Contracts\Field
	 */
	protected $target;
	
	/**
	 * @var array
	 */
	protected $keys = ['name', 'component', 'display_name', 'label', 'config', 'initial_value', 'rules', 'messages'];
	
	public function __construct(Field $field)
	{
		$this->target = $field;
	}
	
	protected function serializeConfig() : string
	{
		return json_encode($this->target->getConfig());
	}
	
	protected function serializeInitialValue() : string
	{
		return json_encode($this->target->getInitialValue());
	}
	
	protected function serializeRules() : string
	{
		return json_encode($this->target->getRules());
	}
	
	protected function serializeMessages() : string
	{
		return json_encode($this->target->getMessages());
	}
}
