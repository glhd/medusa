<?php

namespace Galahad\Medusa\Http\Requests;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Validation\ContentValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ContentRequest extends FormRequest
{
	/**
	 * @var ContentType
	 */
	protected $content_type;
	
	public function authorize()
	{
		// $content = $this->route('content');
		// Gate::authorize($content ? 'update' : 'create', [$content ?? Content::class, $this->contentType()]);
		return true; // FIXME
	}
	
	public function contentType() : ContentType
	{
		if (null === $this->content_type) {
			$content = $this->route('content');
			$this->content_type = $content instanceof Content
				? $content->getContentType()
				: medusa()->resolveContentType($this->input('content_type'));
		}
		
		return $this->content_type;
	}
	
	public function contentData() : array
	{
		return json_decode($this->input('data'), true);
	}
	
	public function withValidator(Validator $validator)
	{
		$validator->after(function() use ($validator) {
			if ($this->input('content_type' !== $this->contentType()->getId())) {
				$validator->messages()->add('content_type', 'You cannot change the content type of existing content.');
			}
			
			$content_validator = new ContentValidator(
				$validator->getTranslator(),
				json_decode($this->input('data'), true),
				$this->contentType()
			);
			
			if ($content_validator->fails()) {
				foreach ($content_validator->messages()->messages() as $key => $messages) {
					foreach ($messages as $message) {
						$validator->messages()->add($key, $message);
					}
				}
			}
		});
	}
	
	public function rules()
	{
		// TODO: Singleton and uniques
		return [
			'content_type' => 'required',
			'data' => 'required|json',
		];
	}
}
