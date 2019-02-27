import React, { useEffect, useState } from 'react';
import { loadCSS } from 'fg-loadcss';
import FroalaEditor from 'react-froala-wysiwyg';
import Debugger from '../Debugger';

const loadStyle = (src) => new Promise((resolve => {
	loadCSS(src).onloadcssdefined(resolve);
}));

const loadScript = (src) => new Promise((resolve, reject) => {
	const script = document.createElement('script');
	script.onload = resolve;
	script.onerror = reject;
	script.src = src;
	
	const firstScript = document.getElementsByTagName('script')[0];
	firstScript.parentNode.insertBefore(script, firstScript);
});

let dependenciesLoaded = false;

// TODO: This should eventually be packaged into Medusa

const useDependencies = () => {
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
};

export default (props) => {
	const { name, errors, label, initial_value, existing, value, config, onChange } = props;
	const id = `medusa-${ name }`;
	const dirty = existing ? existing !== value : initial_value !== value;
	
	const loaded = useDependencies();
	
	const editorConfig = {
		// placeholderText: config.placeholder || 'Type Something',
		key: config.key, // TODO
		linkAutoPrefix: 'https://',
		// imageUploadParam: 'media',
		// imageUploadURL: String(route('content.media.store')),
		// fileUploadParam: 'media',
		// fileUploadURL: String(route('content.media.store')),
		// requestHeaders: {
		// 	'X-CSRF-TOKEN': defaultHeaders['X-CSRF-TOKEN'],
		// },
	};
	
	// if ('toolbar' in config && config.toolbar in toolbarPresets) {
	// 	editorConfig.toolbarButtons = toolbarPresets[config.toolbar];
	// }
	
	return (
		<div className="py-4">
			<label className="block bold mb-2" htmlFor={ id }>
				{ label }
				{ dirty && <span className="ml-1 text-grey-light text-sm">(changed)</span> }
			</label>
			
			{ loaded && (
				<FroalaEditor
					tag="textarea"
					model={ value }
					config={ editorConfig }
					onModelChange={ onChange }
				/>
			) }
			{ (dirty && errors.length > 0) && (
				<ul className="list-reset mt-2">
					{ errors.map((error, i) => (
						<li key={ i } className="text-red mb-1">
							{ error }
						</li>
					)) }
				</ul>
			) }
		</div>
	);
};
