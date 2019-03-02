import React from 'react';
import gql from 'graphql-tag';
import { useQuery } from 'react-apollo-hooks';
import Debugger from './Debugger';
import useFields from "../hooks/useFields";

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
	
	// return useFields(fields)
	// 	.map(({ Field, props }) => <Field {...props} />);
	
	return <Debugger data={data} error={error} loading={loading} />;
};
