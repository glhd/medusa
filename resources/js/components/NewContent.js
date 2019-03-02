import React from 'react';
import Editor from './Editor';
import Debugger from './Debugger';
import useAppContext from "../hooks/useAppContext";

export default ({ name }) => {
	const { content_types } = useAppContext();
	
	if (!(name in content_types)) {
		return <div>Content type not found!</div>; // FIXME
	}
	
	const content_type = content_types[name];
	
	return (
		<div>
			<Editor content_type={ content_type } />
		</div>
	);
};
