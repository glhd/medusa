<?php /** @var \Galahad\Medusa\Serializers\ContentTypeSerializer $serialized */ ?>
<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>
<?php /** @var \Galahad\Medusa\Contracts\Content $content */ ?>

<div
	id="medusa"
	data-config="{{ $serialized->toJson() }}"
	data-old="{{ json_encode((object) old('data', [])) }}"
	data-errors="{{ json_encode((object) $errors->getBag('default')->toArray()) }}"
	data-existing="{{ json_encode($content ? $content->getData() : new stdClass()) }}"
></div>

@if($inline)
	<script>
	<?php echo e($script); ?>
	</script>
@else
	<script src="{{ $src }}"></script>
@endif
