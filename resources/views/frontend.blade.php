<?php /** @var \Galahad\Medusa\Serializers\ContentTypeSerializer $serialized */ ?>
<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>
<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>

@extends('medusa::layout')

@section('medusa')
	<div id="medusa"></div>
	{{ $config }}
	{{ $script }}
@endsection

@push('head')
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
	{{ $styles }}
@endpush
