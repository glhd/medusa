import React from 'react';
import { Query } from 'react-apollo';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLink, faPen } from "@fortawesome/free-solid-svg-icons";
import { GET_CONTENT } from '../../../graphql/queries';

export default function Selected({ id, onChangeSelected }) {
	return (
		<Query query={ GET_CONTENT } variables={ { id } }>
			{ ({ data, loading, error }) => {
				if (loading || !('getContent' in data)) {
					return null;
				}
				
				const value = data.getContent;
				
				return (
					<div className="py-2 px-4 border border-green rounded flex items-center text-sm">
						<span className="mr-2 text-green">
							<FontAwesomeIcon icon={faLink} className="text-green mr-1" />
							{ value.content_type.title }
						</span>
						<span className={`font-mono p-1 border rounded text-grey bg-grey-lightest`}>
							{ value.slug }
						</span>
						<span className="ml-2">
							{ value.description }
						</span>
						<button onClick={() => onChangeSelected()} className="ml-auto text-green no-underline font-bold cursor-pointer hover:underline">
							<FontAwesomeIcon icon={faPen} className="text-green mr-1" />
							Change
						</button>
					</div>
				);
			} }
		</Query>
	);
};
