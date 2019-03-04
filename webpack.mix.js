const LaravelMix = require('laravel-mix');
const ExtractPlugin = require("mini-css-extract-plugin");

LaravelMix
	.disableSuccessNotifications()
	.setPublicPath('./resources/js/dist')
	.react('resources/js/medusa.js', 'resources/js/dist/')
	.autoload({
		jquery: ['$', 'window.jQuery']
	})
	.webpackConfig(() => {
		const plugins = [];
		const loaders = ['style-loader'];
		
		if ('production' === process.env.NODE_ENV) {
			plugins.push(new ExtractPlugin());
			loaders.push({
				loader: ExtractPlugin.loader,
				options: {
					publicPath: './resources/css/dist/'
				}
			});
		}
		
		loaders.push({
			loader: 'css-loader',
			options: { importLoaders: 1 }
		});
		loaders.push('postcss-loader');
		
		return {
			plugins,
			module: { rules: [{ test: /\.css$/, loaders }] },
		};
	})
	.options({
		hmrOptions: {
			port: 8088,
		}
	});
