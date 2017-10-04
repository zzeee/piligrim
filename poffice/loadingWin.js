/**
 * Created by леново on 29.06.2017.
 */


import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actc from './actions/action-creators.js';
import * as act from './actions/actions-list.js';
import Modal from 'react-bootstrap/lib/Modal';
import Button from 'react-bootstrap/lib/Button';
import {closeWindow} from './actions/actions.js';
import YaPay from './YaPay.js';



class LoadingWin extends Component {

    constructor(props) {
        super(props);
        this.state={
            text:"Осуществляем бронирование...",
            txt2:"",
            corderid:0,
            elevent:0,
            status:0
        };
    this.handleGo=this.handleGo.bind(this);
    this.handleEvent=this.handleEvent.bind(this);
    }

    handleGo()
    {
        const path = "/palomnichestvo/users/" + this.props.user_id;
        window.location.assign(path);
    }

    handleEvent()
    {
        let path="https://elitsy.ru/events/"+this.state.elevent;
        window.location.assign(path);
    }

    componentDidUpdate() {
        //console.log("status:", this.state.corderid, this.props.orderid);
        if (this.props.tourorderstatus == act.NOV_ORDERSUCCESS && this.state.corderid!=this.props.orderid) {

            let txtline=(this.props.orderid?"Номер вашего заказа: "+this.props.orderid:"");
            txtline+=(this.props.datenum!=""? " ,дата поездки:"+this.props.datenum:"");
            //console.log('успех', txtline, this.props);
            this.setState({status:10, elevent:this.props.elevent, text:"Успешно забронировано!", txt2:txtline, corderid: this.props.orderid})
        }
    }

    render() {
      if (!this.props.active_window) return <span></span>
      if (this.props.active_window!=act.WIN_LOADING) return <span></span>
      //console.log("loq", this.props.tourorderstatus, this.state);
      let  acti="";
        let qrt=<span><br /><div className="progress">
            <div className="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style={{width: "100%"}}>
                </div>
        </div></span>

        let elbut="";

        if (this.state.status==10) {
            if (this.props.elevent) {
                if (parseInt(this.props.elevent)>0)
                elbut=<button className="btn btn-primary" onClick={this.handleEvent}>Перейти в мероприятие!</button>
            }


            acti = <table  style={{width:"100%"}}>
                <tr>
                    <td className="col-md-12">
                        <div className="col-md-3">
                            <button onClick={((e) => {
                                this.props.dispatch(closeWindow())
                            }).bind(this)} className="btn btn-primary">Вернуться на сайт
                            </button>
                        </div>
                        <div className="col-md-3"><YaPay show="1" sum={this.props.prepaysum}   descr={this.props.paymentline}/></div>
                        <div className="col-md-3">{elbut}</div>
                    </td>
                </tr>
            </table>;
            qrt = "";
        }
      //console.log("AAAA",acti);
        return <div id="modalload">
            <Modal show="1" bsStyle="primary" bsSize="large" animation={false} dialogClassName="loadingwindow"
                    >
                <Modal.Body>
                    <h1>{this.state.text}{qrt}</h1><br /><h3>{this.state.txt2}</h3>
                  <div>{acti}</div>
                </Modal.Body></Modal></div>
                }
}



function mapStateToProps(state) {
  //  console.log("loading..",state, state.novstate.orderid);

    if (state) {
        const {
            toursList
        } = state.novstate

        let title="";
        if (toursList && toursList.tourdata && toursList.tourdata.title) title=toursList.tourdata.title;
        // console.log("state-main", user_id, eluser_id);

        return {
            active_window:state.novstate.active_window,
            tourorderstatus:state.novstate.tourorderstatus,
            tourid:state.novstate.tourid,
            elevent:state.novstate.elevent,
            orderid:state.novstate.orderid,
            datenum:state.novstate.order_date_num,
            prepaysum:state.novstate.prepaysum,
            paymentline:state.novstate.paymentline,
            user_id:state.novstate.user_id,
            title
        }
    }

}


export default connect(mapStateToProps)(LoadingWin)

