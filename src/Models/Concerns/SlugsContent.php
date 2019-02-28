<?php

namespace Galahad\Medusa\Models\Concerns;

use Galahad\Medusa\Contracts\Content;
use Illuminate\Support\Str;

trait SlugsContent
{
	public static function bootSlugsContent()
	{
		static::saving(function(Content $content) {
			if (empty($content->getSlug())) {
				// FIXME: Ensure unique
				$slug_source = $content->getContentType()->generateSlugFromData($content->getData());
				$slug = Str::slug($slug_source);
				$content->setSlug($slug);
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
