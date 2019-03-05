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
				// FIXME: We need a way to indicate that the slug should always be updated
				// TODO: Maybe fire a SluggedContent event so that a redirect can be set up if necessary
				$slug_source = $content->getContentType()->generateSlugFromData($content->getData());
				$slug = $content->addSlugSuffix(Str::slug($slug_source));
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
	
	protected function addSlugSuffix(string $slug) : string
	{
		$suffix = 0;
		$new_slug = $slug;
		
		do {
			$exists = static::where('slug', '=', $new_slug)
				->where($this->getKeyName(), '<>', $this->getKey() ?? 0)
				->withoutGlobalScopes()
				->exists();
			
			if ($exists) {
				$suffix++;
				$new_slug = "{$slug}-{$suffix}";
			}
		} while ($exists);
		
		return $new_slug;
	}
}
