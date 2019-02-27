<?php

namespace Galahad\Medusa\Contracts;

interface Field
{
	/**
	 * Get the name of the component that renders this field
	 *
	 * @return string
	 */
	public function getComponent() : string;
	
	/**
	 * The name that will be used to store this field
	 *
	 * @return string
	 */
	public function getName() : string;
	
	/**
	 * The name that will be used when referencing this field in the UI
	 *
	 * eg. You may want to use "Full Name" for the display name and "Enter the full name:" as the label
	 *
	 * @return string
	 */
	public function getDisplayName() : string;
	
	/**
	 * Text to show as this field's label (i.e. "Your name:" rather than "Name")
	 *
	 * @return string
	 */
	public function getLabel() : string;
	
	/**
	 * Get the field's configuration
	 *
	 * @return array
	 */
	public function getConfig() : array;
	
	/**
	 * Get the initial value if one is not set
	 *
	 * @return mixed
	 */
	public function getInitialValue();
	
	/**
	 * Validation rules
	 *
	 * @return array
	 */
	public function getRules() : array;
	
	/**
	 * @return array
	 */
	public function getMessages() : array;
}
