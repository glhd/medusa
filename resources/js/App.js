import React, { useMemo, useState } from 'react';
import { Router } from '@reach/router';
import { ApolloClient, HttpLink, InMemoryCache } from 'apollo-boost';
import { ApolloProvider } from 'react-apollo';
import AppProvider from './AppProvider';
import Layout from './components/Layout';
import Home from './views/Home';
import Content from './views/Content';
import NewContent from './views/NewContent';
import ErrorBoundary from "./components/ErrorBoundary";

export default function App(context) {
	const { basepath, graphql_endpoint } = context;
	
	const apollo_client = useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({ uri: graphql_endpoint }),
			cache: new InMemoryCache(),
		});
	}, [graphql_endpoint]);
	
	const root_context = { ...context, apollo_client };
	
	return (
		<ApolloProvider client={apollo_client}>
			<AppProvider root_context={root_context}>
				<ErrorBoundary name="wrapper">
					<Layout>
						<Router basepath={basepath}>
							<Home path="/" />
							<Home path="/page/:page" />
							<Content path="/content/:id" />
							<NewContent path="/new/:content_type_id" />
						</Router>
					</Layout>
				</ErrorBoundary>
			</AppProvider>
		</ApolloProvider>
	);
}
