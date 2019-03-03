import React, { useState } from 'react';
import { navigate } from '@reach/router';
import { useMutation } from 'react-apollo-hooks';
import Editor from './Editor';
import useAppContext from "../hooks/useAppContext";
import { ALL_CONTENT, CREATE_CONTENT } from '../queries';

export default ({ name }) => {
	const { content_types, basepath, addNotification } = useAppContext();
	const [saving, setSaving] = useState(false);
	const mutation = useMutation(CREATE_CONTENT);
	
	if (!(name in content_types)) {
		return <div>Content type not found!</div>; // FIXME
	}
	
	const content_type = content_types[name];
	
	const onSave = (data) => {
		setSaving(true);
		mutation({
			variables: {
				content_type_id: content_type.id,
				data: JSON.stringify(data)
			},
			update: (cache, { data }) => {
				const { id } = data.createContent;
				addNotification(`Created new ${content_type.title}!`, { successful: true });
				navigate(`${basepath}/content/${id}`);
			},
			refetchQueries: [{
				query: ALL_CONTENT,
				variables: { page: 1 },
			}],
		}).catch(err => {
			setSaving(false);
			addNotification(`There was an error saving this ${content_type.title}.`, { dangerous: true, timeout: 7500 });
		});
	};
	
	return (
		<>
			<h1 className="text-lg font-semibold text-grey-dark mb-6">
				Create New { content_type.title }
			</h1>
			<Editor
				content_type={ content_type }
				onSave={ onSave }
				saving={saving}
			/>
		</>
	);
};
