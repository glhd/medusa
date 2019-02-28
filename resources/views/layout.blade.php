<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Medusa</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="font-sans antialias">
<div class="bg-grey-lightest border-b p-2">
	<div class="container mx-auto flex items-baseline">
		<h1 class="text-lg font-normal mr-4">
			<a class="no-underline text-grey-dark hover:underline" href="{{ route('medusa.index') }}">
				Medussa
			</a>
		</h1>
		
		<ul class="list-reset flex items-baseline">
			<li class="ml-4">
				<a class="no-underline text-grey hover:text-grey-dark hover:underline" href="{{ route('medusa.index') }}">
					All Content
				</a>
			</li>
			@yield('nav')
		</ul>
	</div>
</div>
<div class="container mx-auto py-8">
	@yield('content')
</div>

@if($snackbar = session('snackbar'))
	<div id="snackbar" class="absolute pin-l pin-r pin-b p-8" style="transition: all .7s; opacity: 0;">
		<div class="container mx-auto text-right">
			<div class="inline-block bg-grey-dark text-white font-semibold py-3 px-6 rounded-full shadow">
				{{ $snackbar }}
			</div>
		</div>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var bar = document.getElementById('snackbar');
		bar.style.opacity = '1';
		bar.style.transform = 'translateY(-20px)';
		setTimeout(function() {
			bar.style.opacity = '0';
			bar.style.transform = 'translateY(0)';
		}, 5000);
	});
	</script>
@endif

</body>
</html>
