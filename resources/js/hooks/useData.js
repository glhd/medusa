import { useState } from 'react';

export default (fields) => {
	const [data, setData] = useState(() => {
		const data = {};
		
		Object.values(fields).forEach(field => {
			data[field.name] = field.initial_value;
		});
		
		return data;
	});
	
	const onChange = (name) => (value) => {
		setData({
			...data,
			[name]: value,
		});
	};
	
	return [data, onChange];
};
