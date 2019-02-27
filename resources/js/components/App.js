import React, { useEffect } from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import useData from '../hooks/useData';
import useDependencies from '../hooks/useDependencies';
import useValidation from '../hooks/useValidation';

// TODO: Better dirty tracking

export default ({ config, existing, old, server_errors }) => {
	const [data, onChange] = useData(config.fields, old, existing);
	const [dependencies, onDependencies] = useDependencies();
	const errors = useValidation(data, config.rules, server_errors);
	
	const creating = 0 === Object.keys(existing).length;
	
	// TODO: track dirty state and show server errors until the user changes something
	
	return (
		<div>
			<h1>
				{ creating ? 'Create New' : 'Update' } { config.content_type.title }
			</h1>
			
			<Fields
				fields={config.fields}
				existing={existing}
				data={data}
				errors={errors}
				onChange={onChange}
				onDependencies={onDependencies}
			/>
			
			{/*{ Object.keys(errors).length > 0 && (*/}
				{/*<div className="border border-red rounded bg-red-lightest text-red p-4">*/}
					{/*There are errors on this page that you must fix before saving.*/}
				{/*</div>*/}
			{/*) }*/}
			
			{/*<Debugger existing={existing} data={data} />*/}
			{/*<Debugger {...data} />*/}
			{/*<Debugger {...dependencies} />*/}
			
			<input name="content_type" type="hidden" value={ config.content_type.id } />
			<input name="data" type="hidden" value={JSON.stringify(data)} />
			
			<div className="py-4">
				<button type="Submit" className="btn">
					{ creating ? 'Create' : 'Save Changes to' } { config.content_type.title }
				</button>
			</div>
		</div>
	);
};
