'use strict';

require("babel/register");  
var React = require('react');  
var express = require('express');  
var path = require('path');  
var bodyParser = require('body-parser');

var app = express();  
app.use(bodyParser.json());  
app.use('/', function(req, res) {  
    try {
        var view = path.resolve('./views/' + req.query.module);
        var component = require(view);
        var props = req.body || null;
        res.status(200).send(
            React.renderToString(
                React.createElement(component, props)
            )
        );
    } catch (err) {
        res.status(500).send(err.message);
    }
});

app.listen(3000);  