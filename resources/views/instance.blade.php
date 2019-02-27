
<div
	id="medusa"
	data-config="{{ $stitched->toJson() }}"
	data-old="{{ json_encode(old()) }}"
	data-errors="{{ json_encode($errors->getBag('default')) }}"
	data-existing="{{ json_encode($content->data ?? []) }}"
></div>

@if($inline)
	<script>{{ $script }}</script>
@else
	<script src="{{ $src }}"></script>
@endif
