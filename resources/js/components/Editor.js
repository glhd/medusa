import React, { useMemo, useState } from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import { EditorContext } from '../hooks/useEditorContext';
import useData from "../hooks/useData";
// import useValidation from "../hooks/useValidation";

export default (props) => {
	const { id, existing, content_type } = props;
	const { fields, rules, messages } = content_type;
	// const { fields, rules, content_type } = config;
	const initial_data = initialData(fields, existing);
	const { data, changed, touched, setData, setTouched } = useData(fields, initial_data);
	const [dependencies, setDependencies] = useState({});
	// const errors = useValidation(data, rules, touched, server_errors);
	const errors = {}; // FIXME
	
	const context = { data, changed, touched, errors, dependencies, setDependencies, setData, setTouched };
	const mode = id
		? 'Update'
		: 'Create New';
	
	return (
		<EditorContext.Provider value={context}>
			<h1 className="text-lg font-semibold text-grey mb-6">
				{ mode } { content_type.title }
			</h1>
			<Fields fields={fields} />
			<Debugger {...data} />
			{/*<Debugger existing={existing} initial={initial_data} data={data} changed={changed} touched={touched} {...props} />*/}
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
}
