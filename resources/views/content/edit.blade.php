<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>
@extends('medusa::layout')

@section('nav')
	<li class="ml-3 border-l pl-3">
		<a class="no-underline text-grey hover:text-grey-dark hover:underline" href="{{ route('medusa.show', $content) }}">
			Cancel Editing
		</a>
	</li>
@endsection

@section('content')
	<form action="{{ route('medusa.update', $content) }}" method="post">
		@csrf
		@method('put')
		@medusa($content)
	</form>
@endsection
