<?php

namespace Galahad\Medusa\Http\Controllers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Http\Middleware\ServeInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContentController extends Controller
{
	public function __construct()
	{
		$this->middleware([
			DispatchMedusaEvent::class,
			Authorize::class,
			ServeInterface::class,
		]);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function show(Content $content)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Content $content)
	{
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Content $content)
	{
		//
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Content $content)
	{
		//
	}
}
