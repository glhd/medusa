<?php

namespace Galahad\Medusa\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContentResolver
{
	public function resolve($id) : Content;
	
	public function exists($id) : bool;
	
	public function paginate($per_page = null, $page = null) : LengthAwarePaginator;
}
