<?php

namespace Galahad\Medusa\Contracts;

use Galahad\Medusa\Collections\FieldCollection;

interface ContentType
{
	/**
	 * Get the unique identifier for this content type
	 *
	 * @return mixed
	 */
	public function getId();
	
	/**
	 * Get the title of the content type for display
	 *
	 * @return string
	 */
	public function getTitle() : string;
	
	/**
	 * Get the fields in the content type
	 *
	 * @return \Galahad\Medusa\Collections\FieldCollection
	 */
	public function getFields() : FieldCollection;
	
	/**
	 * Determine if only one instance of this type should exist in storage
	 *
	 * @return bool
	 */
	public function isSingleton() : bool;
}
