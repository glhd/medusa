import React from 'react';
import { navigate } from '@reach/router';
import { Mutation, Query } from "react-apollo";
import useAppContext from "../hooks/useAppContext";
import Editor from '../components/Editor';
import Loading from "../components/Loading";
import ErrorBoundary from "../components/ErrorBoundary";
import { GET_CONTENT_TYPE } from '../graphql/queries';
import { CREATE_CONTENT } from '../graphql/mutations';

export default function CreateContent({ content_type_id }) {
	const { basepath, addNotification } = useAppContext();
	return (
		<ErrorBoundary name="the NewContent component">
			<Query query={ GET_CONTENT_TYPE } variables={ { id: content_type_id } }>
				{ ({ data, loading }) => {
					if (loading) {
						return <Loading />;
					}
					
					const content_type = data.getContentType;
					
					const mutation_config = {
						mutation: CREATE_CONTENT,
						onError: () => {
							addNotification(`There was an error saving this ${ content_type.title }.`, { dangerous: true, timeout: 7500 });
						},
						onCompleted: (data) => {
							const { id } = data.createContent;
							addNotification(`Created new ${ content_type.title }!`, { successful: true });
							navigate(`${ basepath }/content/${ id }`);
						},
					};
					
					return (
						<Mutation { ...mutation_config }>
							{ (createContent, { loading: saving }) => {
								const onSave = (data) => {
									createContent({
										variables: {
											content_type_id: content_type.id,
											data: JSON.stringify(data)
										}
									});
								};
								
								return (
									<>
										<h1 className="text-lg font-semibold text-grey-dark m-6 mt-0">
											Create New { content_type.title }
										</h1>
										<Editor
											content_type={ content_type }
											onSave={ onSave }
											saving={ saving }
										/>
									</>
								);
							} }
						</Mutation>
					);
				} }
			</Query>
		</ErrorBoundary>
	);
};
