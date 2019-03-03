import { useState, useEffect } from 'react';
import useAppContext from './useAppContext';

export default function useQuery(query, options = {}) {
	const { apollo_client } = useAppContext();
	
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);
	const [result, setResult] = useState({});
	
	useEffect(() => {
		apollo_client.query({ query, ...options })
			.then(result => {
				setResult(result.data);
				// TODO: result.error
				setLoading(false);
			})
			.catch(error => {
				setError(error);
				setLoading(false);
			});
	}, [query, options]);
	
	return { loading, error, result };
};
