/**
 * Created by леново on 22.06.2017.
 */
import React, {Component} from 'react';


class NoAuth extends Component {
    constructor(props) {
        super(props);

    }

    render ()
    {
         return <button onClick={()=>window.location.assign("/?next="+this.props.loc)}className="btn btn-primary">Вход на сайт Елицы</button>
    }
}

export default NoAuth;
