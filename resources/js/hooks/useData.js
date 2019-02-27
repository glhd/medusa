import { useState } from 'react';

export default (fields, old, existing) => {
	const [data, setData] = useState(() => {
		const data = {};
		const old_data = ('data' in old) ? JSON.parse(old.data) : {};
		
		Object.values(fields).forEach(field => {
			data[field.name] = field.initial_value;
		});
		
		return {
			...data,
			...existing,
			...old_data,
		};
	});
	
	const onChange = (name) => (value) => {
		setData({
			...data,
			[name]: value,
		});
	};
	
	return [data, onChange];
};
