import React, { useMemo } from 'react';
import { ApolloClient, HttpLink, InMemoryCache } from 'apollo-boost';
import { ApolloProvider } from 'react-apollo';
import Layout from './components/Layout';
import ErrorBoundary from "./components/ErrorBoundary";
import { Router } from "@reach/router";
import { AppContext } from "./hooks/useAppContext";
import Home from "./views/Home";
import UpdateContent from "./views/UpdateContent";
import CreateContent from "./views/CreateContent";
import useNotifications from "./hooks/useNotifications";

export default function App({ context }) {
	const { basepath, graphql_endpoint, csrf_token } = context;
	const [notifications, addNotification] = useNotifications();
	const apollo_client = useApolloClient({ graphql_endpoint, csrf_token });
	
	const app_context = { ...context, notifications, addNotification };
	
	return (
		<AppContext.Provider value={ app_context }>
			<ApolloProvider client={ apollo_client }>
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
			</ApolloProvider>
		</AppContext.Provider>
	);
}

function useApolloClient({ graphql_endpoint, csrf_token }) {
	return useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({
				uri: graphql_endpoint,
				headers: {
					'x-csrf-token': csrf_token,
				},
			}),
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
	}, [graphql_endpoint, csrf_token]);
}
