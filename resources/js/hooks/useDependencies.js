import { useState } from 'react';

export default function useDependencies() {
	const [dependencies, setDependencies] = useState({});
	
	const onDependencies = (name) => (value) => {
		setDependencies({
			...dependencies,
			[name]: value,
		});
	};
	
	return [dependencies, onDependencies];
};
