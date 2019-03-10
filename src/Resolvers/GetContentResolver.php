<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Serializers\ContentSerializer;

class GetContentResolver extends Resolver
{
	/**
	 * @var int[]
	 */
	protected static $ids = [];
	
	/**
	 * @var \Illuminate\Support\Collection
	 */
	protected static $loaded;
	
	protected $deferred = true;
	
	public static function queue($id) : void
	{
		static::$ids[] = $id;
	}
	
	public static function load($id) : ?Content
	{
		if (null === static::$loaded) {
			static::$loaded = app(ContentRepository::class)
				->resolveMany(static::$ids)
				->keyBy(function(Content $content) {
					return $content->getId();
				});
		}
		
		return static::$loaded->get($id);
	}
	
	protected function prepareDeferred() : void
	{
		static::queue($this->arg('id'));
	}
	
	protected function resolve()
	{
		$content = static::load($this->arg('id'));
		
		$this->authorize('view', $content);
		
		$selection = $this->info->getFieldSelection(2);
		
		return (new ContentSerializer($content))
			->setKeys($selection)
			->toArray();
	}
}
