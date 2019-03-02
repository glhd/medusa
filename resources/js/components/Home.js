import React from 'react';
import { Link } from '@reach/router';
import gql from 'graphql-tag';
import { useQuery } from 'react-apollo-hooks';
import Loading from "./Loading";

export default ({ page }) => {
	const { data, error, loading } = useQuery(currentPage);
	
	if (loading) {
		return <Loading />;
	}
	
	const { total, per_page, content } = data.allContent;
	
	// TODO: Pagination
	
	return (
		<table className="w-full">
			<thead>
				<tr>
					<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						Content Type
					</th>
					<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						URL Slug
					</th>
					<th className="border-b border-grey-lighter p-2 text-left text-sm font-semibold text-grey-dark">
						Description
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
						<td className="px-2 py-4">
							<Link to={ `content/${ content.id }` } className="font-mono text-sm text-grey no-underline hover:text-blue hover:underline">
								{ content.slug }
							</Link>
						</td>
						<td className="px-2 py-4">
							<Link to={ `content/${ content.id }` } className="font-semibold text-grey-darker no-underline hover:underline hover:text-blue">
								{ content.description }
							</Link>
						</td>
					</tr>
				)) }
			</tbody>
		</table>
	);
};

const currentPage = gql`
    {
        allContent {
            total
            per_page
            content {
                id
                slug
                description
                content_type {
                    title
                }
            }
        }
    }
`;
