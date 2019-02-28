import React, { useState, useMemo, useContext } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import useFields from "../../hooks/useFields";
import Group from "../Form/Group";

const BlocksContext = React.createContext({});
const useBlockContext = () => useContext(BlocksContext);

export default function Blocks(props) {
	const [blocks, setBlocks] = useState([]);
	
	const { field, value, onChange } = props;
	const fields = useFields(field.config.fields);
	
	// FIXME: This needs to handle its own validation
	
	const addBlock = (Field, props) => {
		const next_block = {
			Field,
			props,
			key: uuid(),
		};
		
		const next_value = {
			id: next_block.key,
			name: props.field.name,
			config: props.field.config,
			value: props.field.initial_value,
		};
		
		setBlocks([...blocks, next_block]);
		onChange([...value, next_value]);
	};
	
	const onDragEnd = ({ source, destination }) => {
		if (!destination) {
			return;
		}
		setBlocks(reorder(blocks, source.index, destination.index));
		onChange(reorder(value, source.index, destination.index));
	};
	
	const onDelete = (index) => {
		setBlocks(reorder(blocks, index, null));
		onChange(reorder(value, index, null));
	};
	
	const context = { value, blocks, addBlock, onChange, onDelete };
	
	return (
		<BlocksContext.Provider value={context}>
			<Group { ...props }>
				<div className="border rounded p-2">
					<FieldButtons fields={ fields } />
					<BlockFields onDragEnd={onDragEnd} />
				</div>
			</Group>
		</BlocksContext.Provider>
	);
};

const FieldButtons = ({ fields }) => {
	const { addBlock } = useBlockContext();
	return (
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
	);
};

const BlockFields = ({ onDragEnd }) => {
	return (
		<DragDropContext onDragEnd={ onDragEnd }>
			<Droppable droppableId="fields">
				{ (provided, snapshot) => <BlockList provided={provided} snapshot={snapshot} /> }
			</Droppable>
		</DragDropContext>
	);
};

const BlockList = ({ provided, snapshot }) => {
	const { blocks } = useBlockContext();
	return (
		<div ref={ provided.innerRef } className={ snapshot.isDraggingOver ? 'bg-blue-lightest' : '' }>
			{ blocks.map((block, index) => (
				<Draggable key={ block.key } draggableId={ block.key } index={ index }>
					{ (provided, snapshot) => <BlockField block={block} index={index} provided={provided} snapshot={snapshot} /> }
				</Draggable>
			)) }
			{ provided.placeholder }
		</div>
	);
};

const BlockField = ({ index, block, provided, snapshot }) => {
	const { Field, props, key } = block;
	const { value, onChange, onDelete, addBlock } = useBlockContext();
	const { innerRef, draggableProps, dragHandleProps } = provided;
	const { isDragging } = snapshot;
	
	const onBlockChange = (block_value) => {
		const next_value = [...value];
		next_value[index].value = block_value;
		onChange(next_value);
	};
	
	const onBlockDelete = (e) => {
		e.preventDefault();
		
		if (confirm('Are you sure you want to delete this block?')) {
			onDelete(index);
		}
	};
	
	const onBlockClone = (e) => {
		e.preventDefault();
		
		if (confirm('Are you sure you want to clone this block?')) {
			addBlock(Field, {
				...props,
				field: {
					...props.field,
					initial_value: value[index].value,
				}
			});
		}
	};
	
	const className = `flex rounded bg-white my-2 overflow-hidden ${ isDragging ? 'border shadow-lg' : '' }`
	
	return (
		<div className={ className } ref={ innerRef } { ...draggableProps } style={ draggableProps.style }>
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
				<Field { ...props } value={ value[index].value } key={ key } onChange={ onBlockChange } />
			</div>
			<div className="flex flex-col justify-center items-center px-4 ml-4 border-l border-grey-lighter">
				<button
					onClick={ onBlockDelete }
					title="Delete"
					className="flex items-center text-grey-light hover:text-red py-2">
					<TrashIcon />
				</button>
				<button
					onClick={ onBlockClone }
					title="Clone"
					className="flex items-center text-grey-light hover:text-green py-2">
					<CloneIcon />
				</button>
			</div>
		</div>
	);
};

function uuid(a) {
	return a
		? (a ^ Math.random() * 16 >> a / 4).toString(16)
		: ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, uuid);
}

function reorder(list, from, to) {
	const result = Array.from(list);
	const [removed] = result.splice(from, 1);
	if (null !== to) {
		result.splice(to, 0, removed);
	}
	return result;
}

// Font Awesome License - https://fontawesome.com/license

function TrashIcon({ width = 16, height = 16 }) {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" width={ width } height={ height } style={ { fill: 'currentColor' } } viewBox="0 0 512 512">
			<path d="M381.6 80l-34-56.7C338.9 8.8 323.3 0 306.4 0H205.6c-16.9 0-32.5 8.8-41.2 23.3l-34 56.7H40c-13.3 0-24 10.7-24 24v12c0 6.6 5.4 12 12 12h16.4L76 468.4c2.3 24.7 23 43.6 47.8 43.6h264.5c24.8 0 45.5-18.9 47.8-43.6L467.6 128H484c6.6 0 12-5.4 12-12v-12c0-13.3-10.7-24-24-24h-90.4zm-176-32h100.8l19.2 32H186.4l19.2-32zm182.6 416H123.8L92.6 128h326.7l-31.1 336z" />
		</svg>
	);
}

function CloneIcon({ width = 16, height = 16 }) {
	return (
		<svg xmlns="http://www.w3.org/2000/svg" width={ width } height={ height } style={ { fill: 'currentColor' } } viewBox="0 0 512 512">
			<path d="M464 0H144c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v320c0 26.51 21.49 48 48 48h320c26.51 0 48-21.49 48-48v-48h48c26.51 0 48-21.49 48-48V48c0-26.51-21.49-48-48-48zM362 464H54a6 6 0 0 1-6-6V150a6 6 0 0 1 6-6h42v224c0 26.51 21.49 48 48 48h224v42a6 6 0 0 1-6 6zm96-96H150a6 6 0 0 1-6-6V54a6 6 0 0 1 6-6h308a6 6 0 0 1 6 6v308a6 6 0 0 1-6 6z" />
		</svg>
	);
}
