/**
 * Created by zzeee on 31.03.2017.
 */

import React, {Component} from 'react';
import Alert from 'react-bootstrap/lib/Alert';
import Button from 'react-bootstrap/lib/Button';

class AlertDismissable extends Component {
    constructor(props) {
        super(props);
        let av=true;
        if (!isNaN(window.avisible)) { av=window.avisible; window.avisible=false;}
        this.state = {alertVisible: av, title:this.props.title,txt:this.props.txt };
        this.handleAlertDismiss=this.handleAlertDismiss.bind(this);
        this.handleAlertShow=this.handleAlertShow.bind(this);
        }

    handleAlertDismiss() {
        this.setState({alertVisible: false});
        window.avisible=false;
    }

    handleAlertShow() {
        this.setState({alertVisible: true});
        window.avisible=true;
    }

    render() {
        if (this.state.alertVisible) {
            window.avisible=true;
            return (
                <Alert bsStyle="warning" /*onDismiss={this.handleAlertDismiss}*/>
                    <h4>{this.state.title}</h4>
                    <p>{this.state.txt}</p>
                </Alert>
            );
        }

        return (<div></div>);
    }

}


export default AlertDismissable;