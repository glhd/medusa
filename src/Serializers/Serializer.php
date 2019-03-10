<?php

namespace Galahad\Medusa\Serializers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

abstract class Serializer implements Arrayable
{
	/**
	 * @var \Galahad\Medusa\Contracts\Content|\Galahad\Medusa\Contracts\ContentType|\Galahad\Medusa\Contracts\Field
	 */
	protected $target;
	
	/**
	 * @var null
	 */
	protected $keys = [];
	
	/**
	 * @param array $keys
	 * @return $this
	 */
	public function setKeys(array $keys) : self
	{
		$this->keys = $keys;
		
		return $this;
	}
	
	public function toArray() : array
	{
		$result = [];
		
		foreach ($this->keys as $key => $value) {
			if (is_int($key)) {
				$key = $value;
			}
			
			$result[$key] = $this->serialize($key);
		}
		
		return $result;
	}
	
	protected function serialize($key)
	{
		$serialize_method = 'serialize'.Str::studly($key);
		if (method_exists($this, $serialize_method)) {
			return $this->$serialize_method();
		}
		
		$getter_method = 'get'.Str::studly($key);
		if (method_exists($this->target, $getter_method)) {
			return $this->target->$getter_method();
		}
		
		return null;
	}
}
