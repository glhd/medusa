<?php

namespace Galahad\Medusa\Serializers;

class MutationResponseSerializer extends Serializer
{
	/**
	 * @var array
	 */
	protected $response;
	
	/**
	 * @var array
	 */
	protected $keys = ['code', 'success', 'message'];
	
	/**
	 * @var int
	 */
	protected $code;
	
	/**
	 * @var bool
	 */
	protected $success;
	
	/**
	 * @var string
	 */
	protected $message;
	
	public function __construct(int $code, bool $success, string $message, array $response)
	{
		$this->code = $code;
		$this->success = $success;
		$this->message = $message;
		$this->response = $response;
	}
	
	protected function serializeCode() : int
	{
		return $this->code;
	}
	
	protected function serializeSuccess() : bool
	{
		return $this->success;
	}
	
	protected function serializeMessage() : string
	{
		return $this->message;
	}
	
	protected function serialize($key)
	{
		if (isset($this->response[$key])) {
			return $this->response[$key];
		}
		
		return parent::serialize($key);
	}
}
