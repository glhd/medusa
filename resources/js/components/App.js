import React, { useMemo } from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import useData from '../hooks/useData';
import useDependencies from '../hooks/useDependencies';
import useValidation from '../hooks/useValidation';

export default ({ config, existing, old, server_errors }) => {
	const { fields, rules, content_type } = config;
	const initial_data = initialData(fields, existing, old);
	const [data, changed, touched, onChange] = useData(fields, initial_data);
	const [dependencies, onDependencies] = useDependencies();
	const errors = useValidation(data, rules, touched, server_errors);
	
	const creating = 0 === Object.keys(existing).length;
	
	return (
		<>
			<h1>
				{ creating ? 'Create New' : 'Update' } { content_type.title }
			</h1>
			
			<Fields fields={fields} medusa={{ data, changed, touched, errors, onChange, onDependencies }} />
			
			{/*{ Object.keys(errors).length > 0 && (*/}
				{/*<div className="border border-red rounded bg-red-lightest text-red p-4">*/}
					{/*There are errors on this page that you must fix before saving.*/}
				{/*</div>*/}
			{/*) }*/}
			
			{/*<Debugger changed={changed} touched={touched} />*/}
			<Debugger {...data} />
			{/*<Debugger {...server_errors} />*/}
			
			<input name="content_type" type="hidden" value={ content_type.id } />
			<input name="data" type="hidden" value={JSON.stringify(data)} />
			
			<div className="py-4">
				<button type="Submit" className="btn">
					{ creating ? 'Create' : 'Save Changes to' } { content_type.title }
				</button>
			</div>
		</>
	);
};

function initialData(fields, existing, old) {
	return useMemo(() => {
		const data = {};
		
		Object.values(fields).forEach(field => {
			data[field.name] = field.initial_value;
		});
		
		return {
			...data,
			...existing,
			...old,
		};
	}, [fields, existing, old]);
}
