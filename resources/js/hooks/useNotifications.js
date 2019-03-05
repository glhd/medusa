import { useState, useRef, useEffect } from 'react';
import moment from "moment";

export default function useNotifications() {
	const [notifications, setNotifications] = useState([]);
	
	const interval = useRef();
	useEffect(() => {
		clearInterval(interval.current);

		interval.current = setInterval(() => {
			if (!notifications.length) {
				return;
			}
			
			setNotifications(notifications.filter(notification => {
				return moment().diff(notification.created_at) < notification.timeout;
			}));
		}, 500);

		return () => clearInterval(interval.current);
	}, []);
	
	const addNotification = (message, config = {}) => {
		const id = Symbol(message);
		const created_at = moment();
		const { dangerous = false, successful = false, timeout = 5000 } = config;
		
		setNotifications([...notifications, { id, message, dangerous, successful, timeout, created_at }]);
		
		const cleanup = () => {
			const index = notifications.findIndex(notification => notification.id === id);
			if (index > -1) {
				setNotifications(reorder(notifications, index));
			}
		};
		
		return cleanup();
	};
	
	return [notifications, addNotification];
};

const reorder = (list, from, to = null) => {
	const result = Array.from(list);
	const [removed] = result.splice(from, 1);
	if (null !== to) {
		result.splice(to, 0, removed);
	}
	return result;
};
