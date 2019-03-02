import React, { useMemo, useState } from 'react';
import { Router } from '@reach/router';
import { ApolloClient } from 'apollo-boost';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloProvider } from 'react-apollo-hooks';
import AppProvider from './AppProvider';
import Layout from './Layout';
import Home from './Home';
import Content from './Content';
import NewContent from './NewContent';

export default (context) => {
	const { basepath, graphql_endpoint } = context;
	
	const apollo_client = useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({ uri: graphql_endpoint }),
			cache: new InMemoryCache(),
		});
	}, [graphql_endpoint]);
	
	return (
		<ApolloProvider client={apollo_client}>
			<AppProvider root_context={context}>
				<Layout>
					<Router basepath={basepath}>
						<Home path="/" />
						<Home path="/page/:page" />
						<Content path="/content/:id" />
						<NewContent path="/new/:name" />
					</Router>
				</Layout>
			</AppProvider>
		</ApolloProvider>
	);
};
