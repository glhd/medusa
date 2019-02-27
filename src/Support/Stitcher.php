<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\Field;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;

class Stitcher
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
					$name = $field->getName();
					$rules = $field->getRules();
					
					if (!Arr::isAssoc($rules)) {
						return ["data.{$name}" => $rules];
					}
					
					$keys = array_map(function($key) use ($name) {
						if ($name !== $key && 0 !== strpos($key, "{$name}.")) {
							$key = "{$name}.{$key}";
						}
						return "data.{$key}";
					}, array_keys($rules));
					
					return array_combine($keys, array_values($rules));
				})
				->collapse()
				->toArray();
		}
		
		return $this->rules;
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
