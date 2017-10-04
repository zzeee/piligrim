/**
 * Created by леново on 04.06.2017.
 *
 * Загружает заказы юзера. Выводит чере UCorder
 */

import React, {Component} from 'react';
import Comm from './comm.js';
import YaPay from './YaPay.js';
import Ucorder from './uc_order.js';
import {connect} from 'react-redux';
import {novSaveUserInfo, NOV_SAVEUSERINFO}  from  './actions/actions.js';


class ShowList extends Component {
    constructor(props) {
        super(props);
        this.comm = new Comm();
        this.state = {
            showstatus: 0,
            filterstatus: true,
            orders: [], loaded: 0
        }
    }

    componentDidUpdate() {
        if (this.props.NOV_STATUS == "AUTHORIZED" && this.props.user_id > 0 && this.state.loaded == 0) {
            this.setState({loaded: 1});
            /*
             * ПЕРЕНЕСТИ ЗАГРУЗКУ СПИСКА ЗАКАЗОВ В САГУ(!)
             * ЖЕЛАТЕЛЬНО АСИНХРОННО ЗАГРУЗИТЬ СРАЗУ ПОСЛЕ ЗАПУСКА
             *
             * */
            let okf = function (rt) {
                //console.log("RRRRRRRRRRRRRRRRRR");
                this.setState({orders: rt});
            };
            if (parseInt(this.props.user_id) > 0) this.comm.getUserInfo(this.props.user_id, okf.bind(this));
        }
    }

    render() {
        //console.log(this.props,' from sholist!!!!!!!!!!!!!!!!!!');
        if ((parseInt(this.props.user_id) == 0) || (this.state.orders.length == 0)) return <div>Информация
            загружается</div>;
        if (this.props.status == 2) return <span></span>;
        let oList = "";
        if (this.state.orders.length > 0) oList = this.state.orders.map(function (line) {
            let res = <div className="row" key={line.order_id}><Ucorder status={this.props.status} line={line} /></div>;
            if ((this.state.filterstatus && this.props.status != 0 && this.props.status != line.status) || this.props.status == 2) res = "";
            return res;
        }.bind(this));
        return <div>{oList}</div>;
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


export default connect(mapStateToProps)(ShowList);
