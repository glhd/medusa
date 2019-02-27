<?php

namespace Galahad\Medusa\Models;

use Galahad\Medusa\Contracts\Content as ContentContract;
use Galahad\Medusa\Contracts\ContentType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property ContentType|string $content_type
 * @property array $data
 * @property \Illuminate\Support\Carbon $published_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class Content extends Model implements ContentContract
{
	use SoftDeletes, Concerns\DescribesContent, Concerns\SlugsContent;
	
	protected $casts = [
		'data' => 'array',
		'published_at' => 'datetime',
		'deleted_at' => 'datetime',
	];
	
	/**
	 * @var ContentType
	 */
	protected $content_type_instance;
	
	public function __construct(array $attributes = [])
	{
		$this->table = config('medusa.content_table');
		
		parent::__construct($attributes);
	}
	
	public function paginate($per_page = null, $page_name = 'page', $page = null) : LengthAwarePaginator
	{
		return $this->newQuery()->paginate($per_page, null, $page_name, $page);
	}
	
	public function getContentType() : ContentType
	{
		if (null === $this->content_type_instance) {
			$this->content_type_instance = medusa()->resolveContentType($this->getAttribute('content_type'));
		}
		
		return $this->content_type_instance;
	}
	
	public function setContentType(ContentType $content_type) : ContentContract
	{
		$this->setContentTypeAttribute($content_type);
		
		return $this;
	}
	
	protected function setContentTypeAttribute($content_type)
	{
		if ($content_type instanceof ContentType) {
			$this->content_type_instance = $content_type;
			$content_type = $content_type->getId();
		}
		
		$this->attributes['content_type'] = $content_type;
	}
	
	public function getData() : array
	{
		return $this->getAttribute('data');
	}
	
	public function setData(array $data) : ContentContract
	{
		$this->setAttribute('data', $data);
		
		return $this;
	}
	
	public function save(array $options = []) : bool
	{
		return parent::save($options);
	}
}
