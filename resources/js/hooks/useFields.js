import React, { useMemo } from 'react';
import { useMedusaContext } from "./useMedusaContext";
import registry from '../registry';

export default function useFields(fields) {
	const medusa = useMedusaContext();
	const { data, changed, touched, errors, onChange, onDependencies } = medusa;
	
	return useMemo(() => {
		return Object.values(fields)
			.map(field => {
				if (field.component in registry) {
					return {
						Field: registry[field.component],
						props: mapFieldProps(field, medusa),
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
		field,
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
