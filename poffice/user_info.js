/**
 * Created by Zienko on 03.06.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';
import {novSaveUserInfo, NOV_SAVEUSERINFO}  from  './actions/actions.js';

class UserInfo extends Component {
    constructor(props) {
        super(props);
        this.state = {
            userinfo: [], uphone: "", uemail: "", uname: "", userid: 0, newuserinfo: []
        }
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        let okf = function (rt) {
            let qrt = Array.from(rt);
            if (qrt.length > 0)
                this.setState({userinfo: qrt[0], uphone: qrt[0].phone, uemail: qrt[0].email, uname: qrt[0].name});
        };
    }

    componentDidUpdate() {
        if (this.props.NOV_STATUS == "AUTHORIZED" && this.props.user_id > 0 && this.state.userid == 0) {
            this.setState({
                    uphone: this.props.user_phone,
                    uemail: this.props.user_email,
                    uname: this.props.user_name,
                    userid: this.props.user_id
                }
            );
        }
    }

    handleSubmit(event) {
        event.preventDefault();
        this.props.dispatch(novSaveUserInfo(this.state.uname, this.state.uphone, this.state.uemail, this.props.user_id));
    }

    render() {
        return <div className="panel panel-primary">
            <div className="panel-title"> Изменение регистрационных данных:</div>
            <div className="panel-body">
                <form className="form-inline" onSubmit={this.handleSubmit}>
                    <div className="row">
                        <div className="input-group">
                            <span className="input-group-addon" id="basic-addon1">Телефон</span>
                            <input type="text" className="form-control" aria-describedby="basic-addon1"
                                   value={(this.state.uphone)}
                                   onChange={(event) => {
                                       this.setState({uphone: event.target.value});
                                   } }
                            />
                        </div>

                        <div className="input-group">
                            <span className="input-group-addon" id="basic-addon2">E-mail</span>
                            <input className="form-control" aria-describedby="basic-addon2" type="text"
                                   value={(this.state.uemail)}
                                   onChange={(event) => {
                                       this.setState({uemail: event.target.value});
                                   }}
                            /></div>
                        <div className="input-group ">
                            <span className="input-group-addon" id="basic-addon3">Как вас зовут</span>
                            <input className="form-control" aria-describedby="basic-addon3" type="text"
                                   value={(this.state.uname)}
                                   onChange={(event) => {
                                       this.setState({uname: event.target.value});
                                   } }
                            /></div>
                        <input type="submit" className="btn btn-default" value="Сохранить"/></div>
                </form>
            </div>
        </div>
    }
}

function mapStateToProps(state) {
    //console.log("user info state-main", state);
    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            user_email,
            user_id,
            user_name,
            user_phone,
            NOV_STATUS
        } = state.novstate
        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            NOV_STATUS,
            user_email,
            user_id,
            user_name,
            user_phone,
        }
    }
}

export default connect(mapStateToProps)(UserInfo);
