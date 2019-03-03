import React, { useMemo } from 'react';
import { useQuery } from 'react-apollo-hooks';
import { AppContext } from "../hooks/useAppContext";
import useNotifications from '../hooks/useNotifications';
import Loading from "./Loading";
import { ALL_CONTENT_TYPES } from "../queries";
import Debugger from "./Debugger";

export default ({ root_context, children }) => {
	const [notifications, addNotification] = useNotifications();
	const { data, error, loading } = useQuery(ALL_CONTENT_TYPES);
	const { allContentTypes = [] } = data;
	
	const content_types = useMemo(() => {
		const content_types = {};
		
		allContentTypes.forEach(content_type => {
			content_types[content_type.id] = content_type;
		});
		
		return content_types;
	}, [allContentTypes]);
	
	if (loading) {
		return <Loading />;
	}
	
	if (error) {
		<Debugger error={error} />;
	}
	
	const context = { ...root_context, content_types, notifications, addNotification };
	
	return (
		<AppContext.Provider value={ context }>
			{ children }
		</AppContext.Provider>
	);
};
