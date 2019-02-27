import React from 'react';

export default (config) => {
	return (
		<div>
			<pre className="bg-grey-lightest border shadow-lg text-xs text-grey-darker font-mono rounded p-8 my-8">{ JSON.stringify(config, null, 2) }</pre>
		</div>
	);
};
