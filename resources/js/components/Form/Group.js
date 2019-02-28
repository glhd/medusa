import React from 'react';

export default function Group(props) {
	const { field, changed, touched, errors, children } = props;
	const { id, label } = field;
	
	return (
		<div className="py-4">
			<label className="block bold mb-2" htmlFor={ id }>
				{ label }
				{ changed && <span className="ml-1 text-grey-light text-sm">(changed)</span> }
			</label>
			{ children }
			{ (touched && errors.length > 0) && (
				<ul className="list-reset mt-2">
					{ errors.map((error) => (
						<li key={ error } className="text-red mb-1">
							{ error }
						</li>
					)) }
				</ul>
			) }
		</div>
	);
};
