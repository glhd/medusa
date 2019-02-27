import React from 'react';
import ReactDOM from 'react-dom';
import { hot } from 'react-hot-loader/root'
import App from './components/App';

const HotApp = hot(App);

const root = document.getElementById('medusa');
const config = JSON.parse(root.dataset.config);

ReactDOM.render(<HotApp config={config} />, root);
