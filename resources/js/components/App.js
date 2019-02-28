import React, { useMemo } from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import useData from '../hooks/useData';
import useDependencies from '../hooks/useDependencies';
import useValidation from '../hooks/useValidation';
import { MedusaContext } from '../hooks/useMedusaContext';

export default ({ config, existing, old, server_errors }) => {
	const { fields, rules, content_type } = config;
	const initial_data = initialData(fields, existing, old);
	const [data, changed, touched, onChange] = useData(fields, initial_data);
	const [dependencies, onDependencies] = useDependencies();
	const errors = useValidation(data, rules, touched, server_errors);
	
	const medusa = { data, changed, touched, errors, onChange, onDependencies };
	
	const creating = 0 === Object.keys(existing).length;
	
	return (
		<MedusaContext.Provider value={medusa}>
			<h1 className="text-lg text-grey-dark">
				{ creating ? 'Create New' : 'Update' } { content_type.title }
			</h1>
			
			<Fields fields={fields} />
			
			{/*{ Object.keys(errors).length > 0 && (*/}
				{/*<div className="border border-red rounded bg-red-lightest text-red p-4">*/}
					{/*There are errors on this page that you must fix before saving.*/}
				{/*</div>*/}
			{/*) }*/}
			
			{/*<Debugger changed={changed} touched={touched} />*/}
			{/*<Debugger {...data} />*/}
			{/*<Debugger {...server_errors} />*/}
			
			<input name="content_type" type="hidden" value={ content_type.id } />
			<input name="data" type="hidden" value={JSON.stringify(data)} />
			
			<div className="py-4">
				<button type="Submit" className="mx-2 bg-blue border border-blue-darker text-white border rounded px-6 py-3">
					{ creating ? 'Create' : 'Save Changes to' } { content_type.title }
				</button>
			</div>
		</MedusaContext.Provider>
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
