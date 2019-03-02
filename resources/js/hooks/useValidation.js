import React, { useState } from 'react';
import useDebounce from './useDebounce';
import Validator from 'validatorjs';

export default function useValidation(data, rules, touched = {}, server_errors = {}) {
	const [errors, setErrors] = useState({});
	
	// FIXME: Server errors may have duplicates, and aren't showing because of changed/touched checks
	// FIXME: I've just removed them for now
	
	useDebounce(({ isStale }) => {
		const validator = new Validator(data, rules);
		
		const handler = () => {
			if (isStale()) {
				return;
			}
			
			const errors = validator.errors.all();
			
			Object.entries(server_errors).forEach(([key, value]) => {
				if (!touched[key]) {
					errors[key] = errors[key] || [];
					errors[key].push(value);
				}
			});
			
			setErrors(errors);
		};
		
		validator.checkAsync(handler, handler);
	}, 250);
	
	return errors;
};
