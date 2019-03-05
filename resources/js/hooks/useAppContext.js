import React, { useContext } from 'react';

export const AppContext = React.createContext({});

export default function useAppContext() {
	return useContext(AppContext);
}
