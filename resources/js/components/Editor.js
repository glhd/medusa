import React, { useMemo, useState } from 'react';
import Fields from './Fields';
import { EditorContext } from '../hooks/useEditorContext';
import useData from "../hooks/useData";
import useValidation from "../hooks/useValidation";
import Debugger from './Debugger';

export default function Editor(props) {
	const { id, existing, content_type, onSave, saving } = props;
	const { fields } = content_type;
	const initial_data = initialData(fields, existing);
	const { data, changed, touched, setData, setTouched } = useData(fields, initial_data);
	const [dependencies, setDependencies] = useState({});
	const errors = useValidation(data, fields);
	
	const context = { data, changed, touched, errors, dependencies, setDependencies, setData, setTouched };
	
	const disable_save = (saving || Object.keys(errors).length > 0);
	const save_label = id
		? `Save Changes to ${ content_type.title }`
		: `Save New ${ content_type.title }`;
	
	const DEBUGGER_ENABLED = false;
	
	return (
		<EditorContext.Provider value={ context }>
			<div className="bg-white border rounded-lg p-6">
				<Fields fields={ fields } />
				{ (true === DEBUGGER_ENABLED) && <Debugger {...data} /> }
				<div className="pt-6">
					<button
						disabled={ disable_save }
						onClick={ (e) => onSave(data) }
						className={ `${ disable_save ? 'bg-grey-lighter border-grey-light text-grey-darker cursor-not-allowed'
							: 'bg-blue border-blue-dark text-white hover:bg-blue-dark hover:shadow cursor-pointer' } px-6 py-3 rounded` }
					>
						{ saving ? 'Saving…' : save_label }
					</button>
				</div>
			</div>
		</EditorContext.Provider>
	);
};

const initialData = (fields, existing) => {
	return useMemo(() => {
		const data = {};
		
		Object.values(fields).forEach(field => {
			data[field.name] = JSON.parse(field.initial_value);
		});
		
		return {
			...data,
			...existing,
		};
	}, [fields, existing]);
};
