/**
 * Created by леново on 29.06.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment/moment.js';
import * as act from '../actions/actions.js';
import * as actm from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
class AdminSales extends Component {
    constructor(props) {
        super(props);
        this.state = {loaded: 0, rloaded: 0, showdate: 0, showstatus:0}
        this.showTour = this.showTour.bind(this);
    }


    componentWillMount() {
    }

    componentDidUpdate() {
        if (this.props.orders_status == 'loaded' && this.state.loaded == 0) {
            //console.log('orders loaded', this.props.orders_array);
            this.setState({loaded: 1, orders_array: this.props.orders_array});
        }

        if (this.props.show_reserve_date != this.state.showdate) {
            this.setState({show_reserve_info: this.props.show_reserve_info, showdate: this.props.show_reserve_date});
        }

        if (this.props.show_reserve_status == 'loaded' && this.state.rloaded == 0) {
            //  console.log('reserves loaded', this.props.show_reserve_info);
            this.setState({rloaded: 1, show_reserve_info: this.props.show_reserve_info});
        }
    }

    showTour(id) {
        this.props.dispatch(actc.showTourOrders(id));
    }

    render() {
        if (this.props.active_window != act.ACT_SALES && this.props.active_window != actm.WIN_SHOWTOUR) return <span></span>

        const oList = function (number) {
            //console.log(number);
            let mom = new moment(number.date);
            //  console.log(mom.format('dddd, MMMM DD YYYY'),moment(),mom.subtract(1,'months')<=moment(),mom.add(1,'months')>=moment());

            let res = <a style={{color: "black", cursor: "pointer"}}
                         onClick={(e) => this.showTour(number.id)}><span> {number.day} {number.month}
                - {number.title} ({number.cn}/{number.realmaxlimit}-{number.tourid}/{number.id})</span><br /></a>;
            if (mom >= moment().subtract(2, 'months') && mom <= moment().add(3, 'months') && number.actual == 1) return res; else return <span></span>;

        };
        const rList = function (number) {
            console.log("sedfesw", number);
            let delbutton = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.RESERVE_STATUS_DELETE, 'EDITRESERVEDATE', number.turdate));
            }} title="Удалить (только админ)"><span className="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </button>;
            let applbutton = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.RESERVE_STATUS_APPROVED, actm.EDITRESERVEDATE, number.turdate));
            }}><span title="Бронирование подтверждено" className="glyphicon glyphicon-ok" aria-hidden="true"></span>
            </button>;

            let paystatus="";
            if (number.payment_status==1) paystatus=<span style={{color:"green"}}>ОПЛАЧЕН</span>;
            let canclbutton = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.RESERVE_STATUS_CANCELLED, actm.EDITRESERVEDATE, number.turdate));
            }}><span title="Бронирование отменено паломником" className="glyphicon glyphicon-minus"
                     aria-hidden="true"></span></button>;
            let editbutton = <button onClick={(e)=>this.props.dispatch(actc.editUserDataWindow(number.uid))} title="Внести изменения"><span
                className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>;

            let go = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.PILIGRIM_GO, actm.EDITRESERVEDATE, number.turdate));
            }}>Поехал</button>
            let ngo = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.PILIGRIM_NGO, actm.EDITRESERVEDATE, number.turdate));
            }}>Не поехал</button>
            let bblack = <button onClick={(e) => {
                this.props.dispatch(actc.changeReserveStatus(number.urrid, actm.PILIGRIM_BADGO, actm.EDITRESERVEDATE, number.turdate));
            }}>Не поехал без предупреждения</button>
            let bblock = <button>В черный список</button>
       let resend = <span onClick={e=>this.props.dispatch(actc.sendCoupon(number.id))}>&nbsp;&nbsp;&nbsp;<button style={{paddingLeft:"10px"}}>Отправить посадочный купон</button></span>;
       let resendbill = <span onClick={e=>this.props.dispatch(actc.sendBill(number.id))}>&nbsp;&nbsp;&nbsp;<button style={{paddingLeft:"10px"}}>Отправить счет</button></span>
       let resendlog = <button >Отправить данные входа</button>


            let ulprofile = (number.eluser && parseInt(number.eluser) > 0) ?
                <a href={"https://elitsy.ru/profile/" + number.eluser} target="_blank">Пользователь </a> : <span></span>;
            let qres="";

            let coupon=<span><a target="_blank" href={"/palomnichestvo/printtour/"+number.uid+"/v/"+number.id}>Купон</a></span>
            let bill=<span><a target="_blank" href={"/palomnichestvo/bill/"+number.uid+"/v/"+number.id}>Счет</a></span>


            if (this.state.showstatus==0) qres= <div className="well">{number.id} {paystatus} {number.fio}({number.uid})
                - {number.uphone} {number.comment} {number.reservedate} {ulprofile} {coupon} {bill}{number.userid}<br/>{delbutton}{editbutton}{canclbutton}{applbutton} {go}{ngo}{bblack}{resend} {resendbill}</div>;
                if (this.state.showstatus==1) qres= <div>{number.id} {number.fio}
                - {number.uphone} {number.comment} {number.reservedate} {ulprofile} {number.userid}</div>;

                if (this.state.showstatus==2) qres= <div>{number.uphone}</div>;

            return qres;
        };
        let orList = "";
        let rrList = "";
        let tools = <span><button disabled="disabled">Редактировать</button><button onClick={(e)=>this.setState({showstatus:0})}>Все данные заказа</button><button onClick={(e)=>this.setState({showstatus:1})}>Список на посадку</button><button onClick={(e)=>this.setState({showstatus:2})}>Список телефонов</button></span>
        if (this.props.orders_array && this.props.active_window == act.ACT_SALES) {
            orList = this.props.orders_array.map(oList.bind(this));
        }
        if (orList != "") tools = "";
        if (this.props.show_reserve_info && this.props.active_window == actm.WIN_SHOWTOUR) {
            rrList = this.props.show_reserve_info.map(rList.bind(this));
        }
        return <div>{orList}{tools}{rrList}</div>

    }

}

const mapStateToProps = (state) => {
  //  console.log('stae', state);
    return {
        orders_array: state.orderslist.orders_array,
        orders_status: state.orderslist.orders_status,
        active_window: state.novstate.active_window,
        show_reserve_info: state.orderslist.show_reserve_info,
        show_reserve_status: state.orderslist.show_reserve_status,
        show_reserve_date: state.orderslist.show_reserve_date
    }
};


export default connect(mapStateToProps)(AdminSales)
