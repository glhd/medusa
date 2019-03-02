<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\Content;

class ContentSerializer extends Serializer
{
	/**
	 * @var \Galahad\Medusa\Contracts\Content
	 */
	protected $target;
	
	/**
	 * @var array
	 */
	protected $keys = ['id', 'content_type', 'description', 'slug', 'data'];
	
	public function __construct(Content $content)
	{
		$this->target = $content;
	}
	
	protected function serializeId() : string
	{
		return (string) $this->target->getId();
	}
	
	protected function serializeData() : string
	{
		return json_encode($this->target->getData());
	}
	
	protected function serializeContentType() : array
	{
		return (new ContentTypeSerializer($this->target->getContentType()))
			->setKeys($this->keys['content_type'])
			->toArray();
	}
}
