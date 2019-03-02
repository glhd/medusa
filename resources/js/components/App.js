import React, { useMemo, useState } from 'react';
import { Router } from '@reach/router';
import { ApolloClient } from 'apollo-boost';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloProvider } from 'react-apollo-hooks';
import Layout from './Layout';
import Home from './Home';
import Content from './Content';
import NewContent from './NewContent';
import { AppContext } from "../hooks/useAppContext";

export default (context) => {
	const { basepath, graphql_endpoint, content_types } = context;
	
	const apollo_client = useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({ uri: graphql_endpoint }),
			cache: new InMemoryCache(),
		});
	}, [graphql_endpoint]);
	
	return (
		<AppContext.Provider value={context}>
			<ApolloProvider client={apollo_client}>
				<Router basepath={basepath}>
					<Layout path="/">
						<Home path="/" />
						<Home path="/page/:page" />
						<Content path="/content/:id" />
						<NewContent path="/new/:name" />
					</Layout>
				</Router>
			</ApolloProvider>
		</AppContext.Provider>
	);
};
