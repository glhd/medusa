import React, { useState } from 'react';

const load = require.context('./Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

export default ({ config }) => {
	const [data, setData] = useState(() => {
		const data = {};
		
		Object.values(config.fields).forEach(field => {
			data[field.name] = field.initial_value;
		});
		
		return data;
	});
	
	const fields = Object.values(config.fields)
		.map(field => {
			const Field = load('./' + field.component + '.js').default;
			const onChange = (value) => setData({
				...data,
				[field.name]: value,
			});
			return <Field key={field.name} { ...field } value={data[field.name]} onChange={onChange} />;
		});
	
	return (
		<div>
			{ fields }
			<pre className="bg-grey-lightest border shadow-lg text-xs text-grey-darker font-mono rounded p-8 my-8">{ JSON.stringify(data, null, 2) }</pre>
			<pre className="bg-grey-lightest border shadow-lg text-xs text-grey-darker font-mono rounded p-8 my-8">{ JSON.stringify(config.rules, null, 2) }</pre>
		</div>
	);
};
