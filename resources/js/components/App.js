import React, { useMemo, useState } from 'react';
import { Router } from '@reach/router';
import { ApolloClient } from 'apollo-boost';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloProvider } from 'react-apollo-hooks';
import Layout from './Layout';
import Home from './Home';
import Content from './Content';
import Debugger from './Debugger';
import useData from '../hooks/useData';
import useValidation from '../hooks/useValidation';
import { MedusaContext } from '../hooks/useMedusaContext';

export default (props) => {
	const { basepath, graphql_endpoint } = props;
	
	const apollo_client = useMemo(() => {
		return new ApolloClient({
			link: new HttpLink({ uri: graphql_endpoint }),
			cache: new InMemoryCache(),
		});
	}, [graphql_endpoint]);
	
	return (
		<ApolloProvider client={apollo_client}>
			<Router basepath={basepath}>
				<Layout path="/">
					<Home path="/" />
					<Home path="/page/:page" />
					<Content path="/content/:id" />
					<Debugger path="/create/:content_type" {...props} />
				</Layout>
			</Router>
		</ApolloProvider>
	);
};

const old_app = ({ config, existing, old, server_errors }) => {
	const { fields, rules, content_type } = config;
	const initial_data = initialData(fields, existing, old);
	const { data, changed, touched, setData, setTouched } = useData(fields, initial_data);
	const [dependencies, setDependencies] = useState({});
	const errors = useValidation(data, rules, touched, server_errors);
	
	const medusa = { data, changed, touched, errors, dependencies, setDependencies, setData, setTouched };
	
	const creating = 0 === Object.keys(existing).length;
	
	return (
		<MedusaContext.Provider value={medusa}>
			<h1 className="text-lg text-grey-dark">
				{ creating ? 'Create New' : 'Update' } { content_type.title }
			</h1>
			
			{/*<Fields fields={fields} />*/}
			
			{/*{ Object.keys(errors).length > 0 && (*/}
				{/*<div className="border border-red rounded bg-red-lightest text-red p-4">*/}
					{/*There are errors on this page that you must fix before saving.*/}
				{/*</div>*/}
			{/*) }*/}
			
			{/*<Debugger changed={changed} touched={touched} />*/}
			<Debugger {...data} />
			{/*<Debugger {...server_errors} />*/}
			
			<input name="content_type" type="hidden" value={ content_type.id } />
			<input name="data" type="hidden" value={JSON.stringify(data)} />
			
			<div className="py-4">
				<button type="Submit" className="mx-2 bg-blue border border-blue-darker text-white border rounded px-6 py-3">
					{ creating ? 'Create' : 'Save Changes to' } { content_type.title }
				</button>
			</div>
		</MedusaContext.Provider>
	);
};

function initialData(fields, existing, old) {
	return useMemo(() => {
		const data = {};
		
		Object.values(fields).forEach(field => {
			data[field.name] = field.initial_value;
		});
		
		return {
			...data,
			...existing,
			...old,
		};
	}, [fields, existing, old]);
}
