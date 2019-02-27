<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>

<div
	id="medusa"
	data-config="{{ $stitched->toJson() }}"
	data-old="{{ json_encode((object) old('data', [])) }}"
	data-errors="{{ json_encode((object) $errors->getBag('default')->toArray()) }}"
	data-existing="{{ json_encode($content->data ?? new stdClass()) }}"
></div>

@if($inline)
	<script>
	<?php echo e($script); ?>
	</script>
@else
	<script src="{{ $src }}"></script>
@endif
