/**
 * Created by a.zienko on 19.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actc from '../actions/action-creators'
import * as actl from '../actions/actions-list'
import * as act from '../actions/actions'
import {Field, reduxForm} from 'redux-form'
import Modal from 'react-bootstrap/lib/Modal';
import DatePicker from 'react-datepicker';
import moment from 'moment';

import 'react-datepicker/dist/react-datepicker.css';

class DateEditForm extends Component {

    constructor(props) {
        super(props);
        //this.handleSubmit = this.props.handleSubmit;
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        //console.log("edit date", props);
        this.state = {
            loaded: 0,
            comment: "",
            realmaxlimit: 0,
            elevent: 0,
            prepay: 500,
            pricefull: 0,
            vkevent: 0,
            owner: 0,
            actual: false,
            date: moment()
        }
    }

    handleChange(e) {
        //console.log(e.format("MM-DD-YYYY"));
        this.setState({date: e})
    }

    handleSubmit(e) {
        e.preventDefault();
        //console.log(e, this.state);
        let res = {};
        if (this.state.comment && this.state.comment.length > 0) res["comment"] = this.state.comment; else res["comment"] = "";
        if (this.state.realmaxlimit) res["realmaxlimit"] = this.state.realmaxlimit; else res["realmaxlimit"] = 0;
        if (this.state.vkevent) res["vkevent"] = this.state.vkevent; else res["vkevent"] = 0;
        if (this.state.elevent) res["elevent"] = this.state.elevent; else res["elevent"] = 0;
        if (this.state.prepay) res["prepay"] = this.state.prepay; else res["prepay"] = 0;
        if (this.state.pricefull) res["pricefull"] = this.state.pricefull; else res["pricefull"] = 0;
        if (this.state.actual) res["actual"] = this.state.actual; else res["actual"] = false;
        if (this.state.owner) res["owner"] = this.state.owner; else res["owner"] = 0;
        if (this.state.date) res["date"] = this.state.date.format("YYYY-MM-DD");
        console.log("SAVING DATE:",res);
        if (!this.props.addmode) {
            res["dateid"] = this.props.editdate_id;
            this.props.dispatch(actc.updateTourDate(this.props.editdate_id, res));
        }

        else {
            res["tourid"] = this.props.editdate_tourid;
            this.props.dispatch(actc.addTourDate(this.props.editdate_tourid, res, this.props.userid));
        }

        this.props.dispatch(actc.closeEditDateWindow());
        return false;
    }

    componentDidUpdate() {
        if (this.state.loaded == 0 && this.props.openwindow) {
             console.log('updated:', this.props);
            if (this.props.openwindow) {
                this.setState({
                        loaded: 1,
                        comment: this.props.editdate_data.comment,
                        realmaxlimit: this.props.editdate_data.realmaxlimit,
                        elevent: this.props.editdate_data.elevent,
                        vkevent: this.props.editdate_data.vkevent,
                        pricefull: this.props.editdate_data.pricefull ? this.props.editdate_data.pricefull : 0,
                        prepay: this.props.editdate_data.prepay ? this.props.editdate_data.prepay : 500,
                        actual: this.props.editdate_data.actual ? this.props.editdate_data.actual==1 : false,
                        owner: this.props.editdate_data.owner ? this.props.editdate_data.owner : 0,
                        date: moment(this.props.editdate_data.date)
                    }
                );
            }
        }

        if (this.state.loaded == 1 && !this.props.openwindow) {
            this.setState({
                    loaded: 0,
                    comment: "",
                    realmaxlimit: 0,
                    elevent: 0,
                    vkevent: 0,
                    pricefull: 0,
                    actual: 0,
                    prepay: 500,
                    owner: 0,
                    date: moment()
                }
            );

        }


    }


    render() {
        let data = "", organizator="", lline="";

        console.log('actual:', this.state.actual);
        if (!(this.props.isadmin || this.props.istourmaster)) return <span></span>;

        if (this.props.organizators && this.props.organizators.length>0)
        {
            lline=this.props.organizators.map((e)=> {
                return <option value={e.id}>{e.name}</option>
            });
        }

        organizator=<div className="form-group">
            <label className="col-sm-4 control-label" htmlFor="organizer">Организатор</label>
            <div className="col-sm-8">
                <select id="organizer" value={this.state.owner} onChange={((event) => {
                    this.setState({owner: (isNaN(parseInt(event.target.value)) ? 0 : parseInt(event.target.value))})
                }).bind(this)}><option value="0">нет</option>{lline}</select>
            </div>
        </div>;
                console.log(organizator);

        if (!this.props.isadmin) organizator="";

        data = <form className="form-horizontal">
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="date_comment">Комментарий к дате поездки</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="date_comment" placeholder="Комментарий"
                           onChange={((event) => {
                               this.setState({comment: event.target.value})
                           }).bind(this)}
                           value={this.state.comment}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="date_amount">Предполагаемое число мест в
                    группе (число)</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="date_amount" placeholder="число"
                           onChange={((event) => {
                               let q = parseInt(event.target.value);
                               this.setState({realmaxlimit: (isNaN(q) ? 0 : q)})
                           }).bind(this)}
                           value={this.state.realmaxlimit}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="date_elevent">Номер мероприятия на странице
                    Елицы (число)</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="date_elevent" placeholder="123"
                           onChange={((event) => {
                               this.setState({elevent: (isNaN(parseInt(event.target.value)) ? 0 : parseInt(event.target.value))})
                           }).bind(this)}
                           value={this.state.elevent}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="date_amount">Номер мероприятия на странице
                    VK ((число))</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="date_vk" placeholder="123"
                           onChange={((event) => {
                               this.setState({vkevent: (isNaN(parseInt(event.target.value)) ? 0 : parseInt(event.target.value))})
                           }).bind(this)}
                           value={this.state.vkevent}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="price_full">Полная стоимость поездки</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="price_full" placeholder="123"
                           onChange={((event) => {
                               this.setState({pricefull: (isNaN(parseInt(event.target.value)) ? 0 : parseInt(event.target.value))})
                           }).bind(this)}
                           value={this.state.pricefull}/>

                </div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="prepay">Предоплата</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="prepay" placeholder="123"
                           onChange={((event) => {
                               this.setState({prepay: (isNaN(parseInt(event.target.value)) ? 0 : parseInt(event.target.value))})
                           }).bind(this)}
                           value={this.state.prepay}/>

                </div>
            </div><div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="published">Опубликован</label>
                <div className="col-sm-1">
                    <input type="checkbox" className="form-control " id="published" placeholder="123"
                           onChange={((event) => {
                               this.setState({actual: !this.state.actual})
                           }).bind(this)}
                           checked={this.state.actual}/>
                </div>
            </div>{organizator}

            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="date_vk">Дата поездки</label>
                <div className="col-sm-8">

                    <DatePicker
                        selected={this.state.date}
                        onChange={this.handleChange}
                    /></div>
            </div>


        </form>;


        return <div id="modaleditdate">
            <Modal bsStyle="primary" bsSize="large" animation={false} dialogClassName="newdate"
                   show={this.props.openwindow} onHide={(e) => this.props.dispatch(actc.closeEditDateWindow())}>
                <Modal.Header closeButton>
                    <Modal.Title>Дата</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {data}
                </Modal.Body>
                <Modal.Footer>
                    <button onClick={this.handleSubmit} className="btn btn-primary btn-lg">Сохранить</button>
                </Modal.Footer>
            </Modal>
        </div>


    }

}

function mapStateToProps(state) {
    console.log('def:', state);
    if (state) {
        return {
            status: state.novstate.NOV_STATUS,
            userid: state.novstate.user_id,
            isadmin: (state.novstate.user_isadmin == 1),
            istourmaster: state.novstate.user_istourmaster,
            organizators: state.tourstate.organizers_list,
            openwindow: state.tourstate.editdate_window,
            editdate_id: state.tourstate.editdate_id,
            editdate_tourid: state.tourstate.editdate_tourid,
            editdate_data: state.tourstate.editdate_data,
            addmode: (state.tourstate.editdate_tourid > 0 && state.tourstate.editdate_id == 0)
        }
    }
}

export default connect(mapStateToProps)(DateEditForm);

