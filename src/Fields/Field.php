<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Contracts\Field as FieldContract;
use Illuminate\Support\Str;

abstract class Field implements FieldContract
{
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $display_name;
	
	/**
	 * @var string Label to show if different than display name
	 */
	protected $label;
	
	/**
	 * @var mixed
	 */
	protected $initial_value;
	
	/**
	 * @var array
	 */
	protected $config;
	
	/**
	 * @var array
	 */
	protected $rules;
	
	/**
	 * Define the default initial value for this field
	 *
	 * @return mixed
	 */
	abstract protected function defaultInitialValue();
	
	/**
	 * Field constructor.
	 *
	 * @param string $name
	 * @param array $config
	 */
	public function __construct($name)
	{
		$this->name = $name;
		$this->display_name = Str::title(Str::snake($name, ' '));
		$this->label = $this->display_name;
		$this->initial_value = $this->defaultInitialValue();
		$this->config = array_merge(['required' => false], $this->defaultConfig());
	}
	
	public static function make($name) : self
	{
		return new static($name);
	}
	
	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getDisplayName() : string
	{
		return $this->display_name;
	}
	
	public function setDisplayName($display_name) : self
	{
		$this->display_name = (string) $display_name;
		
		return $this;
	}
	
	/**
	 * Text to show in this field type's label (i.e. "Your name:" rather than "Name")
	 *
	 * @return string
	 */
	public function getLabel() : string
	{
		return $this->label;
	}
	
	public function setLabel($label) : self
	{
		$this->label = (string) $label;
		
		return $this;
	}
	
	public function getConfig() : array
	{
		return $this->config;
	}
	
	/**
	 * Get the initial value if one is not set
	 *
	 * @return mixed
	 */
	public function getInitialValue()
	{
		return $this->initial_value;
	}
	
	public function setInitialValue($initial_value) : self
	{
		// TODO: We may want to validate this and throw an InvalidArgumentException on error
		
		$this->initial_value = $initial_value;
		
		return $this;
	}
	
	public function getRules() : array
	{
		return $this->rules ?? $this->commonlyConfiguredRules();
	}
	
	public function getMessages() : array
	{
		return [];
	}
	
	public function setRequired(bool $required = true) : self
	{
		$this->config['required'] = $required;
		
		return $this;
	}
	
	/**
	 * Set up the default configuration for a field
	 *
	 * @return array
	 */
	protected function defaultConfig() : array
	{
		return [];
	}
	
	protected function commonlyConfiguredRules() : array
	{
		$rules = [];
		
		if ($this->config['required']) {
			$rules[] = 'required';
		}
		
		return $rules;
	}
}
