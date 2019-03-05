import React from 'react';
import FroalaEditor from 'react-froala-wysiwyg';
import Group from '../Group';

import 'froala-editor/js/froala_editor.pkgd.min.js';
import 'codemirror/lib/codemirror';
import 'codemirror/mode/xml/xml';

export default function RichText(props) {
	const { field, value, onChange } = props;
	const { id } = field;
	
	const editor_config = {
		key: field.config.key, // TODO
		linkAutoPrefix: 'https://',
	};
	
	return (
		<Group { ...props }>
			<FroalaEditor
				id={id}
				tag="textarea"
				model={ value }
				config={ editor_config }
				onModelChange={ onChange }
			/>
		</Group>
	);
};
