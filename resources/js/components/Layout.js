import React from 'react';
import { Link, navigate } from '@reach/router';
import { Menu, MenuButton, MenuItem, MenuList } from '@reach/menu-button';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus, faTerminal } from "@fortawesome/free-solid-svg-icons";
import { faPlusSquare } from "@fortawesome/free-regular-svg-icons";
import { Query } from "react-apollo";
import Snackbar from "./Snackbar";
import useAppContext from '../hooks/useAppContext';
import { ALL_CONTENT_TYPES } from '../graphql/queries'; // TODO: We really only need name and ID here

export default function Layout({ children }) {
	const { basepath, name, env } = useAppContext();
	
	return (
		<Query query={ ALL_CONTENT_TYPES }>
			{ ({ data, loading }) => (
				<div className="font-sans antialias bg-grey-lightest min-h-screen flex flex-col">
					<div className="bg-white border-b p-3">
						<div className="container mx-auto flex items-baseline">
							<h1 className="text-lg font-medium mr-4">
								<Link to={ basepath } className="no-underline text-grey-dark hover:underline">
									{ name }
								</Link>
							</h1>
							{ !loading && <ContentMenu basepath={ basepath } content_types={ data.allContentTypes } /> }
							{ 'local' === env && (
								<Link to={`${basepath}/tinker`} className="ml-auto no-underline font-mono text-sm text-purple hover:underline">
									<FontAwesomeIcon icon={faTerminal} className="mr-1" />
									Tinker
								</Link>
							)}
						</div>
					</div>
					<div className="container mx-auto py-8 flex-1">
						{ children }
					</div>
					<Snackbar />
				</div>
			) }
		</Query>
	);
};

function ContentMenu({ basepath, content_types }) {
	return (
		<Menu>
			<MenuButton className="ml-4 pl-4 border-l text-base text-grey-dark hover:underline">
				<FontAwesomeIcon icon={ faPlusSquare } fixedWidth className="mr-1" />
				Create New…
			</MenuButton>
			<MenuList className="rounded border-grey-light shadow px-0 py-1">
				{ content_types.map(content_type => (
					<MenuItem
						key={ content_type.id }
						className="px-4 py-2"
						onSelect={ () => navigate(`${ basepath }/new/${ content_type.id }`) }>
						<FontAwesomeIcon icon={ faPlus } fixedWidth className="mr-1" />
						New { content_type.title }
					</MenuItem>
				)) }
			</MenuList>
		</Menu>
	);
}
