<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\Field;
use Illuminate\Contracts\Support\Arrayable;

class ContentTypeSerializer extends Serializer
{
	/**
	 * @var \Galahad\Medusa\Contracts\ContentType
	 */
	protected $target;
	
	/**
	 * @var array
	 */
	protected $keys = ['id', 'title', 'is_singleton', 'fields', 'rules', 'messages'];
	
	public function __construct(ContentType $content_type)
	{
		$this->target = $content_type;
	}
	
	protected function serializeIsSingleton() : bool
	{
		return $this->target->isSingleton();
	}
	
	protected function serializeFields() : array
	{
		return $this->target->getFields()
			->toBase()
			->map(function(Field $field) {
				return (new FieldSerializer($field))->setKeys($this->keys['fields']);
			})
			->values()
			->toArray();
	}
	
	protected function serializeRules() : string
	{
		$rules = $this->target->getFields()
			->toBase()
			->map(function(Field $field) {
				return $field->getRules();
			})
			->collapse()
			->toArray();
		
		return json_encode($rules);
	}
	
	protected function serializeMessages() : string
	{
		return json_encode(new \stdClass()); // FIXME
	}
}
