import React from 'react';
// import Group from '../Form/Group';
import Debugger from '../Debugger';
// import useReferencedField from '../../hooks/useReferencedField';

export default function AdministrativeArea(props) {
	return <Debugger {...props} />;
	// const { id, config, all_data, field } = props; // FIXME Replace all_data with medusacontext
	// const { value, onChange } = useFieldContext(field);
	//
	// const country = useReferencedField(all_data, config, 'country_field', 'country'); // TODO: This maybe could be passed as props?
	//
	// return (
	// 	<Group field={field}>
	// 		<input
	// 			className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
	// 			id={ id }
	// 			value={ value.code }
	// 			onChange={ e => onChange({ code: e.target.value, name: e.target.value }) }
	// 		/>
	// 		<Debugger data={all_data} />
	// 	</Group>
	// );
};
