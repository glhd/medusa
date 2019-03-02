import React from 'react';
import { Link, navigate } from '@reach/router';
import { Menu, MenuList, MenuButton, MenuItem } from '@reach/menu-button';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/free-solid-svg-icons";
import { faPlusSquare } from "@fortawesome/free-regular-svg-icons";
import useAppContext from '../hooks/useAppContext';

export default ({ children }) => {
	const { content_types, basepath } = useAppContext();
	return (
		<div className="font-sans antialias">
			<div className="bg-grey-lightest border-b p-2">
				<div className="container mx-auto flex items-baseline">
					<h1 className="text-lg font-normal mr-4">
						<Link to="." className="no-underline text-grey-dark hover:underline">
							Medusa
						</Link>
					</h1>
					<Menu>
						<MenuButton className="ml-4 pl-4 border-l text-base text-grey-dark hover:underline">
							<FontAwesomeIcon icon={faPlusSquare} fixedWidth className="mr-1" />
							Crate New…
						</MenuButton>
						<MenuList className="rounded border-grey-light shadow px-0 py-1">
							{ Object.values(content_types).map(content_type => (
								<MenuItem key={content_type.id} className="px-4 py-2" onSelect={() => navigate(`${basepath}/new/${content_type.id}`)}>
									<FontAwesomeIcon icon={faPlus} fixedWidth className="mr-1" />
									New { content_type.title }
								</MenuItem>
							))}
						</MenuList>
					</Menu>
				</div>
			</div>
			<div className="container mx-auto py-8">
				{ children }
			</div>
		</div>
	);
};
