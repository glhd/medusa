<?php

namespace Galahad\Medusa\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\UrlRoutable;

interface Content extends UrlRoutable
{
	public function paginate($per_page = null, $page_name = 'page', $page = null) : LengthAwarePaginator;
	
	public function getContentType() : ContentType;
	
	public function getData() : array;
	
	public function getDescription() : string;
	
	public function setDescription(string $description) : Content;
	
	public function getSlug() : string;
	
	public function setSlug(string $description) : Content;
}
