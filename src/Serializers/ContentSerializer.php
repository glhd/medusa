<?php

namespace Galahad\Medusa\Serializers;

use Galahad\Medusa\Contracts\Content;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class ContentSerializer implements Arrayable, Jsonable, JsonSerializable
{
	/**
	 * @var \Galahad\Medusa\Contracts\Content
	 */
	protected $content;
	
	public function __construct(Content $content)
	{
		$this->content = $content;
	}
	
	public function toArray()
	{
		// TODO: Lazy load some values depending on request
		
		return [
			'id' => (string) $this->content->getId(),
			'content_type' => (new ContentTypeSerializer($this->content->getContentType()))->toArray(),
			'description' => $this->content->getDescription(),
			'slug' => $this->content->getSlug(),
			'data' => json_encode($this->content->getData()),
		];
	}
	
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}
	
	public function jsonSerialize()
	{
		return $this->toArray();
	}
}
