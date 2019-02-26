
<div id="medusa"></div>

@if($inline)
	<script>{{ $script }}</script>
@else
	<script src="{{ $src }}"></script>
@endif

