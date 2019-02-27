<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>

<div
	id="medusa"
	data-config="{{ $stitched->toJson(JSON_FORCE_OBJECT) }}"
	data-old="{{ json_encode(old('data', []),  JSON_FORCE_OBJECT) }}"
	data-errors="{{ json_encode($errors->getBag('default'),  JSON_FORCE_OBJECT) }}"
	data-existing="{{ json_encode($content->data ?? [],  JSON_FORCE_OBJECT) }}"
></div>

@if($inline)
	<script>
	<?php echo e($script); ?>
	</script>
@else
	<script src="{{ $src }}"></script>
@endif
