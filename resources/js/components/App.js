import React from 'react';

const load = require.context('./Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

export default ({ config }) => {
	const fields = Object.values(config.fields)
		.map(field => {
			const Field = load('./' + field.component + '.js').default;
			return <Field { ...field } />;
		});
	
	return (
		<div>
			{ fields }
			<pre className="bg-grey-lightest border shadow-lg text-xs text-grey-darker font-mono rounded p-8 my-8">{ JSON.stringify(config.rules, null, 2) }</pre>
		</div>
	);
};
