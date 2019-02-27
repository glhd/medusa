<?php

namespace Galahad\Medusa\Contracts;

use Galahad\Medusa\Collections\FieldCollection;
use Illuminate\Contracts\Routing\UrlRoutable;

interface ContentType extends UrlRoutable
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
	
	/**
	 * Generate a unique slug based on the content
	 *
	 * @param array $data
	 * @return string
	 */
	public function generateSlugFromData(array $data) : string;
	
	/**
	 * Generate a description based on the content
	 *
	 * @param array $data
	 * @return string
	 */
	public function generateDescriptionFromData(array $data) : string;
}
