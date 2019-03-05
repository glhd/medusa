import { useMemo } from 'react';
import useEditorContext from '../hooks/useEditorContext';

export default function useReferencedField(name, fallback = null) {
	const { data } = useEditorContext();
	return useMemo(() => {
		return name in data
			? data[name]
			: fallback;
	}, [data, name, fallback]);
};
