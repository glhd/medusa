import React from 'react';
import gql from 'graphql-tag';
import { useQuery } from 'react-apollo-hooks';
import Debugger from './Debugger';
import Editor from './Editor';

const content = gql`
    query Content($id: String!) {
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

export default ({ id }) => {
	const { data, error, loading } = useQuery(content, { variables: { id } });
	
	if (loading) {
		return null; // FIXME
	}
	
	const result = data.getContent;
	const existing = JSON.parse(result.data);
	
	return (
		<div>
			{/*<Debugger {...data.getContent} />*/}
			<Editor content_type={result.content_type} id={result.id} existing={existing} />
		</div>
	);
};
