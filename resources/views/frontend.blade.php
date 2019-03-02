<?php /** @var \Galahad\Medusa\Serializers\ContentTypeSerializer $serialized */ ?>
<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>
<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>

@extends('medusa::layout')
@section('medusa')
	<div id="medusa"></div>
	{{ $config }}
	{{ $script }}
@endsection
