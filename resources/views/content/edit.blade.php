<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>
@extends('medusa::layout')

@section('content')
	<form action="{{ route('medusa.update', $content) }}" method="post">
		@csrf
		@method('put')
		@medusa($content)
	</form>
@endsection
