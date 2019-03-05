import React from 'react';

export default function DragHandle(props) {
	return (
		<div
			style={ {
				width: '16px',
				marginRight: '8px',
				background: `linear-gradient(90deg, #fff 2px, transparent 1%) center,
						linear-gradient(#fff 2px, transparent 1%) center,
						#dadada`,
				backgroundSize: '4px 4px',
			} }
			{ ...props }
		/>
	);
}
