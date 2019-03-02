import React, { useMemo, useState } from 'react';
import Fields from './Fields';
import { EditorContext } from '../hooks/useEditorContext';
import useData from "../hooks/useData";
import useValidation from "../hooks/useValidation";
import Debugger from "./Debugger";

export default (props) => {
	const { id, existing, content_type, onSave, saving } = props;
	const { fields, rules, messages } = content_type;
	const initial_data = initialData(fields, existing);
	const { data, changed, touched, setData, setTouched } = useData(fields, initial_data);
	const [dependencies, setDependencies] = useState({});
	const errors = useValidation(data, rules, touched);
	
	const context = { data, changed, touched, errors, dependencies, setDependencies, setData, setTouched };
	
	const disable_save = !!saving;
	const save_label = id
		? `Save Changes to ${ content_type.title }`
		: `Save New ${ content_type.title }`;
	
	return (
		<EditorContext.Provider value={ context }>
			<Debugger errors={errors} rules={rules} />
			<Fields fields={ fields } />
			<button
				disabled={ disable_save }
				className="bg-blue border-blue-dark px-6 py-3 text-white rounded hover:bg-blue-dark hover:shadow"
				onClick={ () => onSave(data) }
			>
				{ saving ? 'Saving…' : save_label }
			</button>
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
