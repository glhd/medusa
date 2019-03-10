<?php

namespace Galahad\Medusa\Tests\Support\ContentTypes;

use Galahad\Medusa\Collections\FieldCollection;
use Galahad\Medusa\ContentType;
use Galahad\Medusa\Fields;

class Page extends ContentType
{
	public function getFields() : FieldCollection
	{
		return new FieldCollection([
			Fields\Text::make('title')
				->addRules('required|min:10|max:65'),
			Fields\Slug::make('slug')
				->setRequired(),
			Fields\RichText::make('body')
				->setRequired(),
		]);
	}
	
	public function generateSlugFromData(array $data, string $existing = null) : string
	{
		return $data['slug'];
	}
	
	public function generateDescriptionFromData(array $data, string $existing = null) : string
	{
		return $data['title'];
	}
}
