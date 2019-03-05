import React from 'react';
import { AppContext } from "./hooks/useAppContext";

export default function AppProvider({ context, children }) {
	return (
		<AppContext.Provider value={ context }>
			{ children }
		</AppContext.Provider>
	);
}
