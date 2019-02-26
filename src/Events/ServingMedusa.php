<?php

namespace Galahad\Medusa\Events;

use Illuminate\Http\Request;

class ServingMedusa
{
	/**
	 * The current request
	 *
	 * @var \Illuminate\Http\Request
	 */
	public $request;
	
	/**
	 * Constructor
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}
}
