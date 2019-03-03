import React from 'react';
import { AppContext } from "./hooks/useAppContext";
import useNotifications from './hooks/useNotifications';

export default function AppProvider({ root_context, children }) {
	const [notifications, addNotification] = useNotifications();
	const context = { ...root_context, notifications, addNotification };
	return (
		<AppContext.Provider value={ context }>
			{ children }
		</AppContext.Provider>
	);
}
