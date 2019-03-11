import React, { useMemo, useRef, useEffect } from 'react';
import Group from '../Group';
import showdown from 'showdown';
import autosize from 'autosize';

export default function Markdown(props) {
	const { field, value, onChange } = props;
	const { id } = field;
	const converter = useMemo(() => {
		const class_map = {
			h1: 'mb-3',
			h2: 'mb-3',
			h3: 'mb-3',
			p: 'mb-4',
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
		<div className="flex -mx-3">
			<div className="flex-1 px-3">
				<Group { ...props }>
					<textarea
						ref={ textarea_ref }
						className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
						id={ id }
						value={ value }
						onChange={ e => onChange(e.target.value) }
					/>
				</Group>
			</div>
			<div className="flex-1 px-3">
				<div className="py-4 flex flex-col min-h-full">
					<div className="bold mb-2">
						Preview
					</div>
					<div className="flex-1 shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline">
						<div dangerouslySetInnerHTML={ { __html: html } } />
					</div>
				</div>
			</div>
		</div>
	);
};
