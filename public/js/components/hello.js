import React from 'react';

class HelloComponent extends React.Component {  
    render () {
        return <h1>Hello, {this.props.name}!</h1>;
    }
}

HelloComponent.defaultProps = { name: 'World' };

export default HelloComponent;  