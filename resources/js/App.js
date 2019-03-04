import React, { useMemo } from 'react';
import { ApolloClient, HttpLink, InMemoryCache } from 'apollo-boost';
import { ApolloProvider } from 'react-apollo';
import AppProvider from './AppProvider';
import Layout from './components/Layout';
import ErrorBoundary from "./components/ErrorBoundary";
import { Router } from "@reach/router";
import Home from "./views/Home";
import UpdateContent from "./views/UpdateContent";
import CreateContent from "./views/CreateContent";

export default function App(context) {
	const { basepath, graphql_endpoint } = context;
	const apollo_client = useApolloClient(graphql_endpoint);
	const root_context = { ...context, apollo_client };
	
	return (
		<ApolloProvider client={ apollo_client }>
			<AppProvider root_context={ root_context }>
				<ErrorBoundary name="the App wrapper">
					<Layout>
						<Router basepath={ basepath }>
							<Home path="/" />
							<Home path="/page/:page" />
							<UpdateContent path="/content/:id" />
							<CreateContent path="/new/:content_type_id" />
						</Router>
					</Layout>
				</ErrorBoundary>
			</AppProvider>
		</ApolloProvider>
	);
}

function useApolloClient(graphql_endpoint) {
	return useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({ uri: graphql_endpoint }),
			cache: new InMemoryCache({
				dataIdFromObject: object => {
					if ('id' in object) {
						return object.id;
					}
					if ('name' in object && '__typename' in object && 'Field' === object.__typename) {
						return object.name;
					}
					return null;
				}
			}),
		});
	}, [graphql_endpoint]);
}
