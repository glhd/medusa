<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Collections\FieldCollection;
use Galahad\Medusa\Contracts\Field as FieldContract;
use Galahad\Medusa\Serializers\FieldSerializer;
use RuntimeException;

class Blocks extends Field
{
	/**
	 * @var FieldCollection
	 */
	protected $fields;
	
	public function setFields($fields) : self
	{
		$this->fields = new FieldCollection($fields);
		
		return $this;
	}
	
	public function getConfig() : array
	{
		if (null === $this->fields) {
			throw new RuntimeException('You must configure the fields in a Blocks field.');
		}
		
		$config = parent::getConfig();
		$config['fields'] = $this->fields->toBase()
			->mapWithKeys(function(FieldContract $field) {
				return [$field->getName() => new FieldSerializer($field)];
			})
			->toArray();
		
		return $config;
	}
	
	protected function configureRules()
	{
		$this->addRules('array'); // FIXME
	}
	
	protected function defaultInitialValue()
	{
		return [];
	}
}
