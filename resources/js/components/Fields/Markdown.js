import React, { useMemo, useRef, useEffect } from 'react';
import Group from '../Group';
import showdown from 'showdown';
import autosize from 'autosize';

export default function Markdown(props) {
	const { field, value, onChange } = props;
	const { id } = field;
	const converter = useMemo(() => {
		const class_map = {
			h1: 'my-2',
			p: 'my-2',
		};
		
		const bindings = Object.entries(class_map)
			.map(([tag, class_names]) => ({
				type: 'output',
				regex: new RegExp(`<${tag}(.*)>`, 'g'),
				replace: `<${tag} class="${class_names}" $1>`
			}));
		
		return new showdown.Converter({ extensions: [...bindings] });
	}, []);
	const html = converter.makeHtml(value);
	
	const textarea_ref = useRef();
	useEffect(() => {
		autosize(textarea_ref.current);
		return () => autosize.destroy(textarea_ref.current);
	}, []);
	
	return (
		<Group { ...props }>
			<div className="flex -mx-2">
				<div className="flex-1 mx-2">
					<textarea
						ref={ textarea_ref }
						className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
						id={ id }
						value={ value }
						onChange={ e => onChange(e.target.value) }
					/>
				</div>
				<div className="flex-1 mx-2">
					<div dangerouslySetInnerHTML={ { __html: html } } />
				</div>
			</div>
		</Group>
	);
};
