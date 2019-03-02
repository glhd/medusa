import React from 'react';

export default ({ message }) => {
	return (
		<div className="fixed pin-l pin-r pin-b p-8" style={{ transition: "all .7s", opacity: 0; }}>
			<div className="container mx-auto text-right">
				<div className="inline-block bg-grey-dark text-white font-semibold py-3 px-6 rounded-full shadow">
					{ message }
				</div>
			</div>
		</div>
	);
}

/*
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
