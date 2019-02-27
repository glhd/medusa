import React from 'react';
import Group from '../Form/Group';

export default function Text(props) {
	const { id, value, onChange } = props;
	return (
		<Group {...props}>
			<input
				className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
				id={ id }
				value={ value }
				onChange={ e => onChange(e.target.value) }
			/>
		</Group>
	);
};
