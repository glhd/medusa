import React, { useState } from 'react';
import useFields from "../../hooks/useFields";
import Group from "../Group";
import { BlocksContext } from "./Blocks/useBlockContext";
import Toolbar from "./Blocks/Toolbar";
import BlockFields from "./Blocks/BlockFields";

export default function Blocks(props) {
	const { field, value: block_values, onChange } = props;
	const fields = useFields(field.config.fields);
	
	const [blocks, setBlocks] = useState(() => block_values.map(value_config => {
		const { id, component, name } = value_config;
		const field = fields.find(({ props }) => {
			return props.field.component === component
				&& props.field.name === name;
		});
		return { ...field, key: id };
	}));
	
	// const validation_data = useMemo(() => {
	// 	const data = {};
	// 	block_values.forEach(item => {
	// 		data[item.id] = item.value;
	// 	});
	// 	return data;
	// }, [block_values]);
	//
	// const validation_fields = useMemo(() => {
	// 	const validation_fields = {};
	// 	blocks.forEach(block => {
	// 		const block_rules = {};
	//
	// 		Object.entries(block.props.field.rules).forEach(([key, value]) => {
	// 			block_rules[key.replace(block.props.field.name, block.key)] = value;
	// 		});
	//
	// 		validation_fields[block.key] = {
	// 			...block.props.field,
	// 			rules: block_rules,
	// 		};
	// 	});
	// 	return validation_fields;
	// }, [blocks]);
	//
	// const errors = useValidation(validation_data, validation_fields);
	const errors = {}; // FIXME
	
	const context = {
		block_values,
		blocks,
		errors,
		onAdd: (Field, props) => {
			const key = uuid();
			const addition = { key, Field, props };
			const { name, component, config, initial_value } = props.field;
			const value = { id: key, value: initial_value, name, component, config };
			
			setBlocks([...blocks, addition]);
			onChange([...block_values, value]);
		},
		onChange: (index, value) => {
			const changed = [...block_values];
			changed[index].value = value;
			onChange(changed);
		},
		onDelete: (index) => {
			if (confirm('Are you sure you want to delete this?')) {
				setBlocks(reorder(blocks, index));
				onChange(reorder(block_values, index));
			}
		},
	};
	
	const onDragEnd = ({ source, destination }) => {
		if (destination && destination.index !== source.index) {
			setBlocks(reorder(blocks, source.index, destination.index));
			onChange(reorder(block_values, source.index, destination.index));
		}
	};
	
	return (
		<BlocksContext.Provider value={ context }>
			<Group { ...props }>
				<div className="border rounded p-2">
					<Toolbar fields={ fields } />
					<BlockFields onDragEnd={ onDragEnd } />
				</div>
			</Group>
		</BlocksContext.Provider>
	);
};

const uuid = a => a
	? (a ^ Math.random() * 16 >> a / 4).toString(16)
	: ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, uuid);

const reorder = (list, from, to = null) => {
	const result = Array.from(list);
	const [removed] = result.splice(from, 1);
	if (null !== to) {
		result.splice(to, 0, removed);
	}
	return result;
};
