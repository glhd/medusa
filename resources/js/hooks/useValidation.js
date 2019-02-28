import React, { useEffect, useRef, useState } from 'react';
import Validator from 'validatorjs';

export default function useValidation(data, rules, touched = {}, server_errors = {}) {
	const [errors, setErrors] = useState({});
	const cycle = useRef(0);
	const debounce = useRef(null);
	
	// FIXME: Server errors may have duplicates, and aren't showing because of changed/touched checks
	// FIXME: I've just removed them for now
	
	useEffect(() => {
		clearTimeout(debounce.current);
		debounce.current = setTimeout(() => {
			const current_cycle = cycle.current + 1;
			cycle.current = current_cycle;
			
			const validator = new Validator(data, rules);
			
			const handler = () => {
				if (cycle.current === current_cycle) {
					const errors = validator.errors.all();
					Object.entries(server_errors).forEach(([key, value]) => {
						if (!touched[key]) {
							errors[key] = errors[key] || [];
							errors[key].push(value);
						}
					});
					setErrors(errors);
				}
			};
			
			validator.checkAsync(handler, handler);
		}, 0 === cycle.current ? 1 : 250);
	}, [data, rules, touched, server_errors]);
	
	return errors;
};
