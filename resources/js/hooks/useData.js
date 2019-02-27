import { useState, useMemo } from 'react';

export default (fields, initial) => {
	const [data, setData] = useState(initial);
	const [touched, setTouched] = useState(() => {
		const touched = {};
		
		Object.keys(initial).forEach(key => {
			touched[key] = (
				key in initial
				&& key in fields
				&& initial[key] !== fields[key].initial_value
			);
		});
		
		return touched;
	});
	
	const changed = useMemo(() => {
		const changed = {};
		
		Object.entries(initial).forEach(([key, value]) => {
			changed[key] = (key in data && value !== data[key]);
		});
		
		return changed;
	}, [data, initial]);
	
	const onChange = (name) => (value) => {
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
	};
	
	return [data, changed, touched, onChange];
};
