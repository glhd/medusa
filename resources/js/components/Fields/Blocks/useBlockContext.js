import React, { useContext } from "react";

export const BlocksContext = React.createContext({});

export default function useBlockContext() {
	return useContext(BlocksContext);
}
