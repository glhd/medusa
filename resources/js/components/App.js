import React from 'react';
import Debugger from './Debugger';
import Fields from './Fields';
import useData from '../hooks/useData';
import useDependencies from '../hooks/useDependencies';
import useValidation from '../hooks/useValidation';

// TODO: Better dirty tracking

export default ({ config }) => {
	const [data, onChange] = useData(config.fields);
	const [dependencies, onDependencies] = useDependencies();
	const errors = useValidation(data, config.rules);
	
	return (
		<div>
			<Fields
				fields={config.fields}
				data={data}
				errors={errors}
				onChange={onChange}
				onDependencies={onDependencies}
			/>
			{ Object.keys(errors).length > 0 && (
				<div className="border border-red rounded bg-red-lightest text-red p-4">
					There are errors on this page that you must fix before saving.
				</div>
			) }
			<Debugger {...data} />
			<Debugger {...dependencies} />
		</div>
	);
};
