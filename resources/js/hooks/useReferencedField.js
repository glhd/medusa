import { useMemo } from 'react';
import useMedusaContext from '../hooks/useMedusaContext';

export default function useReferencedField(name, fallback = null) {
	const { data } = useMedusaContext();
	return useMemo(() => {
		return name in data
			? data[name]
			: fallback;
	}, [data, name, fallback]);
};
