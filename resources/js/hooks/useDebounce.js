import React, { useEffect, useRef } from 'react';

export default function useDebounce(callback, inputs = [], timeout = 250) {
	const debounce = useRef(null);
	const cycle = useRef(0);
	
	const current_cycle = cycle.current + 1;
	cycle.current = current_cycle;
	
	const isStale = () => cycle.current !== current_cycle;
	
	useEffect(() => {
		clearTimeout(debounce.current);
		debounce.current = setTimeout(() => callback({ isStale }), timeout);
		return () => clearTimeout(debounce.current);
	}, inputs);
	
	return current_cycle;
};
