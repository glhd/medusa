<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\ContentTypeRepository;
use Galahad\Medusa\Serializers\ContentTypeSerializer;

class GetContentTypeResolver extends Resolver
{
	protected function resolve()
	{
		$content_type = app(ContentTypeRepository::class)
			->resolve($this->arg('id'));
		
		return (new ContentTypeSerializer($content_type))
			->setKeys($this->getFieldSelection(1))
			->toArray();
	}
}
