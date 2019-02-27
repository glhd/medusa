<?php

namespace Galahad\Medusa\Models\Concerns;

use Galahad\Medusa\Contracts\Content;

trait SlugsContent
{
	public static function bootSlugsContent()
	{
		static::saving(function(Content $content) {
			if (empty($content->getSlug())) {
				$content->setSlug($content->getContentType()->generateSlugFromData($content->getData()));
			}
		});
	}
	
	public function getSlug() : string
	{
		return (string) $this->getAttribute('slug');
	}
	
	public function setSlug(string $slug) : Content
	{
		$this->setAttribute('slug', $slug);
		
		return $this;
	}
}
