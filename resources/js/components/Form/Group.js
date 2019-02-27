import React from 'react';
import useFieldContext from '../../hooks/useFieldContext';

export default function Group({ field, children }) {
	const { id, label } = field;
	const { changed, touched, errors } = useFieldContext(field);
	
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
