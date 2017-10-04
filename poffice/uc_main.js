import React, {Component} from 'react';
import Panel from 'react-bootstrap/lib/Panel';
import Nav from 'react-bootstrap/lib/Nav';
import NavItem from 'react-bootstrap/lib/NavItem';
import Comm from './comm.js';
import UserInfo from './user_info.js';
import BillList from './billlist.js';
import ShowList from './ShowList.js';
import {connect} from 'react-redux';
import {loadUserInfo, LP_USERINFO} from './actions/actions.js';
import Acmain from './admin-cabinet/admincabinet.js';


/*
 *
 * Основное окно личного кабинета:
 * мои оплаты, билеты итп
 * */
class Ucmain extends Component {
    constructor(props) {
        super(props);
        //console.log("ucmain-",props);
        //alert(this.props.myid);
        let id = this.props.location_userid;
        let id2 = this.props.location_option1; //orderid
        if (typeof(id) == "undefined") id = 0;

        this.handleCl2 = this.handleCl2.bind(this);
        this.handlePay = this.handlePay.bind(this);

        this.state = {
            test: 10,
            showpay: 0,
            loaded:0,
            orders: [],  orderinfo: [],
            userid: id, showstatus: 0,
            user: [],uphone:"",uemail:"", uname:"",
            orderid: id2
        };
        //this.comm = new Comm();
    }

    componentDidUpdate() {

        if (this.props.NOV_STATUS == "AUTHORIZED" && this.props.user_id > 0 && this.state.loaded == 0) {
if (parseInt(this.state.userid)>0 && (this.state.userid!=this.props.user_id))
{
    this.setState({userid:this.props.user_id});
    //alert('Ошибка доступа. Вы будете перенаправлены в ваш личный кабинет');
    const path = "/palomnichestvo/users/" + this.props.user_id;
    window.location.assign(path);
} else {
            this.setState({
                loaded:1,
                    user_id: this.props.user_id,
                    uphone: this.props.user_phone,
                    uemail: this.props.user_email,
                    uname: this.props.user_name
                    //userid: this.props.user_id
                }
            );
        }
        }
    }

    handleCl2(id) {
//        console.log(id);
        this.setState({showstatus: id, showpay: 0})
    }

    handlePay() {
        if (this.state.showpay > 0) this.setState({showpay: 0, showstatus: 2});
        else this.setState({showpay: 1, showstatus: 2});
    }


    render() {
        console.log("uc-main-props", this.props);
        if (this.props.show == 0) return <span></span>
        let oList = "";
        let type = 0;
        let resL = "";

/*

         <NavItem eventKey={4} title="Item4" onClick={stateClick4}>Архив</NavItem>
         <NavItem eventKey={5} title="Item5" onClick={stateClick5}>Платежи</NavItem>

         */
        let stateClick0 = this.handleCl2.bind(this, 0);
        let stateClick1 = this.handleCl2.bind(this, 1);
        let stateClick3 = this.handleCl2.bind(this, 3);
        let stateClick4 = this.handleCl2.bind(this, 4);
        let stateClick5 = this.handleCl2.bind(this, 5);
        if (parseInt(this.state.showpay) == 1) oList = "";
        return (
            <div>
                <div className="container">
                    <h2>Личный кабинет</h2>
                    <Panel bsStyle="primary" style={{height: '100%'}} header="Личный кабинет">
                        <div className="row">
                            <div className="col-md-12">
                                <UserInfo />
                            </div>
                        </div>
                        <div className="row">
                            <div  className="col-md-3" >
                                <Nav bsStyle="pills" stacked activeKey={this.state.showstatus}>
                                    <NavItem eventKey={0} title="Item0" onClick={stateClick0}>Все</NavItem>
                                    <NavItem eventKey={1} title="Item1" onClick={stateClick1}>В процессе
                                        оформления</NavItem>
                                    <NavItem eventKey={2} title="Item2" onClick={this.handlePay}>Счета</NavItem>
                                    <NavItem eventKey={3} title="Item3" onClick={stateClick3}>Посадочные
                                        талоны</NavItem>
                                </Nav>
                            </div>
                            <div className="col-md-6">
                                <ShowList status={this.state.showstatus} />

                                <BillList show={this.state.showpay} />
                            </div>
                        </div>
                    </Panel>
                </div>
            </div>
        );
    }
}

function mapStateToProps(state) {
    //console.log("state-ya-redux - test",state);

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
            location_userid,
            location_option1,
            NOV_STATUS
        } = state.novstate;

       // console.log("state-main", user_id, eluser_id);

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
            location_userid,
            location_option1

        }
    }

}


export default connect(mapStateToProps)(Ucmain);