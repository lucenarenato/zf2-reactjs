// import React from 'react';
import ImageGallery from 'react-image-gallery';
//import { render } from "react-dom";
import ReactDOM from 'react-dom';
import * as React from "react";
// Logger with default options
import logger from 'redux-logger';
import { Router, Route, browserHistory, IndexRoute  } from 'react-router';
import {Provider} from 'react-redux';
import {createStore} from 'redux';
//import React, { Component } from 'react';

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
      alert('Hello!');
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
    
console.log('teste');