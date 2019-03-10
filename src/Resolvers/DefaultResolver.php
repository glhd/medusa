<?php

namespace Galahad\Medusa\Resolvers;

use ArrayAccess;

class DefaultResolver extends Resolver
{
	public static function findResolver($type_name) : string
	{
		// $type_name = preg_replace('/Response$/', '', $type_name);
		
		$resolver = 'Galahad\\Medusa\\Resolvers\\'.ucfirst($type_name).'Resolver';
		if (class_exists($resolver)) {
			return $resolver;
		}
		
		return self::class;
	}
	
	protected function resolve()
	{
		$source = $this->source;
		$field_name = $this->info->fieldName;
		$resolver = static::findResolver($field_name);
		
		if ($resolver !== self::class) {
			return (new $resolver($this->source, $this->args, $this->context, $this->info))();
		}
		
		if ((is_array($source) || $source instanceof ArrayAccess) && isset($source[$field_name])) {
			return $source[$field_name];
		}
		
		if (is_object($source) && isset($source->{$field_name})) {
			return $source->{$field_name};
		}
		
		return null;
	}
}
