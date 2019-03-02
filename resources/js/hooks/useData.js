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
	
	return { data, changed, touched, setData, setTouched };
};
