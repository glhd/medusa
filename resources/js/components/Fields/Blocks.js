import React, { useState } from 'react';
import useFields from "../../hooks/useFields";
import Group from "../Form/Group";

export default function Blocks(props) {
	const [block_id, setNextBlockId] = useState(0);
	const [blocks, setBlocks] = useState([]);
	
	const { field, value, medusa, onChange } = props;
	const fields = useFields(field.config.fields, medusa);
	
	// FIXME: This needs to handle its own validation
	
	const addBlock = (Field, props) => {
		const next_block = {
			Field,
			props,
			key: block_id,
		};
		
		const { component, name, initial_value } = props.field;
		
		setNextBlockId(block_id + 1);
		setBlocks([...blocks, next_block]);
		onChange([...value, { component, name, value: initial_value }]);
	};
	
	return (
		<Group { ...props }>
			<div className="border rounded p-2">
				<div className="-mx-2">
					{ fields.map(([Field, props]) => (
						<button
							key={ props.field.name }
							className="mx-2 bg-grey-lightest text-grey-dark border rounded px-4 py-2"
							onClick={ e => e.preventDefault() || addBlock(Field, props) }>
							Add { props.field.display_name }
						</button>
					)) }
				</div>
				<div className={ blocks.length > 0 ? 'mt-4 border-t' : '' }>
					{ blocks.map((block, i) => {
						const { Field, props, key } = block;
						const onBlockChange = (block_value) => {
							const next_value = [...value];
							next_value[i].value = block_value;
							onChange(next_value);
						};
						return <Field { ...props } key={ key } onChange={ onBlockChange } />;
					}) }
				</div>
			</div>
		</Group>
	);
};
