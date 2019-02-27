import React from 'react';

export default (props) => {
	const { name, errors, label, initial_value, value, onChange } = props;
	const { url_prefix } = props.config;
	
	const id = `medusa-${ name }`;
	const dirty = initial_value !== value;
	
	return (
		<div className="py-4">
			<label className="block bold mb-2" htmlFor={ id }>
				{ label }
				{ dirty && <span className="ml-1 text-grey-light text-sm">(changed)</span> }
			</label>
			<div className="flex items-center shadow appearance-none border rounded w-full py-2 px-3 text-grey-darkest leading-tight">
				<label htmlFor={ id }>
					{ url_prefix }
				</label>
				<input
					className="flex-1 text-grey-dark"
					id={ id }
					value={ props.value }
					onChange={ e => onChange(e.target.value) }
				/>
			</div>
			{ (dirty && errors.length > 0) && (
				<ul className="list-reset mt-2">
					{ errors.map((error, i) => (
						<li key={ i } className="text-red mb-1">
							{ error }
						</li>
					)) }
				</ul>
			) }
		</div>
	);
};
