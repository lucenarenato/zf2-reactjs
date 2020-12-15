
var React = require('react'); // importa a lib react
var reactDOM = require('react-dom'); // importa a lib react-dom

// using ES6 modules

import { Provider } from 'react-redux';
import { Route, Redirect } from 'react-router-dom';

// using CommonJS modules
const BrowserRouter = require("react-router-dom").BrowserRouter;
// const Route = require("react-router-dom").Route;
const Link = require("react-router-dom").Link;

import App from './MeuComponente';
//var MeuComponente = require('./MeuComponente.js');


ReactDOM.render(<App />, document.getElementById('app'));
// reactDOM.render(<h1>Hello World</h1>, document.getElementById('app'));
// ReactDOM.render(<App/>,document.getElementById('root'));

// reactDOM//.render(<App/>,document.getElementById('app'));
