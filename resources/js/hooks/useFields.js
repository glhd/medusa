import React, { useMemo } from 'react';
import registry from '../registry';

export default function useFields(fields, medusa) {
	const { data, changed, touched, errors, onChange, onDependencies } = medusa;
	return useMemo(() => {
		return Object.values(fields)
			.map(field => {
				if (field.component in registry) {
					return [
						registry[field.component],
						mapFieldProps(field, medusa)
					];
				} else {
					return [
						() => <div className="text-red">Unable to load '{ field.component }'</div>,
						{},
					];
				}
			});
	}, [fields, data, changed, touched, errors, onChange, onDependencies]);
}

function mapFieldProps(field, props) {
	const { data, changed, touched, errors, onChange, onDependencies } = props;
	const { name } = field;
	
	return {
		field,
		key: name,
		value: data[name],
		changed: changed[name],
		touched: touched[name],
		errors: name in errors ? errors[name] : [],
		onChange: onChange(name),
		onDependencies: onDependencies(name),
	};
}
