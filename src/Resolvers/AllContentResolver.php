<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Serializers\ContentSerializer;

class AllContentResolver extends Resolver
{
	protected function resolve()
	{
		$this->authorize('view', config('medusa.content_model', Content::class));
		
		$page = $this->arg('page', 1);
		
		$paginator = app(ContentRepository::class)->paginate(20, $page);
		$selection = $this->info->getFieldSelection(2);
		
		return [
			'total' => $paginator->total(),
			'per_page' => $paginator->perPage(),
			'current_page' => $paginator->currentPage(),
			'last_page' => $paginator->lastPage(),
			'content' => collect($paginator->items())
				->map(function(Content $content) use ($selection) {
					return (new ContentSerializer($content))->setKeys($selection['content'])->toArray();
				})
				->toArray(),
		];
	}
}
