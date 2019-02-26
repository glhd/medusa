require('laravel-mix')
	.disableNotifications()
	.setPublicPath('./resources/js/dist/')
	.react('resources/js/medusa.js', 'resources/js/dist/')
	.options({
		hmrOptions: {
			port: 8088,
		}
	});
