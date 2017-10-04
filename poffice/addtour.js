/**
 * Created by Zienko on 22.02.2017.
 */
import React, {Component} from 'react';
import Modal from 'react-bootstrap/lib/Modal';
import Button from 'react-bootstrap/lib/Button';
import Form from 'react-bootstrap/lib/Form'
import Comm from './comm.js';
import TourCard from './tourcard.js';
import HotelCard from './hotelcard.js';
import Alert from 'react-bootstrap/lib/Alert';
import {connect} from 'react-redux';

import * as act from './actions/actions.js'
import * as actc from './actions/action-creators.js'

/*

 Стартовая форма окна резервирования тура.
 Пользователь добавляет тур.
 После чего имеет возможность его оплатить.

 Особенности:
 к туру необходимо догрузить:
 -даты
 -дополнительные сервисы
 -название итп.


 По сути должна быть компоненты:
 -подгрузки информации о туре
 -проверки заполнения форм и отправки на сервер
 -обработки результата

 */

function AlertWindow(props) {
    let res = "";
    let qres = "";
    if (props.warning_phone == 1) qres = qres + " телефон";
    if (props.warning_fio == 1) qres = qres + " ФИО";
    //  if (props.warning_phone==1) qres="телефон";
    // if (props.warning_phone==1) qres="телефон";

    if (props.show == 1) return <Alert bsStyle="warning">
        <strong>Ошибка заполения!</strong>&nbsp;Ошибка заполнения полей:{qres}    </Alert>;
    else return <div></div>;

}

class Addtour extends Component {

    constructor(props) {
        super(props);
        this.open = this.open.bind(this);
        //this.close = this.close.bind(this);
        //this.addtour = this.addtour.bind(this);
        this.addhotelreserve = this.addhotelreserve.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleSubmitHotel = this.handleSubmitHotel.bind(this);
        let userid = this.props.userid;
        this.comm = new Comm();

        this.state = {
            showModal: (this.props.active_window == act.WIN_TOUR_RESERVE), tourid: 0,
            userid: window.userid, alertW: 0,
            tdate: props.tdate,
            warning_fio: 0,
            warning_phone: 0,
            elparams: this.props.elparams,
            tloaded: 0,
            hid: 0,
            type: 0,
            show: 0,
            loaded: 0,
            loadedid: 0,
            warning_payment: 0,
            newuserid: userid
        }
        //this.onZClose = this.onZClose.bind(this);
    }
/*
    close() {
        this.setState({showModal: false, tloaded: 0});
        //console.log('close', this.state);
    }

*/
    open() {
        this.setState({showModal: true});
    }

    alertW() {
        this.setState({alertW: 1});
    }

    componentWillUpdate() {
        if (this.props.tourid && this.props.tourid != this.state.loadedid) {
            //console.log(`checkload${this.props.tourid}`, this.props.loading);
            if (this.props.loading != "loading") this.props.dispatch(act.novTourList(this.props.tourid));
            this.setState({loadedid: this.props.tourid})
        }

    }

    componentDidUpdate() {

        if (this.props.active_window == act.WIN_TOUR_RESERVE && this.props.tourid && this.state.show != true) {
            this.setState({loaded: 1, show: true, showModal: true, tourid: this.props.tourid});
        }

        if (this.props.active_window == act.WIN_NOWINDOW && this.state.show != 0) {
            this.setState({show: 0});
        }
        //if (this.props.tourid && this.props.tourid!=this.state.loadedid)
        //{
//            console.log(`checkload${this.props.tourid}`);
        //          this.props.dispatch(act.novTourList(this.props.tourid));
        //        this.setState({loadedid:this.props.tourid})
        //  }

    }

    handleSubmitHotel(param) {
        //console.log(param);
        this.comm.sendHotelOrder(param, (req) => {
            let zstr = "Заказ был успешно отправлен. В ближайшее время с вами свяжутся для подтверждения заказа.";
            if (this.props.NOV_STATUS == 'AUTHORIZED') zstr = zstr + ' Вы можете найти посадочные купоны в разделе "мои билеты"';
            this.props.onZChange();

        });
    }




