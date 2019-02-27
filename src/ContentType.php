<?php

namespace Galahad\Medusa;

use Galahad\Medusa\Contracts\ContentType as ContentTypeContract;
use Illuminate\Support\Str;

abstract class ContentType implements ContentTypeContract
{
	public function getId()
	{
		return Str::snake(class_basename(static::class));
	}
	
	public function getTitle() : string
	{
		return Str::title(Str::snake(class_basename(static::class), ' '));
	}
	
	public function isSingleton() : bool
	{
		return false;
	}
}
