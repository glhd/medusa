import React from 'react';
import useAppContext from '../hooks/useAppContext';

export default function Snackbar() {
	const { notifications } = useAppContext();
	
	return (
		<div className="fixed pin-l pin-r pin-b p-8">
			<div className="container mx-auto flex flex-col md:justify-end md:items-end">
				{ notifications.map(notification => {
					const { message, dangerous, successful } = notification;
					
					let style = 'bg-grey-darker text-white';
					
					if (successful) {
						style = 'bg-green-dark text-white';
					}
					if (dangerous) {
						style = 'bg-red-darker text-lg text-white';
					}
					
					return (
						<div key={ message } className={ `${ style } font-semibold py-3 px-6 mt-1 rounded-full shadow-lg` }>
							{ message }
						</div>
					);
				}) }
			</div>
		</div>
	);
}

/*
style={{ transition: "all .7s", opacity: 0; }}
document.addEventListener('DOMContentLoaded', function() {
	var bar = document.getElementById('snackbar');
	bar.style.opacity = '1';
	bar.style.transform = 'translateY(-20px)';
	setTimeout(function() {
		bar.style.opacity = '0';
		bar.style.transform = 'translateY(0)';
	}, 5000);
});
*/
