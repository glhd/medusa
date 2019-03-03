import React from 'react';
import Group from '../Group';

export default function Slug(props) {
	const { field, value, onChange } = props;
	const { id, url_prefix } = field.config;
	
	return (
		<Group {...props}>
			<div className="flex items-center shadow appearance-none border rounded w-full py-2 px-3 text-grey-darkest leading-tight">
				<label htmlFor={ id }>
					{ url_prefix }
				</label>
				<input
					className="flex-1 text-grey-dark"
					id={ id }
					value={ value }
					onChange={ e => onChange(e.target.value) }
				/>
			</div>
		</Group>
	);
};