    handleSubmit(event) {
        function pNul(vk)
        {
        if (isNaN(parseInt(vk))) return 0; else return parseInt(vk);

        }

        event.preventDefault(event);
        let req = [];
        let inp = [];
        const pq = document.getElementById("adddata");

        let date = pq.elements["chooseDate"].value;
        let elevent = pq.elements["elevent"].value;

        let paymentType = 0;
        let addservices = [];
        for (let i = 0; i < pq.elements.length; i++) {
            let qpt = pq.elements[i];
            if (qpt.className == "addNam" && qpt.checked) {
                addservices.push(qpt.id);
            }
            if (qpt.type == "radio" && qpt.checked) {
                paymentType = qpt.id;
            }
            if (qpt.className == "inpfam" && qpt.value != "") inp.push(qpt.value);
        }
        req = {};
        req["userid"] = parseInt(this.props.user_id);
        req["elevent"] = parseInt(pq.elements["elevent"].value);
        req["tourid"] = parseInt(this.props.tourid);//pq.elements["tourid"].value;
        req["comment"] = pq.elements["txtarea"].value;
        req["phonenum"] = pq.elements["phnum"].value;
        if (addservices.length > 0) req["addservices"] = addservices;
        req["paymenttype"] = paymentType;
        req["prepay"] = pq.elements["prepay"].value;
        req["totalpay"] = pq.elements["totalpay"].value;

        if (paymentType == 0) {
            this.setState({warning_payment: 1});
            this.alertW();
            return false;
        } else this.setState({warning_payment: 0});
        if (req["phonenum"] == "") //TODO тут нужно будет сделать проверку корректности номера, а также копирование номера из профиля
        {
            this.setState({warning_phone: 1});
            this.alertW();
            return false;
        } else this.setState({warning_phone: 0});
        req["dateid"] = date;
        req["inpfam"] = inp;
        req["elparams"] = [];// this.props.elparams;
        try {
            req["vkuser_id"]=this.props.vkuser_id;
            req["vkid"] = pNul(pq.elements["vkid"].value);
            req["emid"] = pq.elements["emid"].value;
            req["oelid"] = pq.elements["oelid"].value;
            req["nrid"] = pq.elements["nrid"].value;
            req["email"] = pq.elements["email"].value;
            req["elid"] = this.props.eluser_id;
            req["elname"] = this.props.eluser_name;
        }
        catch (e) {
            //console.log(e);
        }
        //console.log("order", req);
        this.props.dispatch(actc.novSendOrder(req));
    }


    addhotelreserve(i) {
        //this.setState({hid: i});
        //this.setState({type: 1});
        //this.open();
    }


    render() {
        if (this.props.active_window != act.WIN_TOUR_RESERVE && this.props.active_window != act.WIN_HOTEL_RESERVE) return <span></span>
        let qt;
        if (this.props.active_window == act.WIN_TOUR_RESERVE) qt =
            <TourCard warning_payment={this.state.warning_payment}
                      warning_phone={this.state.warning_phone}
                      warning_fio={this.state.warning_fio}
                      tdate={this.state.tdate}/>
        if (this.props.active_window == act.WIN_HOTEL_RESERVE) qt =
            <HotelCard onSend={this.handleSubmitHotel} hid={this.props.ident}/>;
        return (
            <div id="modal">
                <Modal bsStyle="primary" bsSize="large" animation={false} dialogClassName="reservewindow"
                       show={this.state.show} onHide={(e) => {
                    this.setState({loaded: 0, tloaded: 0});
                    this.props.dispatch(act.closeWindow())
                }}>
                    <Modal.Header closeButton>
                        <Modal.Title>Бронирование{(this.props.isadmin?" - admin":"")}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form id="adddata" action="/palomnichestvo/backpost/addorder" className="form-horizontal"
                              method="POST" onSubmit={this.handleSubmit}>
                            {qt}
                            <AlertWindow warning_phone={this.state.warning_phone} warning_fio={this.state.warning_fio}
                                         show={this.state.alertW}/>
                        </Form>
                    </Modal.Body>
                </Modal>

            </div>
        );
    }
}

function mapStateToProps(state) {
    //console.log("ADD+TOUR", state);

    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            active_window,
            orderstatelist,
            tourid,
            tourList,

            user_email,
            user_id,
            user_name,
            user_phone,
            NOV_STATUS
        } = state.novstate

        //  console.log("state-main", user_id, eluser_id);

        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            tourList,
            active_window,
            NOV_STATUS,
            user_email,
            user_id,
            user_name,
            user_phone,
            loading: state.novstate.tours_status,
            vkuser_id:state.novstate.vkuser_id,
            isadmin:(state.novstate.user_isadmin==1)

        }
    }
}

export default connect(mapStateToProps)(Addtour);

