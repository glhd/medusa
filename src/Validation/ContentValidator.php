<?php

namespace Galahad\Medusa\Validation;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\Field;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator;

class ContentValidator extends Validator
{
	public function __construct(Translator $translator, array $data, ContentType $content_type)
	{
		$validation_params = $this->mapContentTypeToValidationParams($content_type);
		
		parent::__construct($translator, $data, ...$validation_params);
	}
	
	protected function mapContentTypeToValidationParams(ContentType $content_type) : array
	{
		$rules = $content_type->getFields()
			->toBase()
			->map(function(Field $field) {
				return $field->getRules();
			})
			->collapse()
			->toArray();
		
		return [$rules, [], []]; // FIXME
	}
}
