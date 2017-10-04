/**
 * Created by леново on 31.05.2017.
 */
import React, {Component} from 'react';
import Button from 'react-bootstrap/lib/Button';
import {connect} from 'react-redux';

import {LP_USERINFO, LP_ORDER, selectUserDetected, LP_USERDETECTED} from './actions/actions.js';


class YaPay extends Component {
    constructor(props) {
        super(props);
        //console.log('ya-form', this.props);
        this.handlePay = this.handlePay.bind(this);
        this.clickPay = this.clickPay.bind(this);
    }


    handlePay() {
        console.log(this.props);
    }

    clickPay(event) {
        alert(event.target.id + " " + this.input1.value + " " + this.input1.checked);
    }

    render() {
//console.log('ya-rend',this.props);



        let line = <div></div>;
        let phone = "";
        let email = "";
        let fio = "";
        let uid = 0;
        let customerName = "";
        if (this.props.show == 1) {
            line = <form onSubmit={this.handlePay} method="POST" action="https://money.yandex.ru/eshop.xml">
                <input type="hidden" name="shopId" value="141183"/>
                <input type="hidden" name="scid" value="102181"/>
                <input type="hidden" name="cps_phone" value={this.props.user_phone} size="64"/>
                <input type="hidden" name="custEmail" value={this.props.user_email} size="64"/>
                <input type="hidden" name="custName" value={this.props.user_name} size="64"/>
                <input type="hidden" name="orderDetails" value={this.props.descr} size="64"/>
                <input type="hidden" name="customerNumber" value={this.props.user_id} size="64"/>
                <input type="hidden" name="sum" value={this.props.sum}/>
                <Button type="submit" className="btn btn-primary">Перейти к оплате:{this.props.sum} руб.</Button>
            </form>
        }
        return line;
    }
}

function mapStateToProps(state) {
    //console.log("state-ya-redux", state);
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


export default connect(mapStateToProps)(YaPay);