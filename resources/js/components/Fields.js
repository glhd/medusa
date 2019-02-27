import React from 'react';

const load = require.context('./Fields', false, /^(?!.*\.stories\.js$).*\.js$/i);

export default ({ fields, existing, data }) => {
	return Object.values(fields)
		.map(field => {
			const Field = load('./' + field.component + '.js').default;
			return (
				<Field
					key={ field.name }
					{ ...field }
					field={field}
					all_data={ data }
					existing={ field.name in existing ? existing[field.name] : null }
				/>
			);
		});
};
