import React from 'react';

export default function Loading({ message = 'Loading…' }) {
	return (
		<div className="bg-grey-lightest border rounded px-8 py-12 text-grey-dark font-semibold">
			{ message }
		</div>
	);
}
