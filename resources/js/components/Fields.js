import React from 'react';
import useFields from "../hooks/useFields";

export default function Fields({ fields }) {
	return useFields(fields)
		.map(({ Field, props }) => <Field {...props} id={`medusa-${props.field.name}`} />);
};
