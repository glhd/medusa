const registry = {};
const context = require.context('./components/Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

context.keys().forEach(key => {
	const name = key.replace(/(^[\.\/]+|\.js$)/gi, '');
	registry[name] = context(key).default;
	
	if (module.hot) {
		module.hot.accept(key, function() {
			registry[name] = context(key).default;
		});
	}
});

export default registry;
