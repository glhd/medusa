<?php

namespace Galahad\Medusa\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContentRepository
{
	public function resolve($id) : Content;
	
	public function resolveMany(array $ids) : Collection;
	
	public function exists($id) : bool;
	
	public function paginate($per_page = null, $page = null) : LengthAwarePaginator;
	
	public function search(array $options) : LengthAwarePaginator;
}
