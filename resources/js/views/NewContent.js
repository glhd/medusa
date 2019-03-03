import React from 'react';
import { navigate } from '@reach/router';
import { Mutation } from "react-apollo";
import useAppContext from "../hooks/useAppContext";
import Editor from '../components/Editor';
import ErrorBoundary from "../components/ErrorBoundary";
import useQuery from "../hooks/useQuery";
import { ALL_CONTENT, GET_CONTENT_TYPE } from '../graphql/queries';
import { CREATE_CONTENT } from '../graphql/mutations';
import Loading from "../components/Loading";

export default function NewContent({ content_type_id }) {
	const { basepath, addNotification } = useAppContext();
	const { result, loading } = useQuery(GET_CONTENT_TYPE, {
		variables: { id: content_type_id }
	});
	
	if (loading) {
		return <Loading />;
	}
	
	const content_type = result.getContentType;
	
	return (
		<ErrorBoundary name="the NewContent component">
			<Mutation mutation={ CREATE_CONTENT }>
				{ (createContent, { loading: saving }) => {
					const onSave = (data) => {
						createContent({
							variables: {
								content_type_id: content_type.id,
								data: JSON.stringify(data)
							},
							onError: () => {
								addNotification(`There was an error saving this ${ content_type.title }.`, { dangerous: true, timeout: 7500 });
							},
							onCompleted: (data) => {
								const { id } = data.createContent;
								addNotification(`Created new ${ content_type.title }!`, { successful: true });
								navigate(`${ basepath }/content/${ id }`);
							},
							refetchQueries: [
								{
									query: ALL_CONTENT,
									variables: { page: 1 },
								}
							],
						});
					};
					
					return (
						<>
							<h1 className="text-lg font-semibold text-grey-dark mb-6">
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
		</ErrorBoundary>
	);
};
