import React from 'react';
import { Menu, MenuButton, MenuItem, MenuList } from "@reach/menu-button";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronCircleDown } from "@fortawesome/free-solid-svg-icons";
import { faClone, faPlusSquare, faTrashAlt } from "@fortawesome/free-regular-svg-icons";

export default function ActionMenu({ onClone, onDelete }) {
	return (
		<Menu>
			<MenuButton className="text-grey-light hover:text-grey py-2">
				<FontAwesomeIcon icon={ faChevronCircleDown } size="lg" />
			</MenuButton>
			<MenuList className="rounded border-grey-light shadow px-0 py-1">
				<MenuItem className="px-4 py-2" onSelect={ onClone }>
					<FontAwesomeIcon className="mr-2" icon={ faClone } fixedWidth={ true } />
					Duplicate
				</MenuItem>
				<MenuItem className="px-4 py-2" onSelect={ onDelete }>
					<FontAwesomeIcon className="mr-2" icon={ faTrashAlt } fixedWidth={ true } />
					Delete
				</MenuItem>
				<MenuItem className="px-4 py-2" onSelect={ () => alert('todo') }>
					<FontAwesomeIcon className="mr-2" icon={ faPlusSquare } fixedWidth={ true } />
					Add Below…
				</MenuItem>
				<MenuItem className="px-4 py-2" onSelect={ () => alert('todo') }>
					<FontAwesomeIcon className="mr-2" icon={ faPlusSquare } fixedWidth={ true } />
					Add Above…
				</MenuItem>
			</MenuList>
		</Menu>
	);
}
