<?php

namespace Galahad\Medusa\Concerns;

use Galahad\Medusa\Collections\ContentTypeCollection;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeResolver;

trait ResolvesContentTypes
{
	/**
	 * @var \Galahad\Medusa\Contracts\ContentTypeResolver
	 */
	protected $content_type_resolver;
	
	public function setContentTypeResolver(ContentTypeResolver $resolver) : self
	{
		$this->content_type_resolver = $resolver;
		
		return $this;
	}
	
	public function resolveContentType($id) : ?ContentType
	{
		return $this->content_type_resolver->resolve($id);
	}
	
	public function allContentTypes() : ContentTypeCollection
	{
		return $this->content_type_resolver->all();
	}
}
