<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeRepository;
use Galahad\Medusa\Serializers\ContentTypeSerializer;

class AllContentTypesResolver extends Resolver
{
	protected function resolve()
	{
		$selection = $this->getFieldSelection(1);
		
		return app(ContentTypeRepository::class)->all()
			->toBase()
			->map(function(ContentType $content_type) use ($selection) {
				return (new ContentTypeSerializer($content_type))->setKeys($selection);
			})
			->values()
			->toArray();
	}
}
