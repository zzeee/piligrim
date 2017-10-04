/**
 * Created by леново on 21.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
import PhotoEditForm from './photoeditform.js'


class EditPointPhotoList extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        //console.log('point photo main render');
        let l="";
        if (this.props.editphoto_list_data && this.props.editphoto_list_data.length>0)
        {
            l=this.props.editphoto_list_data.map((e)=>{
                //console.log("WWW",e);
                let p1=<span>нет основного фото </span>,p2=<span>нет фото для галереи </span>,p3=<span>нет иконки </span>,p4=<span>нет картинки</span>
                if (e.name) p1=<img align="bottom" title="Основное фото" width="300" src={"/palomnichestvo/img/"+e.name}/>;
                if (e.galname) p2=<img align="bottom" title="Галерея" width="300" src={"/palomnichestvo/img/"+e.galname}/>;
                if (e.thumbname) p3=<img align="bottom" title="иконка" width="300" src={"/palomnichestvo/img/"+e.thumbname}/>;
                if (e.supername) p4=<img align="bottom" title="супер-картинка" height="100" src={"/palomnichestvo/img/"+e.supername}/>;
                //console.log("PHOTOS:",e);
                return <div>#{e.id} {e.comment}<button onClick={(q)=>{this.props.dispatch(actc.editPhotoWindow(parseInt(e.id),e)) } }><span className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button><button onClick={((q)=>{ this.props.dispatch(actc.delTourPhoto(e.id, 0,this.props.editphoto_list_main_id))}).bind(this)} ><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></button><br /><button onClick={(q)=>{
                    let eid=parseInt(e.id)
                    if (!isNaN(eid) && eid>0)
                    {
                        this.props.dispatch(actc.reloadPhoto(eid,"thumb"));
                        this.props.dispatch(actc.reloadPhoto(eid,"gallery"));
                        this.props.dispatch(actc.reloadPhoto(eid,"source"));
//                        this.props.dispatch(actc.reloadPhoto(eid,"super"));
                        this.props.dispatch(actc.editPhotoWindow(eid,e))}
                } }>{p1}{p2}{p3}{p4}</button></div>});
        }
        return <div className="well">Фото<br />{l}<br /><button onClick={((q)=>{ this.props.dispatch(actc.AddPhoto(0,this.props.editphoto_list_main_id))}).bind(this)}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span>{this.props.editphoto_list_main_id}</button><PhotoEditForm itype="point" backto="editpoint"/></div>
    }
}

function mapStateToProps(state) {
    //console.log("point photos", state);

    if (state) {
        // console.log("state-main", user_id, eluser_id);

        return {

            editphoto_list_main_id: state.photos.edit_photo_main_id,
            editphoto_list_data: state.photos.edit_photo_data
//            editphoto_list_main_id: state.tourstate.editphoto_list_main_id,
  //          editphoto_list_data: state.tourstate.editphoto_list_data

        }
    }

}


export default connect(mapStateToProps)(EditPointPhotoList)