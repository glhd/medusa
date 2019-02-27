import React from 'react';

export default (props) => {
	return (
		<div>
			<pre className="bg-grey-lightest border shadow-lg text-xs text-grey-darker font-mono rounded p-8 my-8">
				{ JSON.stringify(props, null, 2) }
			</pre>
			<input
				className="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
				value={props.value}
				onChange={e => props.onChange(e.target.value)}
			/>
		</div>
	);
};
