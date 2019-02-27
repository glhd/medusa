<?php /** @var \Illuminate\Pagination\LengthAwarePaginator|\Galahad\Medusa\Contracts\Content[] $content_page */ ?>
<?php /** @var \Galahad\Medusa\Collections\ContentTypeCollection|\Galahad\Medusa\Contracts\ContentType[] $content_types */ ?>

@extends('medusa::layout')

@section('content')
	
	@if($content_page->count())
		
		<table class="w-full">
			<thead>
				<tr>
					<th class="border-b p-2 font-bold">Slug</th>
					<th class="border-b p-2 font-bold">Description</th>
					<th class="border-b p-2 font-bold"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($content_page as $content)
					<tr>
						<td class="p-2">
							<span class="font-mono text-sm p-1 border rounded bg-grey-lightest text-grey-dark">
								{{ $content->getSlug() }}
							</span>
						</td>
						<td class="p-2">
							{{ $content->getDescription() }}
						</td>
						<td class="p-2">
							<a href="{{ route('medusa.edit', $content) }}">
								Edit
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		
		{{ $content_page->links() }}
		
	@else
		
		<div class="my-8 border rounded p-8 text-grey">
			There's no content yet.
		</div>
		
	@endif
	
	<hr />
	
	@foreach($content_types as $content_type)
		<a class="btn" href="{{ route('medusa.create', $content_type) }}">
			Create New {{ $content_type->getTitle() }}
		</a>
	@endforeach
	
@endsection
