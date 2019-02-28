import React, { useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import useFields from "../../hooks/useFields";
import Group from "../Form/Group";

export default function Blocks(props) {
	const [blocks, setBlocks] = useState([]);
	
	const { field, value, medusa, onChange } = props;
	const fields = useFields(field.config.fields, medusa);
	
	// FIXME: This needs to handle its own validation
	
	function uuid(a) {
		return a
			? (a ^ Math.random() * 16 >> a / 4).toString(16)
			: ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, uuid);
	}
	
	const addBlock = (Field, props) => {
		const next_block = {
			Field,
			props,
			key: uuid(),
		};
		
		const { component, name, initial_value } = props.field;
		
		setBlocks([...blocks, next_block]);
		onChange([...value, { component, name, value: initial_value }]);
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
				<DragDropContext onDragEnd={ onDragEnd }>
					<Droppable droppableId="droppable">
						{ (provided, snapshot) => (
							<div ref={ provided.innerRef } className={ snapshot.isDraggingOver ? 'bg-blue-lightest' : '' }>
								{ blocks.map((block, index) => (
									<Draggable key={ block.key } draggableId={ block.key } index={ index }>
										{ (provided, snapshot) => {
											const { Field, props, key } = block;
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
											
											return (
												<div className={`flex rounded-lg bg-white my-2 overflow-hidden ${isDragging ? 'border shadow-lg' : ''}`} ref={ innerRef } { ...draggableProps } style={ draggableProps.style }>
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
														<Field { ...props } key={ key } onChange={ onBlockChange } />
													</div>
													<button onClick={onBlockDelete} title="Delete" className="flex items-center px-4 ml-4 border-l border-grey-lighter text-grey-light hover:text-red">
														<TrashCan />
													</button>
												</div>
											);
										} }
									</Draggable>
								)) }
							</div>
						) }
					</Droppable>
				</DragDropContext>
			</div>
		</Group>
	);
};

function reorder(list, from, to) {
	const result = Array.from(list);
	const [removed] = result.splice(from, 1);
	if (null !== to) {
		result.splice(to, 0, removed);
	}
	return result;
}

function TrashCan({ width = 16, height = 16 }) {
	return (
		<svg width={width} height={height} style={{ fill: 'currentColor' }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
			<path d="M381.6 80l-34-56.7C338.9 8.8 323.3 0 306.4 0H205.6c-16.9 0-32.5 8.8-41.2 23.3l-34 56.7H40c-13.3 0-24 10.7-24 24v12c0 6.6 5.4 12 12 12h16.4L76 468.4c2.3 24.7 23 43.6 47.8 43.6h264.5c24.8 0 45.5-18.9 47.8-43.6L467.6 128H484c6.6 0 12-5.4 12-12v-12c0-13.3-10.7-24-24-24h-90.4zm-176-32h100.8l19.2 32H186.4l19.2-32zm182.6 416H123.8L92.6 128h326.7l-31.1 336z" />
		</svg>
	);
}
