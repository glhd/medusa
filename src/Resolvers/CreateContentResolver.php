<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentTypeRepository;
use Galahad\Medusa\Serializers\ContentSerializer;
use Galahad\Medusa\Serializers\MutationResponseSerializer;
use Galahad\Medusa\Validation\ContentValidator;
use Illuminate\Contracts\Translation\Translator;

class CreateContentResolver extends Resolver
{
	protected function resolve()
	{
		$content_type = app(ContentTypeRepository::class)
			->resolve($this->arg('content_type_id'));
		
		// FIXME: $this->authorize('create', [Content::class, $content_type]);
		
		$data = json_decode($this->arg('data'), true);
		
		$validator = new ContentValidator(app(Translator::class), $data, $content_type);
		
		if ($validator->fails()) {
			return new MutationResponseSerializer(
				422,
				false,
				'Validation failed.',
				['validation' => array_values($validator->messages()->all())]
			);
		}
		
		$content_class = config('medusa.content_model');
		$content = new $content_class();
		
		$content->setContentType($content_type);
		$content->setData($data);
		$content->save();
		
		return new MutationResponseSerializer(
			201,
			true,
			'Content created.',
			[
				'content' => (new ContentSerializer($content))
					->setKeys($this->info->getFieldSelection(2))
					->toArray(),
			]
		);
	}
}
