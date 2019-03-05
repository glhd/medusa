import React from "react";
import DraggableFields from "./DraggableFields";

// It's important to set the innerRef as high up in the DOM of the
// drop zone as possible for performance reasons. We then pass the
// rest of the render down to DraggableFields so that we can memoize
// the render to prevent re-rendering the entire sub-tree whenever
// a drag event happens. This is also a convenient place to render
// the placeholder, which ensures there's enough space to drop our
// fields into the drop zone

export default function InnerDropZone({ provided, snapshot }) {
	const { innerRef, placeholder } = provided;
	const { isDraggingOver } = snapshot;
	
	return (
		<div ref={ innerRef } className={ isDraggingOver ? 'bg-blue-lightest' : '' }>
			<DraggableFields />
			{ placeholder }
		</div>
	);
};
