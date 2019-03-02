import React, { useMemo } from 'react';
import gql from 'graphql-tag';
import { useQuery } from 'react-apollo-hooks';
import { AppContext } from "../hooks/useAppContext";
import useNotifications from '../hooks/useNotifications';
import Loading from "./Loading";

export default ({ root_context, children }) => {
	const [notifications, addNotification] = useNotifications();
	const { data, error, loading } = useQuery(contentTypesQuery);
	const { allContentTypes = [] } = data;
	
	const content_types = useMemo(() => {
		const content_types = {};
		
		allContentTypes.forEach(content_type => {
			console.log(content_type);
			content_types[content_type.id] = {
				...content_type,
				rules: JSON.parse(content_type.rules),
				messages: JSON.parse(content_type.messages),
			};
		});
		
		return content_types;
	}, [allContentTypes]);
	
	if (loading) {
		return <Loading />;
	}
	
	const context = { ...root_context, content_types, notifications, addNotification };
	
	return (
		<AppContext.Provider value={context}>
			{ children }
		</AppContext.Provider>
	);
};

const contentTypesQuery = gql`
    {
        allContentTypes {
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
    }
`;
