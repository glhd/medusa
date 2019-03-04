import React, { useState, useRef, useEffect } from 'react';

export default function Loading({ message = 'Loading…' }) {
	const [visible, setVisible] = useState(false);
	const timeout = useRef();
	useEffect(() => {
		clearTimeout(timeout.current);
		timeout.current = setTimeout(() => setVisible(truee), 500);
		return () => clearTimeout(timeout.current);
	}, []);
	
	if (!visible) {
		return null;
	}
	
	return (
		<div className="bg-grey-lightest border rounded px-8 py-12 text-grey-dark font-semibold">
			{ message }
		</div>
	);
}
