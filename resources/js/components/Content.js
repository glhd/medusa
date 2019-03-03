import React, { useState } from 'react';
import { useMutation, useQuery } from 'react-apollo-hooks';
import Editor from './Editor';
import moment from "moment";
import useAppContext from '../hooks/useAppContext';
import Loading from './Loading';
import { GET_CONTENT, UPDATE_CONTENT } from "../queries";

export default ({ id }) => {
	const { data, error, loading } = useQuery(GET_CONTENT, { variables: { id } });
	const { addNotification } = useAppContext();
	
	const [saving, setSaving] = useState(false);
	const [last_saved, setLastSaved] = useState(null); // FIXME: This won't update unless we re-render
	
	const mutation = useMutation(UPDATE_CONTENT);
	
	if (loading || error) {
		return <Loading />;
	}
	
	const content = {
		...data.getContent,
		data: JSON.parse(data.getContent.data),
	};
	
	const { content_type } = content;
	
	const onSave = (data) => {
		setSaving(true);
		mutation({
			variables: {
				id: content.id,
				data: JSON.stringify(data)
			},
			update: () => {
				setSaving(false);
				setLastSaved(new Date());
				addNotification(`Your changes to this ${ content.content_type.title } have been saved!`, { successful: true });
			}
		}).catch(err => {
			setSaving(false);
			addNotification(`Therre was an error saving this ${ content.content_type.title }.`, { dangerous: true, timeout: 7500 });
		});
	};
	
	return (
		<>
			<h1 className="text-lg font-semibold text-grey-dark mb-6 flex justify-between items-baseline">
				<span>
					Update { content.content_type.title }
				</span>
				{ last_saved && (
					<span className="text-grey font-normal text-sm">
						Last saved { moment(last_saved).fromNow() }
					</span>
				) }
			</h1>
			<Editor
				content_type={ content.content_type }
				id={ content.id }
				existing={ content.data }
				onSave={ onSave }
				saving={ saving }
			/>
		</>
	);
};
