import React, { useState } from 'react';
import gql from 'graphql-tag';
import { useMutation, useQuery } from 'react-apollo-hooks';
import Editor from './Editor';
import moment from "moment";
import useAppContext from '../hooks/useAppContext';
import Loading from './Loading';

export default ({ id }) => {
	const { data, error, loading } = useQuery(content, { variables: { id } });
	const { addNotification } = useAppContext();
	
	const [saving, setSaving] = useState(false);
	const [last_saved, setLastSaved] = useState(null); // FIXME: This won't update unless we re-render
	
	const mutation = useMutation(updateContent);
	
	if (loading) {
		return <Loading />;
	}
	
	const result = data.getContent;
	const existing = JSON.parse(result.data);
	
	const onSave = (data) => {
		setSaving(true);
		mutation({
			variables: {
				id: result.id,
				data: JSON.stringify(data)
			},
			update: () => {
				setSaving(false);
				setLastSaved(new Date());
				addNotification(`Your changes to this ${result.content_type.title} have been saved!`);
			}
		}).catch(err => {
			setSaving(false);
			addNotification(`Therre was an error saving this ${result.content_type.title}.`, { dangerous: true, timeout: 7500 });
		});
	};
	
	return (
		<div>
			<h1 className="text-lg font-semibold text-grey-dark mb-6 flex justify-between items-baseline">
				<span>
					Update { result.content_type.title }
				</span>
				{ last_saved && (
					<span className="text-grey font-normal text-sm">
						Last saved { moment(last_saved).fromNow() }
					</span>
				)}
			</h1>
			<Editor
				content_type={result.content_type}
				id={result.id}
				existing={existing}
				onSave={onSave}
				saving={saving}
			/>
		</div>
	);
};

const content = gql`
    query Content($id: ID!) {
        getContent(id: $id) {
            id
            description
            content_type {
                id
                title
                is_singleton
                fields {
                    name
                    component
                    display_name
                    label
                    config
                    initial_value
                }
                rules
                messages
            }
            data
        }
    }
`;

const updateContent = gql`
    mutation updateContent($id: ID!, $data: String!) {
        updateContent(
            id: $id,
	        data: $data,
        ) {
            id
            description
            content_type {
                id
                title
                is_singleton
                fields {
                    name
                    component
                    display_name
                    label
                    config
                    initial_value
                }
                rules
                messages
            }
            data
        }
    }
`;
