<?php

namespace Galahad\Medusa\Repositories\Content;

use Galahad\Medusa\Contracts\Content as ContentContract;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Models\Content;
use GraphQL\Error\UserError;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
	
	public function search(array $options) : LengthAwarePaginator
	{
		$query = Arr::get($options, 'query');
		$per_page = Arr::get($options, 'per_page', 20);
		$page = Arr::get($options, 'page', 1);
		
		if (empty($query)) {
			throw new UserError('Query cannot be empty');
		}
		
		$builder = $this->model->newQuery()->latest()
			->where(function(Builder $builder) use ($query) {
				$builder->orWhere('slug', 'like', "%{$query}%");
				$builder->orWhere('description', 'like', "%{$query}%");
				$builder->orWhere('data', 'like', "%{$query}%");
			})
			->orderByRaw('description like ? desc', "%{$query}%")
			->orderByRaw('slug like ? desc', "%{$query}%");
		
		if ($content_type_id = Arr::get($options, 'content_type_id')) {
			$builder->where('content_type', '=', $content_type_id);
		}
		
		return $builder->paginate($per_page, null, null, $page);
	}
}
