<?php /** @var \Illuminate\Pagination\LengthAwarePaginator|\Galahad\Medusa\Contracts\Content[] $content_page */ ?>
<?php /** @var \Galahad\Medusa\Collections\ContentTypeCollection|\Galahad\Medusa\Contracts\ContentType[] $content_types */ ?>

@extends('medusa::layout')

@section('nav')
	@foreach($content_types as $content_type)
		<li class="ml-3 border-l pl-3">
			<a class="no-underline text-grey hover:text-grey-dark hover:underline" href="{{ route('medusa.create', $content_type) }}">
				New {{ $content_type->getTitle() }}
			</a>
		</li>
	@endforeach
@endsection

@section('content')
	
	@if($content_page->count())
		
		<table class="w-full">
			<thead>
				<tr>
					<th class="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						Content Type
					</th>
					<th class="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						URL Slug
					</th>
					<th class="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						Description
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach($content_page as $content)
					<tr>
						<td class="px-2 py-4">
							<span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">
								{{ $content->getContentType()->getTitle() }}
							</span>
						</td>
						<td class="px-2 py-4">
							<span class="font-mono text-sm px-1 py-px border rounded bg-grey-lightest text-grey-dark">
								{{ $content->getSlug() }}
							</span>
						</td>
						<td class="px-2 py-4">
							<a class="font-semibold text-grey-darker no-underline hover:underline hover:text-blue" href="{{ route('medusa.edit', $content) }}">
								{{ $content->getDescription() }}
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
	
	<div class="-mx-1 my-10 py-10 border-t border-grey-lighter">
		@foreach($content_types as $content_type)
			<a class="mx-1 inline-block bg-blue border border-blue-darker text-white border rounded px-4 py-2 no-underline" href="{{ route('medusa.create', $content_type) }}">
				Create a {{ $content_type->getTitle() }}
			</a>
		@endforeach
	</div>
	
@endsection
