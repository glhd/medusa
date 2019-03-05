import React, { useMemo } from 'react';
import useEditorContext from './useEditorContext';
import registry from '../registry';

export default function useFields(fields) {
	const context = useEditorContext();
	const { data, changed, touched, errors, onChange, onDependencies } = context;
	
	return useMemo(() => {
		return Object.values(fields)
			.map(field => {
				if (field.component in registry) {
					return {
						Field: registry[field.component],
						props: mapFieldProps(field, context),
					};
				}
				
				throw `Unable to find field component "${ field.component }"`;
			});
	}, [fields, data, changed, touched, errors, onChange, onDependencies]);
}

function mapFieldProps(field, medusa) {
	const { data, changed, touched, errors, dependencies, setDependencies, setData, setTouched } = medusa;
	const { name } = field;
	
	return {
		field: {
			...field,
			rules: JSON.parse(field.rules),
			config: JSON.parse(field.config),
			initial_value: JSON.parse(field.initial_value),
		},
		key: name,
		value: data[name],
		changed: changed[name],
		touched: touched[name],
		errors: name in errors ? errors[name] : [],
		onChange: (value) => {
			setData({
				...data,
				[name]: value,
			});
			
			if (!touched[name]) {
				setTouched({
					...touched,
					[name]: true,
				});
			}
		},
		onDependencies: (value) => setDependencies({
			...dependencies,
			[name]: value,
		}),
	};
}
