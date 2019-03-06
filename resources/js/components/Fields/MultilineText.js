import React from 'react';
import Group from '../Group';
// import Debugger from '../Debugger';

export default function MultilineText(props) {
	const { field, value, onChange } = props;
	const { id } = field;
	
	return (
		<Group {...props}>
			<textarea
				className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
				id={ id }
				value={ value }
				onChange={ e => onChange(e.target.value) }
			/>
		</Group>
	);
};
