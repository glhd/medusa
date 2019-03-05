import React, { useMemo } from "react";
import { Draggable } from "react-beautiful-dnd";
import useBlockContext from "./useBlockContext";
import DraggableField from "./DraggableField";

// This memoizes our array of Draggables so that they don't re-render
// every time the parent tree changes (which happens each time the
// drop zone updates). Instead, we only re-render if blocks or
// block_values change.

export default function DraggableFields() {
	const { blocks, block_values } = useBlockContext();
	
	return useMemo(() => blocks.map((block, index) => (
		<Draggable key={ block.key } draggableId={ block.key } index={ index }>
			{ (provided, snapshot) => <DraggableField
				block={ block }
				index={ index }
				provided={ provided }
				snapshot={ snapshot }
			/> }
		</Draggable>
	)), [blocks, block_values]);
}
