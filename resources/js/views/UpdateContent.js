import React, { useState } from 'react';
import { Mutation, Query } from "react-apollo";
import moment from "moment";
import useAppContext from '../hooks/useAppContext';
import Editor from '../components/Editor';
import Loading from '../components/Loading';
import { GET_CONTENT } from "../graphql/queries";
import { UPDATE_CONTENT } from "../graphql/mutations";
import ErrorBoundary from "../components/ErrorBoundary";

export default function UpdateContent({ id }) {
	const { addNotification } = useAppContext();
	const [last_saved, setLastSaved] = useState(null); // FIXME: This won't update unless we re-render
	
	return (
		<ErrorBoundary name="the Content component">
			<Query query={ GET_CONTENT } variables={ { id } }>
				{ ({ data, error, loading }) => {
					if (loading || error) {
						return <Loading />;
					}
					
					const content = {
						...data.getContent,
						data: JSON.parse(data.getContent.data),
					};
					
					const { content_type } = content;
					
					const mutation_config = {
						mutation: UPDATE_CONTENT,
						onCompleted: () => {
							// FIXME: Handle error message from server
							setLastSaved(new Date());
							addNotification(`Your changes to this ${ content_type.title } have been saved!`, { successful: true });
						},
						onError: () => {
							addNotification(`Therre was an error saving this ${ content_type.title }.`, { dangerous: true, timeout: 7500 });
						},
					};
					
					return (
						<Mutation { ...mutation_config }>
							{ (updateContent, { loading: saving }) => {
								const onSave = (updated_data) => {
									updateContent({
										variables: {
											id: content.id,
											data: JSON.stringify(updated_data)
										}
									});
								};
								
								return (
									<>
										<h1 className="text-lg font-semibold text-grey-dark mb-6 flex justify-between items-baseline">
											<span>
												Update { content_type.title }
											</span>
											{ last_saved && (
												<span className="text-grey font-normal text-sm">
													Last saved { moment(last_saved).fromNow() }
												</span>
											) }
										</h1>
										<Editor
											content_type={ content_type }
											id={ content.id }
											existing={ content.data }
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
}
