import React, { useContext } from 'react';

export const EditorContext = React.createContext({});

export default function useEditorContext() {
	return useContext(EditorContext);
}
