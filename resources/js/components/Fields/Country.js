import React from 'react';
//import Group from '../Form/Group';
// import useFieldContext from "../../hooks/useFieldContext";
import Debugger from "../Debugger";

export default function Country(props) {
	return <Debugger {...props} />;
	// const { id, field } = props;
	// const { value, onChange } = useFieldContext(field);
	// return (
	// 	<Group field={field}>
	// 		<input
	// 			className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
	// 			id={ id }
	// 			value={ value.code }
	// 			onChange={ e => onChange({ code: e.target.value, name: e.target.value }) }
	// 		/>
	// 	</Group>
	// );
};
