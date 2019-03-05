import React from "react";
import { DragDropContext, Droppable } from "react-beautiful-dnd";
import InnerDropZone from "./InnerDropZone";

// This sets up the drag and drop context and passes the current
// droppable context and provided props down to the inner drop zone

export default function BlockFields({ onDragEnd }) {
	return (
		<DragDropContext onDragEnd={ onDragEnd }>
			<Droppable droppableId="block_fields">
				{ (provided, snapshot) => <InnerDropZone provided={ provided } snapshot={ snapshot } /> }
			</Droppable>
		</DragDropContext>
	);
};
