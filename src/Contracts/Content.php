<?php

namespace Galahad\Medusa\Contracts;

interface Content
{
	public function getContentType() : ContentType;
	
	public function setAttribute($key, $value);
}
