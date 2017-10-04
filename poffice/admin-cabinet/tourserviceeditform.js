/**
 * Created by леново on 20.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actc from '../actions/action-creators'
import * as actl from '../actions/actions-list'
import * as act from '../actions/actions'
import {Field, reduxForm} from 'redux-form'
import Modal from 'react-bootstrap/lib/Modal';

const zerostate = {
    loaded: 0,
    description: "",
    title: "",
    price: 0,
    type: 0,
    tourid: 0,
    id:0
};

class TourServiceEditForm extends Component {

    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.state = zerostate;
    }

    handleSubmit(e) {


        e.preventDefault();
        console.log(e, this.state);
        if (parseInt(this.state.price)>0 && this.state.title.length>0 ) {

            let res = {};

            if (this.props.addmode) this.props.dispatch(actc.addTourServices(this.props.tourid, this.state))
            else this.props.dispatch(actc.updateTourServices(this.state.id, this.state));

            this.props.dispatch(actc.closeEditServiceWindow());

        }
        else {
            alert('Цена и название - обязательные поля. ');
        }


    }

    componentDidUpdate() {
        if (this.state.loaded == 0 && this.props.openwindow) {
            console.log('service updated:', this.props);
            if (this.props.openwindow) {
                this.setState({
                        loaded: 1,
                        title: this.props.editservice_data.title,
                        description: this.props.editservice_data.description,
                        price: this.props.editservice_data.price,
                        type: this.props.editservice_data.type,
                        id: this.props.editservice_data.id,
                        tourid: this.props.tourid,
                    }
                );
            }
        }

        if (this.state.loaded == 1 && !this.props.openwindow) this.setState(zerostate);


    }

    render() {
        let data = "";

        data = <form className="form-horizontal">
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="service_title">Название</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="service_title" placeholder="Название"
                           onChange={((event) => {
                               this.setState({title: event.target.value})
                           }).bind(this)}
                           value={this.state.title}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="service_descr">Описание услуги</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="service_descr" placeholder="Описание услуги"
                           onChange={((event) => {
                               this.setState({description: event.target.value})
                           }).bind(this)}
                           value={this.state.description}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="service_descr">Цена</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="service_descr" placeholder="Цена"
                           onChange={((event) => {
                               this.setState({price: event.target.value})
                           }).bind(this)}
                           value={this.state.price}/></div>
            </div>

        </form>
        return <div id="modaleditservice">
            <Modal bsStyle="primary" bsSize="large" animation={false} dialogClassName="newservice"
                   show={this.props.openwindow} onHide={(e) => this.props.dispatch(actc.closeEditServiceWindow())}>
                <Modal.Header closeButton>
                    <Modal.Title>Услуга</Modal.Title>
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
    //console.log("SEF", state);

    if (state) {
        return {
            openwindow: state.tourstate.editservice_window,
            tourid: state.tourstate.tur_services_main_id,
            editservice_id: state.tourstate.editservice_id,
            editservice_tourid: state.tourstate.editservice_tourid,
            editservice_data: state.tourstate.editservice_data,
            addmode: (state.tourstate.editservice_tourid > 0 && state.tourstate.editservice_id == 0)


        }
    }
}

export default connect(mapStateToProps)(TourServiceEditForm);

