<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Serializers\ContentSerializer;
use Galahad\Medusa\Serializers\MutationResponseSerializer;
use Galahad\Medusa\Validation\ContentValidator;
use Illuminate\Contracts\Translation\Translator;

class UpdateContentResolver extends Resolver
{
	protected $deferred = true;
	
	protected function prepareDeferred() : void
	{
		GetContentResolver::queue($this->arg('id'));
	}
	
	protected function resolve()
	{
		if (!$content = GetContentResolver::load($this->arg('id'))) {
			return new MutationResponseSerializer(
				404,
				false,
				'Content not found.',
				['content' => null]
			);
		}
		
		$this->authorize('update', $content);
		
		$data = json_decode($this->arg('data'), true);
		
		$validator = new ContentValidator(app(Translator::class), $data, $content->getContentType());
		
		if ($validator->fails()) {
			return new MutationResponseSerializer(
				422,
				false,
				'Validation failed.',
				['validation' => array_values($validator->messages()->all())]
			);
		}
		
		$content->setData($data);
		$content->save();
		
		return (new ContentSerializer($content))
			->setKeys($this->getFieldSelection(2))
			->toArray();
	}
}
