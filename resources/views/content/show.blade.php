<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>
@extends('medusa::layout')

@section('nav')
	<li class="ml-3 border-l pl-3">
		<a class="no-underline text-grey hover:text-grey-dark hover:underline" href="{{ route('medusa.edit', $content) }}">
			Edit &ldquo;{{ $content->getDescription() }}&rdquo;
		</a>
	</li>
@endsection

@section('content')
	<?php dump($content->toArray()); ?>
@endsection
