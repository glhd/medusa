import { useState, useRef, useEffect } from 'react';

export default function useNotifications() {
	const [notifications, setNotifications] = useState([]);
	const handle = useRef();
	
	useEffect(() => {
		clearTimeout(handle.current);
		handle.current = setInterval(() => {
			const now = Date.now();
			const filtered = notifications.filter(notification => notification.expiration > now);
			if (filtered.length !== notifications.length) {
				console.log('updating notifications');
				setNotifications(filtered);
			}
		}, 500);

		return () => clearInterval(handle.current);
	}, [notifications]);
	
	const addNotification = (message, config = {}) => {
		const id = Symbol(message);
		const { dangerous = false, successful = false, timeout = 5000 } = config;
		const expiration = Date.now() + timeout;
		
		setNotifications([...notifications, { id, message, dangerous, successful, timeout, expiration }]);
	};
	
	return [notifications, addNotification];
};
