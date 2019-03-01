require('laravel-mix')
	.disableSuccessNotifications()
	.setPublicPath('./resources/js/dist')
	.react('resources/js/medusa.js', 'resources/js/dist/')
	.webpackConfig({
		module: {
			rules: [
				{
					test: /\.css$/,
					loaders: [
						'style-loader',
						{
							loader: 'css-loader',
							options: { importLoaders: 1 }
						},
						'postcss-loader'
					],
				},
			],
		},
	})
	.options({
		hmrOptions: {
			port: 8088,
		}
	});
