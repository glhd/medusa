<?php

namespace Galahad\Medusa\Concerns;

use Galahad\Medusa\Collections\ContentTypeCollection;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeRepository;

trait ResolvesContentTypes
{
	/**
	 * @var \Galahad\Medusa\Contracts\ContentTypeRepository
	 */
	protected $content_type_resolver;
	
	public function setContentTypeResolver(ContentTypeRepository $resolver) : self
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
