import React, { useEffect, useMemo } from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import useData from '../hooks/useData';
import useDependencies from '../hooks/useDependencies';
import useValidation from '../hooks/useValidation';
import { MedusaContext } from '../hooks/useMedusaContext';

export default ({ config, existing, old, server_errors }) => {
	const initial_data = initialData(config.fields, existing, old);
	const [data, changed, touched, onChange] = useData(config.fields, initial_data);
	const [dependencies, onDependencies] = useDependencies();
	const errors = useValidation(data, config.rules, touched, server_errors);
	
	const creating = 0 === Object.keys(existing).length;
	
	const context = { data, changed, touched, errors, onChange, onDependencies };
	
	return (
		<MedusaContext.Provider value={context}>
			<h1>
				{ creating ? 'Create New' : 'Update' } { config.content_type.title }
			</h1>
			
			<Fields
				fields={config.fields}
				existing={existing}
				data={data}
			/>
			
			{/*{ Object.keys(errors).length > 0 && (*/}
				{/*<div className="border border-red rounded bg-red-lightest text-red p-4">*/}
					{/*There are errors on this page that you must fix before saving.*/}
				{/*</div>*/}
			{/*) }*/}
			
			{/*<Debugger changed={changed} touched={touched} />*/}
			{/*<Debugger {...data} />*/}
			{/*<Debugger {...server_errors} />*/}
			
			<input name="content_type" type="hidden" value={ config.content_type.id } />
			<input name="data" type="hidden" value={JSON.stringify(data)} />
			
			<div className="py-4">
				<button type="Submit" className="btn">
					{ creating ? 'Create' : 'Save Changes to' } { config.content_type.title }
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
