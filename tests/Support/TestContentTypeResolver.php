<?php

namespace Galahad\Medusa\Tests\Support;

use Galahad\Medusa\Collections\ContentTypeCollection;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeResolver;
use Galahad\Medusa\Exceptions\ContentTypeNotFoundException;
use Galahad\Medusa\Tests\Support\ContentTypes\Page;

class TestContentTypeResolver implements ContentTypeResolver
{
	protected $map = [];
	
	public function __construct()
	{
		$this->map['page'] = new Page();
	}
	
	public function resolve($id) : ContentType
	{
		if (isset($this->map[$id])) {
			return $this->map[$id];
		}
		
		throw new ContentTypeNotFoundException("Cannot find Content Type '$id'");
	}
	
	public function exists($id) : bool
	{
		return isset($this->map[$id]);
	}
	
	public function all() : ContentTypeCollection
	{
		return new ContentTypeCollection(array_values($this->map));
	}
}
