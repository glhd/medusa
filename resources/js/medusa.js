import React from 'react';
import ReactDOM from 'react-dom';
import { hot } from 'react-hot-loader/root'
import App from './App';

import '../css/medusa.css';

const HotApp = hot(App);

const root = document.getElementById('medusa');

if ('development' === process.env.NODE_ENV) {
	console.log(window.__MEDUSA__);
}

ReactDOM.render(<HotApp {...window.__MEDUSA__} />, root);
