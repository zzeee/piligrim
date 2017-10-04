/**
 * Created by леново on 09.08.2017.
 */
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
    downloaded: "",
    url: "",
    type: 1,
    pointid: 0,
};

class AddInfoEdit extends Component {

    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.state = zerostate;
    }

    handleSubmit(e) {
        e.preventDefault();
        //console.log(this.state);
        let res=[];
        res.push({addmode:this.props.addmode,title:this.state.title, description:this.state.description, url:this.state.url, type:this.state.type, id:this.state.id, pointid:this.props.point_id });

        /*res["title"]=this.state.title;
        res["description"]=this.state.description;
        res["url"]=this.state.url;
        res["type"]=this.state.type;
        res["id"]=this.state.id;
        res["pointid"]=this.props.point_id;
*/
        console.log("preres",res);
        this.props.dispatch(actc.savePointAddInfo(res));

        /*
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
         */
    }
    componentDidUpdate() {
        if (this.state.loaded == 0 && this.props.openwindow) {
            if (this.props.openwindow) {
                this.setState({
                        loaded: 1,
                        title: this.props.point_data.title,
                        description: this.props.point_data.description,
                        url: this.props.point_data.url,
                        type: this.props.point_data.type,
                        downloaded: this.props.point_data.downloaded,
                        id: this.props.point_data.id
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
                <label className="col-sm-4 control-label" htmlFor="service_descr">Описание</label>
                <div className="col-sm-8">
                    <textarea  className="form-control " id="service_descr" placeholder="Описание"
                           onChange={((event) => {
                               this.setState({description: event.target.value})
                           }).bind(this)}
                           value={this.state.description}/></div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="service_descr">Тип</label>
                <div className="col-sm-8"><select
                    value={this.state.type} onChange={((event) => {
                    this.setState({type: event.target.value})
                }).bind(this)}
                ><option value="1">Ссылка (закрытая)</option><option value="2">Публичная ссылка</option><option value="12">Реклама такси</option><option value="13">Реклама гостиница</option><option value="14">Реклама гид</option><option value="15">Реклама иное</option></select>
                </div>
            </div>
            <div className="form-group">
                <label className="col-sm-4 control-label" htmlFor="service_descr">URL</label>
                <div className="col-sm-8">
                    <input type="text" className="form-control " id="service_descr" placeholder="URL ссылки"
                           onChange={((event) => {
                               this.setState({url: event.target.value})
                           }).bind(this)}
                           value={this.state.url}/></div>
            </div>

        </form>;
        return <div id="modaleditaddinfo">
            <Modal bsStyle="primary" bsSize="large" animation={false} dialogClassName="newservice"
                   show={this.props.openwindow} onHide={(e) => this.props.dispatch(actc.closeEditAddInfoWindow())}>
                <Modal.Header closeButton>
                    <Modal.Title>Услуга{this.props.addmode}</Modal.Title>
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

    let pointid = parseInt(state.points.edit_points_ainfo_edit_id);
    let pointarr = Array.from(state.points.edit_points_ainfo_data);
    let qt = pointarr.filter(((e) => {if (parseInt(e.id)===pointid) return true;    }).bind(this))
    let arr2=[];
    if (qt.length>0) arr2=qt[0];
    let addmode=state.points.edit_points_ainfo_loaded_id!=0 && qt.length==0;
    console.log('addmode:',addmode);

    if (state) {
        return {
            openwindow: state.points.edit_points_ainfowindow,
            point_id: state.points.edit_points_ainfo_loaded_id,
            point_all_data: state.points.edit_points_ainfo_data,
            point_data:arr2, addmode
        }
    }
}

export default connect(mapStateToProps)(AddInfoEdit);

