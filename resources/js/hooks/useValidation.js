import React, { useState, useEffect, useRef } from 'react';
import Validator from 'validatorjs';

export default function useValidation(data, rules, server_errors = {}) {
	const [errors, setErrors] = useState(server_errors);
	const cycle = useRef(0);
	const debounce = useRef(null);
	
	useEffect(() => {
		clearTimeout(debounce.current);
		debounce.current = setTimeout(() => {
			const current_cycle = cycle.current + 1;
			cycle.current = current_cycle;
			
			const validator = new Validator(data, rules);
			
			const handler = () => {
				if (cycle.current === current_cycle) {
					setErrors(validator.errors.all());
				}
			};
			
			validator.checkAsync(handler, handler);
		}, 0 === cycle.current ? 1 : 250);
	}, [data, rules]);
	
	return errors;
};
