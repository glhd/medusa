import React, { useState, useRef, useEffect } from 'react';
import Autosuggest from 'react-autosuggest';
import { Query } from 'react-apollo';
import Group from '../Group';
import { SEARCH_CONTENT } from '../../graphql/queries';
import Selected from "./Relationship/Selected";

const renderSuggestion = (suggestion, { isHighlighted }) => (
	<div key={ suggestion.id } className="flex items-center text-sm">
		<span
			className={ `font-mono p-1 border rounded ${ isHighlighted ? 'text-blue-dark bg-blue-lightest border-blue-lighter'
				: 'text-grey bg-grey-lightest' }` }>
			{ suggestion.slug }
		</span>
		
		<span className="ml-2">
			{ suggestion.description }
		</span>
	</div>
);

export default function Relationship(props) {
	const { field, value, onChange } = props;
	const { content_type_id = null } = field.config;
	const [editing, setEditing] = useState(0 === value);
	const [query, setQuery] = useState('');
	let autosuggest_ref = null;
	
	const newQuery = () => {
		setEditing(true);
	};
	
	const onSelected = (event, { suggestion }) => {
		onChange(suggestion.id);
		setEditing(false);
	};
	
	useEffect(() => {
		if (value && editing && null !== autosuggest_ref) {
			autosuggest_ref.focus();
		}
	}, [editing]);
	
	return (
		<Group { ...props }>
			{ (value > 0 && !editing) && (
				<div className="flex-1 px-2">
					<Selected id={ value } onChangeSelected={() => newQuery()} />
				</div>
			) }
			{ (!value || editing) && (
				<Query query={ SEARCH_CONTENT } variables={ { query, content_type_id } }>
					{ ({ data, loading, error }) => {
						const input_props = {
							id: field.id,
							value: query,
							onChange: (e) => null,
						};
						
						let suggestions = (loading || !data)
							? []
							: data.searchContent.content;
						
						const theme = {
							container: 'w-full',
							containerOpen: '',
							input: 'shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight',
							inputOpen: '',
							inputFocused: 'outline-none shadow-outline',
							suggestionsContainer: 'mt-1 overflow-hidden py-1',
							suggestionsContainerOpen: 'shadow appearance-none border rounded',
							suggestionsList: 'list-reset',
							suggestion: 'p-2',
							suggestionFirst: '',
							suggestionHighlighted: 'bg-blue text-white',
							sectionContainer: '',
							sectionContainerFirst: '',
							sectionTitle: ''
						};
						
						const updateRef = (config) => {
							if (config && 'input' in config) {
								autosuggest_ref = config.input;
							}
						};
						
						return (
							<>
								<Autosuggest
									ref={updateRef}
									suggestions={ suggestions }
									onSuggestionsFetchRequested={ ({ value }) => setQuery(value) }
									onSuggestionsClearRequested={ () => setQuery('') }
									getSuggestionValue={ suggestion => suggestion.description }
									onSuggestionSelected={ onSelected }
									renderSuggestion={ renderSuggestion }
									inputProps={ input_props }
									theme={ theme }
								/>
								{ (true === loading) && (
									<div className="text-grey text-sm pt-1">
										Searching…
									</div>
								) }
							</>
						);
					} }
				</Query>
			) }
		</Group>
	);
};
