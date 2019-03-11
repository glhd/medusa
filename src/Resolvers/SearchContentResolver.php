<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Serializers\ContentSerializer;

class SearchContentResolver extends Resolver
{
	protected function resolve()
	{
		$this->authorize('view', config('medusa.content_model', Content::class));
		
		$paginator = app(ContentRepository::class)->search([
			'query' => $this->arg('query'),
			'content_type_id' => $this->arg('content_type_id'),
			'page' => $this->arg('page', 1),
		]);
		
		$selection = $this->getFieldSelection(2);
		
		return [
			'total' => $paginator->total(),
			'per_page' => $paginator->perPage(),
			'current_page' => $paginator->currentPage(),
			'last_page' => $paginator->lastPage(),
			'content' => collect($paginator->items())
				->map(function(Content $content) use ($selection) {
					return (new ContentSerializer($content))
						->setKeys($selection['content'])
						->toArray();
				})
				->toArray(),
		];
	}
}
