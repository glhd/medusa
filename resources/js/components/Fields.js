import React from 'react';

const load = require.context('./Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

export default ({ fields, data, errors, onChange, onDependencies }) => {
	return Object.values(fields)
		.map(field => {
			const Field = load('./' + field.component + '.js').default;
			return (
				<Field
					key={ field.name }
					{ ...field }
					value={ data[field.name] }
					errors={ field.name in errors ? errors[field.name] : [] }
					onChange={ onChange(field.name) }
					onDependencies={ onDependencies(field.name) }
				/>
			);
		});
};
