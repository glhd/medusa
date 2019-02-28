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
			'id' => 'medusa-'.$field->getName().'-'.(static::$next_id++),
			'component' => $field->getComponent(),
			'name' => $field->getName(),
			'display_name' => $field->getDisplayName(),
			'label' => $field->getLabel(),
			'config' => $field->getConfig(),
			'initial_value' => $field->getInitialValue(),
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
