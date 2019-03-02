import React, { useContext, useMemo, useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import { Menu, MenuList, MenuButton, MenuItem } from '@reach/menu-button';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronCircleDown } from '@fortawesome/free-solid-svg-icons';
import { faTrashAlt, faClone, faPlusSquare  } from '@fortawesome/free-regular-svg-icons';
import useFields from "../../hooks/useFields";
import Group from "../Form/Group";

const BlocksContext = React.createContext({});
const useBlockContext = () => useContext(BlocksContext);

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
	
	// FIXME: This needs to handle its own validation
	
	const context = {
		block_values,
		blocks,
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
	
	return (
		<BlocksContext.Provider value={ context }>
			<Group { ...props }>
				<div className="border rounded p-2">
					<FieldButtons fields={ fields } />
					<BlockFields
						onDragEnd={ ({ source, destination }) => {
							if (destination && destination.index !== source.index) {
								setBlocks(reorder(blocks, source.index, destination.index));
								onChange(reorder(block_values, source.index, destination.index));
							}
						} }
					/>
				</div>
			</Group>
		</BlocksContext.Provider>
	);
};

const FieldButtons = ({ fields }) => {
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

const BlockFields = ({ onDragEnd }) => {
	return (
		<DragDropContext onDragEnd={ onDragEnd }>
			<Droppable droppableId="block_fields">
				{ (provided, snapshot) => <BlockList provided={ provided } snapshot={ snapshot } /> }
			</Droppable>
		</DragDropContext>
	);
};

const BlockList = ({ provided, snapshot }) => {
	const { innerRef, placeholder } = provided;
	const { isDraggingOver } = snapshot;
	
	return (
		<div ref={ innerRef } className={ isDraggingOver ? 'bg-blue-lightest' : '' }>
			<InnerBlockList />
			{ placeholder }
		</div>
	);
};

const InnerBlockList = () => {
	const { blocks, block_values } = useBlockContext();
	return useMemo(() => blocks.map((block, index) => (
		<Draggable key={ block.key } draggableId={ block.key } index={ index }>
			{ (provided, snapshot) => <BlockField
				block={ block }
				index={ index }
				provided={ provided }
				snapshot={ snapshot }
				value={ block_values[index].value }
			/> }
		</Draggable>
	)), [blocks, block_values]);
};

// TODO: https://ui.reach.tech/menu-button - "Unmounting the Menu after an action"

const BlockField = ({ index, block, value, provided, snapshot }) => {
	const { Field, props, key } = block;
	const { onChange, onDelete, onAdd } = useBlockContext();
	const { innerRef, draggableProps, dragHandleProps } = provided;
	const { isDragging } = snapshot;
	
	const onBlockClone = () => {
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
			<div
				style={ {
					width: '16px',
					marginRight: '8px',
					background: `linear-gradient(90deg, #fff 2px, transparent 1%) center,
						linear-gradient(#fff 2px, transparent 1%) center,
						#eee`,
					backgroundSize: '4px 4px',
				} }
				{ ...dragHandleProps }
			/>
			<div className="flex-1 pr-2">
				<Field
					{ ...props }
					field={ { ...props.field, id: `${ props.field.id }-${ key }` } }
					key={ key }
					value={ value }
					onChange={ value => onChange(index, value) }
				/>
			</div>
			<div className="flex flex-col justify-center items-center px-4 ml-4 border-l border-grey-lighter">
				<Menu>
					<MenuButton className="text-grey-light hover:text-grey py-2">
						<FontAwesomeIcon icon={faChevronCircleDown} size="lg" />
					</MenuButton>
					<MenuList className="rounded border-grey-light shadow px-0 py-1">
						<MenuItem className="px-4 py-2" onSelect={() => onBlockClone()}>
							<FontAwesomeIcon className="mr-2" icon={faClone} fixedWidth={true} />
							Duplicate
						</MenuItem>
						<MenuItem className="px-4 py-2" onSelect={() => onDelete()}>
							<FontAwesomeIcon className="mr-2" icon={faTrashAlt} fixedWidth={true} />
							Delete
						</MenuItem>
						<MenuItem className="px-4 py-2" onSelect={() => alert('todo')}>
							<FontAwesomeIcon className="mr-2" icon={faPlusSquare} fixedWidth={true} />
							Add Below…
						</MenuItem>
						<MenuItem className="px-4 py-2" onSelect={() => alert('todo')}>
							<FontAwesomeIcon className="mr-2" icon={faPlusSquare} fixedWidth={true} />
							Add Above…
						</MenuItem>
					</MenuList>
				</Menu>
			</div>
		</div>
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
