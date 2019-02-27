<?php

namespace Galahad\Medusa\Http\Controllers;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Http\Middleware\ServeInterface;
use Galahad\Medusa\Http\Requests\ContentRequest;
use Galahad\Medusa\Validation\ContentValidator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Middleware\SubstituteBindings;

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
		return view('medusa::content.index', [
			'content_types' => medusa()->allContentTypes(),
			'content_page' => app(Content::class)->paginate(),
		]);
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($content_type)
	{
		return view('medusa::content.create', [
			'content_type' => medusa()->resolveContentType($content_type),
		]);
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ContentRequest $request)
	{
		$data = json_decode($request->input('data'), true);
		
		$content = $this->newContentModel();
		
		$content->content_type = $request->contentType();
		$content->data = $data;
		
		// TODO: slug, description, unique_key
		
		$content->save();
		
		return redirect()->route('medusa.show', $content);
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function show(Content $content)
	{
		dd($content->toArray());
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Content $content)
	{
		return view('medusa::content.edit', [
			'content' => $content,
		]);
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Galahad\Medusa\Contracts\Content $content
	 * @return \Illuminate\Http\Response
	 */
	public function update(ContentRequest $request, Content $content)
	{
		$data = json_decode($request->input('data'), true);
		
		$content->data = $data;
		
		// TODO: slug, description, unique_key
		
		$content->save();
		
		return redirect()->route('medusa.show', $content);
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
	
	/**
	 * @return \Galahad\Medusa\Contracts\Content|\Galahad\Medusa\Models\Content
	 */
	protected function newContentModel() : Content
	{
		$content_class = config('medusa.content_model');
		return new $content_class();
	}
}
