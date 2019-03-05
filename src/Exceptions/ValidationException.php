<?php

namespace Galahad\Medusa\Exceptions;

use Galahad\Medusa\Validation\ContentValidator;
use GraphQL\Server\RequestError;

class ValidationException extends RequestError
{
	/**
	 * @var \Galahad\Medusa\Validation\ContentValidator
	 */
	public $validator;
	
	public function __construct(ContentValidator $validator)
	{
		parent::__construct('The given data was invalid.');
		
		$this->validator = $validator;
	}
}
