import React from 'react';

export default class ErrorBoundary extends React.Component {
	constructor(props) {
		super(props);
		this.state = { error: null, info: null };
	}
	
	static getDerivedStateFromError(error) {
		return { error, info: null };
	}
	
	componentDidCatch(error, info) {
		console.error(error, info);
		this.setState({ error, info });
	}
	
	render() {
		const { error, info } = this.state;
		
		if (error) {
			return (
				<div className="border-red bg-red-lightest text-red-darker p-4 rounded">
					<div className="font-bold mb-2">
						An error occurred in { this.props.name || 'this component' }:
					</div>
					
					<div className="my-1">
						&ldquo;{ error.message }&rdquo;
					</div>
					{ (info && 'componentStack' in info) && info.componentStack
						.split("\n").map(line => (
							<div className="my-1 ml-3">
								{ line }
							</div>
						)) }
				</div>
			);
		}
		
		return this.props.children;
	}
}
