<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class ContentTypeSerializer implements Arrayable, Jsonable, JsonSerializable
{
	/**
	 * @var \Galahad\Medusa\Contracts\ContentType
	 */
	protected $content_type;
	
	public function __construct(ContentType $content_type)
	{
		$this->content_type = $content_type;
	}
	
	public function toArray()
	{
		return [
			'content_type' => [
				'id' => $this->content_type->getId(),
				'title' => $this->content_type->getTitle(),
			],
			'fields' => $this->fieldsToArray(),
			'rules' => $this->fieldsToRules(),
			'messages' => $this->fieldsToMessages(),
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
	
	protected function fieldsToArray() : array
	{
		return $this->content_type->getFields()
			->toBase()
			->mapWithKeys(function(Field $field) {
				return [$field->getName() => new FieldSerializer($field)];
			})
			->toArray();
	}
	
	protected function fieldsToRules() : array
	{
		return $this->content_type->getFields()
			->toBase()
			->map(function(Field $field) {
				return $field->getRules();
			})
			->collapse()
			->toArray();
	}
	
	protected function fieldsToMessages() : array
	{
		return []; // FIXME
	}
}
