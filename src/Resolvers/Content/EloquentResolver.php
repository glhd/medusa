<?php

namespace Galahad\Medusa\Resolvers\Content;

use Galahad\Medusa\Contracts\Content as ContentContract;
use Galahad\Medusa\Contracts\ContentResolver;
use Galahad\Medusa\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentResolver implements ContentResolver
{
	/**
	 * @var \Galahad\Medusa\Models\Content
	 */
	protected $model;
	
	public function __construct(Content $model)
	{
		$this->model = $model;
	}
	
	public function resolve($id) : ContentContract
	{
		return $this->model->newQuery()->findOrFail($id);
	}
	
	public function exists($id) : bool
	{
		return $this->model->newQuery()->where('id', '=', $id)->exists();
	}
	
	public function paginate($per_page = null, $page = null) : LengthAwarePaginator
	{
		return $this->model->newQuery()->latest()->paginate($per_page, null, null, $page ?? 1);
	}
}
