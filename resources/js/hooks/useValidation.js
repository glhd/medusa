import React, { useState, useMemo, useEffect } from 'react';
import useDebounce from './useDebounce';
import Validator from 'validatorjs';

Validator.registerMissedRuleValidator((inputValue, ruleValue, attribute, callback) => {}, '');

export default function useValidation(data, fields) {
	const [errors, setErrors] = useState({});
	
	const rules = useMemo(() => {
		const rules = {};
		
		Object.values(fields).forEach(field => {
			const field_rules = ('string' === typeof field.rules || field.rules instanceof String)
				? JSON.parse(field.rules)
				: field.rules;
			Object.entries(field_rules).forEach(([ key, value ]) => {
				rules[key] = value;
			});
		});
		
		return rules;
	}, [fields]);
	
	useDebounce(({ isStale }) => {
		const validator = new Validator(data, rules);
		const handler = () => isStale() ? null : setErrors(validator.errors.all());
		validator.checkAsync(handler, handler);
	}, [data, fields], 250);
	
	return errors;
};
