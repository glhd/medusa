import React from 'react';

export default (props) => {
	const { name, errors, label, initial_value, existing, value, onChange } = props;
	const id = `medusa-${ name }`;
	const dirty = existing ? existing !== value : initial_value !== value;
	
	return (
		<div className="py-4">
			<label className="block bold mb-2" htmlFor={ id }>
				{ label }
				{ dirty && <span className="ml-1 text-grey-light text-sm">(changed)</span> }
			</label>
			<input
				id={ id }
				className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
				value={ props.value }
				onChange={ e => onChange(e.target.value) }
			/>
			{ (dirty && errors.length > 0) && (
				<ul className="list-reset mt-2">
					{ errors.map((error, i) => (
						<li key={i} className="text-red mb-1">
							{ error }
						</li>
					))}
				</ul>
			) }
		</div>
	);
};
