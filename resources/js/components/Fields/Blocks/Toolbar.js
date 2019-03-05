import React from "react";
import useBlockContext from "./useBlockContext";

export default function Toolbar({ fields }) {
	const { onAdd } = useBlockContext();
	
	return (
		<div className="-mx-2">
			{ fields.map(({ Field, props }) => (
				<button
					key={ props.field.name }
					className="mx-2 bg-grey-lightest text-grey-dark border rounded px-4 py-2"
					onClick={ e => e.preventDefault() || onAdd(Field, props) }>
					Add { props.field.display_name }
				</button>
			)) }
		</div>
	);
};
