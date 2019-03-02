<?php

namespace Galahad\Medusa\Fields;

use Galahad\Medusa\Contracts\Field as FieldContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationRuleParser;
use InvalidArgumentException;

abstract class Field implements FieldContract
{
	use Concerns\HasTextValue; // FIXME: Just adding this for now
	
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
	protected $config = [];
	
	/**
	 * @var array
	 */
	protected $rules;
	
	/**
	 * @var bool
	 */
	protected $rules_configured = false;
	
	/**
	 * Rules pending parse/merge
	 *
	 * @var array
	 */
	protected $pending_rules = [];
	
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
	}
	
	/**
	 * @param $name
	 * @return \Galahad\Medusa\Fields\Field|$this
	 */
	public static function make($name) : self
	{
		return new static($name);
	}
	
	public function getComponent() : string
	{
		return class_basename(static::class);
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
		return array_merge($this->defaultConfig(), $this->config);
	}
	
	/**
	 * Get the initial value if one is not set
	 *
	 * @return mixed
	 */
	public function getInitialValue()
	{
		return $this->initial_value ?? $this->defaultInitialValue();
	}
	
	public function setInitialValue($initial_value) : self
	{
		// TODO: We may want to validate this and throw an InvalidArgumentException on error
		
		$this->initial_value = $initial_value;
		
		return $this;
	}
	
	/**
	 * @param string|array $rules
	 * @return \Galahad\Medusa\Fields\Field
	 */
	public function addRules($rules) : self
	{
		// TODO: Rule ordering matters… (esp. required/nullable/etc)
		$name = $this->getName();
		
		// We need to reset the compiled rules if we add more after we call getRules()
		$this->rules = null;
		
		if (is_string($rules)) {
			$parsed = (new ValidationRuleParser([$name => $this->getInitialValue()]))
				->explode([
					$name => $rules,
				]);
			$rules = $parsed->rules;
		}
		
		if (!is_array($rules)) {
			throw new InvalidArgumentException(static::class.'::addRules() must be passed a string or array.');
		}
		
		if (!Arr::isAssoc($rules)) {
			$rules = [$name => $rules];
		}
		
		$this->pending_rules[] = $rules;
		
		return $this;
	}
	
	public function getRules() : array
	{
		if (null === $this->rules) {
			$name = $this->getName();
			
			if (!$this->rules_configured) {
				$this->configureRules();
				$this->rules_configured = true;
			}
			
			$this->rules = [];
			
			foreach ($this->pending_rules as $pending_rules) {
				foreach ($pending_rules as $key => $value) {
					if ($name !== $key && 0 !== strpos($key, "{$name}.")) {
						$key = "{$name}.{$key}";
					}
					
					if (!isset($this->rules[$key])) {
						$this->rules[$key] = $value;
					} else {
						$this->rules[$key] = array_merge($this->rules[$key], $value);
					}
				}
			}
		}
		
		return $this->rules;
	}
	
	public function getMessages() : array
	{
		return []; // FIXME
	}
	
	public function setRequired() : self
	{
		$this->addRules(['required']);
		
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
	
	/**
	 * Override this to configure field rules
	 *
	 * @return mixed
	 */
	protected function configureRules()
	{
		//
	}
}
