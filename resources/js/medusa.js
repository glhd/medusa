import React from 'react';
import ReactDOM from 'react-dom';
import { hot } from 'react-hot-loader/root'
import App from './components/App';

const HotApp = hot(App);

const root = document.getElementById('medusa');

const config = JSON.parse(root.dataset.config);
const old = JSON.parse(root.dataset.old);
const errors = JSON.parse(root.dataset.errors);
const existing = JSON.parse(root.dataset.existing);

if ('development' === process.env.NODE_ENV) {
	console.log({ config, old, errors, existing });
}

ReactDOM.render(<HotApp config={config} existing={existing} old={old} server_errors={errors} />, root);
