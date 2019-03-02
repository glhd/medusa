import React from 'react';
import { Link } from '@reach/router';

export default ({ children }) => {
	return (
		<div className="font-sans antialias">
			<div className="bg-grey-lightest border-b p-2">
				<div className="container mx-auto flex items-baseline">
					<h1 className="text-lg font-normal mr-4">
						<Link to="." className="no-underline text-grey-dark hover:underline">
							Medusa
						</Link>
					</h1>
				</div>
			</div>
			<div className="container mx-auto py-8">
				{ children }
			</div>
		</div>
	);
};
