/**
 * Created by леново on 05.08.2017.
 */

/*Не факт что нужно*/

/**
 * Created by леново on 21.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actc from '../actions/action-creators'
import * as actl from '../actions/actions-list'
import * as act from '../actions/actions'
import {Field, reduxForm} from 'redux-form'
import Modal from 'react-bootstrap/lib/Modal';
import PicEdit from './picEdit.js';
import ImageFileSelector from "react-image-select-component";


const zerostate = {
    loaded: 0,
    thumb: "",
    gallery: "",
    source: "",
    comment: "",
    tourid: 0,
    sorder: 0,
    id: 0
};


function SuperPhotoWindow(props)
{
    return <div className="col-md-6"><PicEdit
        ControlLocation="top" WidthControl="no" HeightControl="no" itype="gallery"
        BorderRadiusControl="no" width="2000" height="300"
        onSave={props.onSave}
        src={props.src}/></div>
}

class SuperPhotoEditForm extends Component {

    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.copyData = this.copyData.bind(this);
        this.handleSavePic = this.handleSavePic.bind(this);
        this.state = zerostate;
        this.selectImage = this.selectImage.bind(this);
        //this.setGallRef=this.setGallRef.bind(this);
        //this.setThumbRef=this.setThumbRef.bind(this);
    }


    handleSubmit(e) {
        this.props.dispatch(actc.savePhotoComment(this.props.editphoto_id ? this.props.editphoto_id : 0, this.state.comment, this.state.sorder));
        this.props.dispatch(actc.getTourPhoto(this.props.tourid));
        this.props.dispatch(actc.closeEditPhotoWindow())

//        console.log("st", e);
    }

    handleSavePic(img, param1, param2) {
        console.log("st2", param1, param2,this.props.tourid, this.props.pointid);
        //   console.log(...param2);
        this.props.dispatch(actc.savePhoto(img, param1, param2, this.props.tourid, this.props.pointid));
    }


    copyData() {
        this.setState({thumb: this.props.source, gallery: this.props.source});
        this.props.dispatch(actc.spreadPhoto("thumb"));
        this.props.dispatch(actc.spreadPhoto("gallery"));
    }


    componentDidUpdate() {
        //console.log("checkspread",this.props, this.state);

        if (this.state.loaded === 0 && this.props.openwindow) {
            //  console.log('photo updated:!!!!!!!!!!!!!', this.props);
            if (this.props.openwindow) {
                let photoname = this.props.editphoto_data.name;
                //console.log('photoupp', photoname);
                this.setState({
                        loaded: 1,
                        comment: this.props.editphoto_data.comment,
                        main_photo: this.props.editphoto_data.name,
                        sorder: this.props.editphoto_data.sorder
                    }
                );
            }
        }

        if (this.state.loaded === 1 && !this.props.openwindow) this.setState(zerostate);
        if ((this.state.thumb == "" && this.props.thumb && this.props.thumb != "") || (this.state.thumb != this.props.thumb)) {

            //    console.log("SPREADED");
            this.setState({thumb: this.props.thumb});
        }
        if ((this.state.gallery == "" && this.props.gallery && this.props.gallery != "") || (this.state.gallery != this.props.gallery)) {
            this.setState({gallery: this.props.gallery});
        }
    }

    selectImage(e) {
        //console.log("image selected:", typeof e, e);
        this.props.dispatch(actc.reloadPhotoLoaded("source", 0, e));
        this.props.dispatch(actc.reloadPhotoLoaded("thumb", 0, e));
        this.props.dispatch(actc.reloadPhotoLoaded("gallery", 0, e));
        this.props.dispatch(actc.spreadPhoto("thumb"));
        this.props.dispatch(actc.spreadPhoto("gallery"));
    }

    render() {
        let data = "", srcname = "", bigphotoedit = "";
        if (this.props.editphoto_data && this.props.editphoto_data.name) {
            let photoname = this.props.editphoto_data.name;
            srcname = <a  target="_blank" href={"https://elitsy.ru/palomnichestvo/img/" + photoname}><img
                id="sourcephoto" src={this.props.source} width="200"/></a>;
        }

        let uploadbutton = <ImageFileSelector
            ref="imageFileSelector"
            onSelect={this.selectImage} //require
            onInvalidImage={(e) => alert('invalid')}/>;
        let comment = <input type="text" size="26" onChange={(e) => {
            this.setState({comment: e.target.value})
        }} value={this.state.comment}/>;
        let position = <input type="text" size="5" value={this.state.sorder} onChange={(e) => {
            this.setState({sorder: isNaN(e.target.value) ? 0 : e.target.value})
        }}/>;

        let savebut = <button onClick={this.handleSubmit} className="btn btn-primary">Сохранить текст</button>
        let instruments = <div className="col-md-12">
            <div className="container">
                <div className="row" style={{fontSize: "1.25em"}}> Исходное&nbsp;фото:<br />{srcname}</div>
                <div className="row" style={{fontSize: "1.1em"}}>Загрузить&nbsp;новое:</div>
                <div className="row">{uploadbutton}</div>
                <div className="row"><div><button
                    onClick={e => this.props.dispatch(actc.reloadPhoto(this.props.editphoto_data.id ? this.props.editphoto_data.id : 0, "source"))}
                    title="Восстановить изображение с сервера"><span className="glyphicon glyphicon-refresh"
                                                                     aria-hidden="true"></span></button>
                    <button
                        onClick={this.copyData}
                        title="Распространить"><span className="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                    </button>
                    <button
                        onClick={e => {this.props.dispatch(actc.savePhoto(this.props.source, this.props.editphoto_id ? this.props.editphoto_id : 0, "source", this.props.tourid, this.props.pointid))}
                        }><span className="glyphicon glyphicon-floppy-open" aria-hidden="true"></span></button>
                </div></div>

                <div className="row">
                    <br />
                    Комментарий:
                    <br />
                    {comment}
                    <br />
                    Позиция:
                    <br />
                    {position}
                    <br />
                    {savebut}</div>
            </div></div>
        ;
        return <div id="modaleditphoto">
            <Modal bsStyle="primary" bsSize="lg" animation={false} dialogClassName="photoedit" show={this.props.openwindow} onHide={(e) => {
                if (this.props.tourid && parseInt(this.props.tourid)>0)this.props.dispatch(actc.getTourPhoto(this.props.tourid));
                if (this.props.pointid && parseInt(this.props.pointid)>0) this.props.dispatch(actc.getPointsPhoto(this.props.pointid));
                this.props.dispatch(actc.closeEditPhotoWindow());
                if (this.props.backto==="editpoint" && this.props.pointid && parseInt(this.props.pointid)>0)this.props.dispatch(act.showEditPoint(this.props.pointid));

            }}>
                <Modal.Header closeButton>
                    <Modal.Title><h6>Фото</h6></Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <table style={{width: "100%"}}>
                        <tbody>
                        <tr>

                            <td>
                                <div className="col-md-12">
                                    <div className="col-md-3">{instruments}</div>
                                    <div className="col-md-3">300x200:<br /><MiniWindow src={this.props.thumb} onSave={this.handleSavePic}/></div>
                                    <div className="col-md-4">600x400:<br /><BigPhotoWindow src={this.props.gallery} onSave={this.handleSavePic}/></div></div></td>

                        </tr>

                        </tbody>
                    </table>
                </Modal.Body>
            </Modal>
        </div>
    }
}


function mapStateToProps(state) {
    //console.log("PHOTOEDITFORM", state);
    if (state) {
        return {
            openwindow: state.tourstate.editphoto_window,
            tourid: state.tourstate.editphoto_list_main_id,
            pointid: state.photos.edit_photo_main_id,
            editphoto_id: state.tourstate.editphoto_id,
            editpicwindow: state.tourstate.editpicwindow,
            editphoto_data: state.tourstate.editphoto_data,
            thumb: state.tourstate.editphoto_thumb,
            gallery: state.tourstate.editphoto_gallery,
            source: state.tourstate.editphoto_source,
            addmode: (state.tourstate.editphoto_tourid > 0 && state.tourstate.editphoto_id == 0)
        }
    }
}

export default connect(mapStateToProps)(PhotoEditForm);




