import React from 'react';

const load = require.context('./Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

export default ({ fields, existing, data, errors, onChange, onDependencies }) => {
	return Object.values(fields)
		.map(field => {
			const Field = load('./' + field.component + '.js').default;
			return (
				<Field
					key={ field.name }
					{ ...field }
					value={ data[field.name] }
					existing={ field.name in existing ? existing[field.name] : null }
					errors={ field.name in errors ? errors[field.name] : [] }
					onChange={ onChange(field.name) }
					onDependencies={ onDependencies(field.name) }
				/>
			);
		});
};
