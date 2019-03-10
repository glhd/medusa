import React, { useState } from 'react';
import Autosuggest from 'react-autosuggest';
import Group from '../Group';
// import Debugger from '../Debugger';

const renderSuggestion = suggestion => (
	<div>
		{ suggestion.description }
	</div>
);

export default function Relationship(props) {
	const [query, setQuery] = useState(``);
	const [suggestions, setSuggestions] = useState([]);
	const { field, value, onChange } = props;
	const { id } = field;
	
	const onQuery = ({ value }) => {
		setSuggestions([
			{ description: 'Foo' },
			{ description: 'Bar' },
		]);
	};
	
	const input_props = {
		id,
		// placeholder: 'Type a programming language',
		value: query,
		onChange: (event, { newValue }) => {
			console.log('onChange', newValue);
			setQuery(newValue);
		},
	};
	
	const theme = {
		container: '',
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
	
	return (
		<Group { ...props }>
			<Autosuggest
				suggestions={ suggestions }
				onSuggestionsFetchRequested={ onQuery }
				onSuggestionsClearRequested={ () => setSuggestions([]) }
				getSuggestionValue={ suggestion => suggestion.description }
				renderSuggestion={ renderSuggestion }
				inputProps={ input_props }
				theme={ theme }
			/>
		</Group>
	);
};
