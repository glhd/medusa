<?php

namespace Galahad\Medusa\Models\Concerns;

use Galahad\Medusa\Contracts\Content;

trait DescribesContent
{
	public static function bootDescribesContent()
	{
		static::saving(function(Content $content) {
			$content->setDescription($content->getContentType()->generateDescriptionFromData($content->getData()));
		});
	}
	
	public function getDescription() : string
	{
		return (string) $this->getAttribute('description');
	}
	
	public function setDescription(string $description) : Content
	{
		$this->setAttribute('description', $description);
		
		return $this;
	}
}
