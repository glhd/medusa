@extends('medusa::layout')

@section('content')
	<form action="{{ route('medusa.store') }}" method="post">
		@csrf
		@medusa($content_type)
	</form>
@endsection
