<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\Field;
use Illuminate\Contracts\Support\Jsonable;

class Stitcher implements Jsonable
{
	/**
	 * @var \Galahad\Medusa\Contracts\ContentType
	 */
	protected $content_type;
	
	protected $rules;
	
	public function __construct(ContentType $content_type)
	{
		$this->content_type = $content_type;
	}
	
	public function getRules() : array
	{
		// TODO: content_type exists rule
		
		if (null === $this->rules) {
			$this->rules = $this->content_type->getFields()
				->toBase()
				->map(function(Field $field) {
					return $field->getRules();
				})
				->collapse()
				->toArray();
		}
		
		return $this->rules;
	}
	
	public function getFields() : array
	{
		return $this->content_type->getFields()
			->toBase()
			->mapWithKeys(function(Field $field) {
				return [$field->getName() => [
					'component' => $field->getComponent(),
					'name' => $field->getName(),
					'display_name' => $field->getDisplayName(),
					'label' => $field->getLabel(),
					'config' => $field->getConfig(),
					'initial_value' => $field->getInitialValue(),
				]];
			})
			->toArray();
	}
	
	public function toJson($options = 0)
	{
		return json_encode([
			'content_type' => [
				'id' => $this->content_type->getId(),
				'title' => $this->content_type->getTitle(),
			],
			'fields' => $this->getFields(),
			'rules' => $this->getRules(),
			// TODO: messages
		], $options);
	}
	
	/**
	 * Pass method calls down into the content type
	 *
	 * @param $name
	 * @param $arguments
	 * @return $this
	 */
	public function __call($name, $arguments)
	{
		$result = $this->content_type->$name(...$arguments);
		
		if ($result instanceof ContentType) {
			return $this;
		}
		
		return $result;
	}
}
