import React from "react";
import useBlockContext from "./useBlockContext";
import ActionMenu from "./ActionMenu";
import DragHandle from "./DragHandle";

// TODO: https://ui.reach.tech/menu-button - "Unmounting the Menu after an action"

// This is the wrapper for the actual field that's going to be rendered, with
// all the Block-aware elements and logic. Because fields inside Blocks are
// treated as "value" inside the block, we have to massage the field config
// to make it think it's part of the main field context

export default function DraggableField({ index, block, provided, snapshot }) {
	const { Field, props, key } = block;
	const { innerRef, draggableProps, dragHandleProps } = provided;
	const { isDragging } = snapshot;
	
	const { onChange, onDelete, onAdd, errors, block_values } = useBlockContext();
	
	const value = block_values[index].value;
	// TODO: Calculate error
	
	const onClone = () => {
		if (confirm('Are you sure you want to clone this block?')) {
			onAdd(Field, {
				...props,
				field: {
					...props.field,
					initial_value: value,
				}
			});
		}
	};
	
	const className = `flex rounded bg-white my-2 overflow-hidden ${ isDragging ? 'border shadow-lg' : '' }`;
	
	return (
		<div className={ className } ref={ innerRef } { ...draggableProps }>
			<DragHandle {...dragHandleProps} />
			<div className="flex-1 pr-2">
				<Field
					{ ...props }
					field={ { ...props.field, id: `${ props.field.name }-${ key }` } }
					key={ key }
					value={ value }
					errors={ key in errors ? errors[key] : {} }
					onChange={ value => onChange(index, value) }
				/>
			</div>
			<div className="flex flex-col justify-center items-center px-4 ml-4 border-l border-grey-lighter">
				<ActionMenu onClone={ onClone } onDelete={ onDelete } />
			</div>
		</div>
	);
};
