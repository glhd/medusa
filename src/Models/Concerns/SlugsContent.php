<?php

namespace Galahad\Medusa\Models\Concerns;

use Galahad\Medusa\Contracts\Content;
use Illuminate\Support\Str;

trait SlugsContent
{
	public static function bootSlugsContent()
	{
		static::saving(function(Content $content) {
			$existing = $content->getSlug();
			
			$slug = Str::slug(
				$content->getContentType()
					->generateSlugFromData($content->getData(), $existing)
			);
			
			if (empty($existing) || $slug !== $existing) {
				$content->setSlug($content->makeSlugUnique($slug));
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
	
	protected function makeSlugUnique(string $slug) : string
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
