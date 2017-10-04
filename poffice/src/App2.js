/**
 * Created by Zienko on 10.01.2017.
 */
import React, { Component } from 'react';
import {Link} from 'react-router';



class App2 extends Component {
    render() {
        return (
            <div className="App">
                <div className="App-header">
                    <h2>Компонент 2</h2>  <br />
<a href="34534">segfwe</a>
                </div>
                <p className="App-intro">
<Link to="http://rbc.ru">REACT-LINK</Link>
Компонент 2
                </p>
            </div>
        );
    }
}

export default App2;

