import React from 'react';
import useFields from "../hooks/useFields";

export default ({ fields, medusa }) => {
	return useFields(fields, medusa)
		.map(([Field, props]) => <Field medusa={medusa} {...props} />);
};
