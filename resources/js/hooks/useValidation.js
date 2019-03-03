import React, { useState } from 'react';
import useDebounce from './useDebounce';
import Validator from 'validatorjs';

export default function useValidation(data, rules_json, touched = {}) {
	const [errors, setErrors] = useState({});
	
	const rules = JSON.parse(rules_json);
	
	useDebounce(({ isStale }) => {
		const validator = new Validator(data, rules);
		
		const handler = () => {
			if (isStale()) {
				return;
			}
			
			setErrors(validator.errors.all());
		};
		
		validator.checkAsync(handler, handler);
	}, 250);
	
	return errors;
};
