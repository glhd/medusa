import React, { useEffect, useState } from 'react';
import FroalaEditor from 'react-froala-wysiwyg';
import { loadCSS } from 'fg-loadcss';
import Group from '../Form/Group';

let dependenciesLoaded = false;

export default function RichText(props) {
	const { field, value, onChange } = props;
	const { id } = field;
	
	const editor_config = {
		key: field.config.key, // TODO
		linkAutoPrefix: 'https://',
	};
	
	let wysiwyg = null;
	const loaded = useDependencies();
	
	if (loaded) {
		wysiwyg = <FroalaEditor
			id={id}
			tag="textarea"
			model={ value }
			config={ editor_config }
			onModelChange={ onChange }
		/>;
	}
	
	return (
		<Group { ...props }>
			{ wysiwyg }
		</Group>
	);
};

function loadStyle(src) {
	return new Promise(resolve => {
		loadCSS(src).onloadcssdefined(resolve);
	});
}

function loadScript(src) {
	return new Promise((resolve, reject) => {
		const script = document.createElement('script');
		script.onload = resolve;
		script.onerror = reject;
		script.src = src;
		
		const siblingElement = document.getElementsByTagName('link')[0];
		siblingElement.parentNode.insertBefore(script, siblingElement);
	});
}

function useDependencies() {
	const [loaded, setLoaded] = useState(dependenciesLoaded);
	
	useEffect(() => {
		// TODO: Conditionally load
		Promise.all([
			loadStyle('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css'),
			loadStyle('https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_editor.pkgd.min.css'),
			loadStyle('https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_style.min.css'),
			loadScript('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js'),
			loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js'),
			loadScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js'),
			loadScript('https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/js/froala_editor.pkgd.min.js'),
		]).then(() => {
			setTimeout(() => {
				dependenciesLoaded = true;
				setLoaded(true);
			}, 100);
		});
	}, []);
	
	return loaded;
}
