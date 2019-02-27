import React, { useContext } from 'react';

export const MedusaContext = React.createContext({});

export function useMedusaContext() {
	return useContext(MedusaContext);
}
