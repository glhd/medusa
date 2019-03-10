<?php

namespace Galahad\Medusa\Repositories\Content;

use Galahad\Medusa\Contracts\Content as ContentContract;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentRepository implements ContentRepository
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
	
	public function resolveMany(array $ids) : Collection
	{
		return $this->model->newQuery()
			->whereIn('id', $ids)
			->get();
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
