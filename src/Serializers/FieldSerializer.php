<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class FieldSerializer implements Arrayable, Jsonable, JsonSerializable
{
	protected static $next_id = 1;
	
	/**
	 * @var \Galahad\Medusa\Contracts\Field
	 */
	protected $field;
	
	public function __construct(Field $field)
	{
		$this->field = $field;
	}
	
	public function toArray()
	{
		$field = $this->field;
		
		return [
			'name' => $field->getName(),
			'component' => $field->getComponent(),
			'display_name' => $field->getDisplayName(),
			'label' => $field->getLabel(),
			'config' => json_encode($field->getConfig()),
			'initial_value' => json_encode($field->getInitialValue()),
			'rules' => json_encode($field->getRules()),
			'messages' => json_encode($field->getMessages()),
		];
	}
	
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}
	
	public function jsonSerialize()
	{
		return $this->toArray();
	}
}
