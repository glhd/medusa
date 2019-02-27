<?php

namespace Galahad\Medusa\Contracts;

use Galahad\Medusa\Collections\ContentTypeCollection;

interface ContentTypeResolver
{
	public function resolve($id) : ContentType;
	
	public function exists($id) : bool;
	
	public function all() : ContentTypeCollection;
}
