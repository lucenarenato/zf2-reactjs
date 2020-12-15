// import React from 'react';
import ImageGallery from 'react-image-gallery'; // https://github.com/xiaolin/react-image-gallery
//import { render } from "react-dom";
import ReactDOM from 'react-dom';
import * as React from "react";
// Logger with default options
import logger from 'redux-logger';
import { Router, Route, browserHistory, IndexRoute  } from 'react-router';
import {Provider} from 'react-redux';
import {createStore} from 'redux';


//var React = require('react'); // importa a lib react
//var reactDOM = require('react-dom'); // importa a lib react-dom

//var createReactClass = require('create-react-class');
// var MeuComponente = createReactClass({
// var MeuComponente = React.createClass({
//   render: function(){
//     return (
      


//         <div>Teste</div>

//     );
//   }
// });

// module.exports = MeuComponente;
//const {createStore} = Redux;


// const store = createStore(
//   reducer,
//   applyMiddleware(logger)
// )
class ExampleComponent extends React.Component {
    render () {
      return(
        <div>
          <button {...this.props}>
            Click me!
          </button>
        </div>
      )
    }
  }
  
  class RenderComponent extends React.Component {
    clickHandler () {
      console.log('Click fired!')
    }
  
    render () {
      return(
        <ExampleComponent onClick={this.clickHandler.bind(this)} />
      )
    }
  }
  
  ReactDOM.render(
    <RenderComponent />, 
    document.getElementById('app')
    )
    
// console.log('const images');
// const images = [
//     {
//       original: 'https://picsum.photos/id/1018/1000/600/',
//       thumbnail: 'https://picsum.photos/id/1018/250/150/',
//     },
//     {
//       original: 'https://picsum.photos/id/1015/1000/600/',
//       thumbnail: 'https://picsum.photos/id/1015/250/150/',
//     },
//     {
//       original: 'https://picsum.photos/id/1019/1000/600/',
//       thumbnail: 'https://picsum.photos/id/1019/250/150/',
//     },
//   ];
//   console.log('MyGallery');
//   class MyGallery extends React.Component {
//     render() {
//       return <ImageGallery items={images} />;
//     }
//   }