import React from 'react';
import GraphiQL from 'graphiql';
import ErrorBoundary from "../components/ErrorBoundary";
import useAppContext from "../hooks/useAppContext";

import 'graphiql/graphiql.css';

export default function Tinker() {
	const { graphql_endpoint, csrf_token } = useAppContext();
	const fetcher = body => {
		return fetch(graphql_endpoint, {
			method: 'post',
			headers: {
				'content-type': 'application/json',
				'x-csrf-token': csrf_token,
			},
			body: JSON.stringify(body),
		}).then(response => response.json());
	};
	
	return (
		<ErrorBoundary name="the GraphiQL component">
			<div className="h-screen">
				<GraphiQL fetcher={ fetcher } />
			</div>
		</ErrorBoundary>
	);
};
