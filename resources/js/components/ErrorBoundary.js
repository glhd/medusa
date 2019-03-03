import React from 'react';

export default class ErrorBoundary extends React.Component {
	constructor(props) {
		super(props);
		this.state = { error: null };
	}
	
	static getDerivedStateFromError(error) {
		return { error };
	}
	
	componentDidCatch(error, info) {
		console.error(error, info);
	}
	
	render() {
		if (this.state.error) {
			return (
				<div className="border-red bg-red-lightest text-red-darker p-4 rounded">
					<div className="font-bold mb-2">
						An error occurred in { this.props.name || 'this component' }
					</div>
					<pre>{ JSON.stringify(this.state.error, null, 2) }</pre>
				</div>
			);
		}
		
		return this.props.children;
	}
}
