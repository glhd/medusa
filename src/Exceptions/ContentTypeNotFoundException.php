<?php

namespace Galahad\Medusa\Exceptions;

class ContentTypeNotFoundException extends \Exception
{
	protected $requested_content_type;
	
	public function setRequestedContentType($requested_content_type) : self
	{
		$this->requested_content_type = $requested_content_type;
		
		return $this;
	}
	
	public function getRequestedContentType()
	{
		return $this->requested_content_type;
	}
}
