import React from 'react';
import { Link } from '@reach/router';
import useQuery from '../hooks/useQuery';
import Loading from "../components/Loading";
import { ALL_CONTENT } from '../graphql/queries';
import Debugger from "../components/Debugger";
import ErrorBoundary from "../components/ErrorBoundary";

export default function Home({ page = 1 }) {
	const { result, error, loading } = useQuery(ALL_CONTENT, { variables: page });
	
	if (loading) {
		return <Loading />;
	}
	
	if (error) {
		return <Debugger error={ error } />; // FIXME
	}
	
	const { total, per_page, content } = result.allContent;
	
	// TODO: Pagination
	
	return (
		<ErrorBoundary name="the Home component">
			<table className="w-full">
				<thead>
					<tr>
						<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
							Content Type
						</th>
						<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
							Description
						</th>
						<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
							URL Slug
						</th>
					</tr>
				</thead>
				<tbody>
					{ content.map(content => (
						<tr key={ content.id }>
							<td className="px-2 py-4">
								<span className="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">
									{ content.content_type.title }
								</span>
							</td>
							<td>
								<Link
									to={ `content/${ content.id }` }
									className="inline-block px-2 py-4 font-semibold text-grey-darker no-underline hover:underline hover:text-blue">
									{ content.description }
								</Link>
							</td>
							<td>
								<Link
									to={ `content/${ content.id }` }
									className="inline-block px-2 py-4 font-mono text-sm text-grey no-underline hover:text-blue hover:underline">
									{ content.slug }
								</Link>
							</td>
						</tr>
					)) }
				</tbody>
			</table>
		</ErrorBoundary>
	);
};
