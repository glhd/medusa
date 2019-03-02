import React, { useState } from 'react';
import { navigate } from '@reach/router';
import gql from 'graphql-tag';
import { useMutation } from 'react-apollo-hooks';
import Editor from './Editor';
import useAppContext from "../hooks/useAppContext";

export default ({ name }) => {
	const { content_types, basepath, addNotification } = useAppContext();
	
	const [saving, setSaving] = useState(false);
	
	if (!(name in content_types)) {
		return <div>Content type not found!</div>; // FIXME
	}
	
	const content_type = content_types[name];
	
	const createContentMutation = useMutation(createContent);
	
	const onSave = (data) => {
		setSaving(true);
		createContentMutation({
			variables: {
				content_type_id: content_type.id,
				data: JSON.stringify(data)
			},
			update: (proxy, result) => {
				debugger;
				const { id } = result.data.createContent;
				addNotification(`Created new ${content_type.title}!`);
				navigate(`${basepath}/content/${id}`);
			}
		}).catch(err => {
			setSaving(false);
			addNotification(`There was an error saving this ${content_type.title}.`, { dangerous: true, timeout: 7500 });
		});
	};
	
	return (
		<div>
			<h1 className="text-lg font-semibold text-grey-dark mb-6">
				Create New { content_type.title }
			</h1>
			<Editor content_type={ content_type } onSave={ onSave } saving={saving} />
		</div>
	);
};

const createContent = gql`
    mutation createContent($content_type_id: ID!, $data: String!) {
        createContent(
            content: {
                content_type_id: $content_type_id,
                data: $data
            }
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
