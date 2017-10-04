/**
 * Created by леново on 21.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
import PhotoEditForm from './photoeditform.js'


class TourPhotoEdit extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        console.log('tour photo main render');
        let l="";
        if (this.props.editphoto_list_data && this.props.editphoto_list_data.length>0)
        {

          l=this.props.editphoto_list_data.map((e)=>{
              let p1=<span>нет основного фото </span>,p2=<span>нет фото для галереи </span>,p3=<span>нет иконки </span>;
              if (e.name) p1=<img align="bottom" title="Основное фото" width="300" src={"/palomnichestvo/img/"+e.name}/>
              if (e.gallery) p2=<img align="bottom" title="Галерея" width="300" src={"/palomnichestvo/img/"+e.gallery}/>
              if (e.thumb) p3=<img align="bottom" title="иконка" width="300" src={"/palomnichestvo/img/"+e.thumb}/>
              console.log("PHOTOS:",e);


              return <div>#{e.id} {e.comment}<button onClick={(q)=>{this.props.dispatch(actc.editPhotoWindow(parseInt(e.id),e)) } }><span className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button><button onClick={((q)=>{ this.props.dispatch(actc.delTourPhoto(e.id, this.props.editphoto_list_main_id))}).bind(this)} ><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></button><br /><button onClick={(q)=>{
              let eid=parseInt(e.id)
              if (!isNaN(eid) && eid>0)
              {
              this.props.dispatch(actc.reloadPhoto(eid,"thumb"));
              this.props.dispatch(actc.reloadPhoto(eid,"gallery"));
              this.props.dispatch(actc.reloadPhoto(eid,"source"));
              this.props.dispatch(actc.editPhotoWindow(eid,e))}
          } }>{p1}{p2}{p3}</button></div>});
        }
       return <div className="well">Фото<br />{l}<br /><button onClick={((q)=>{ this.props.dispatch(actc.AddPhoto(this.props.editphoto_list_main_id,0))}).bind(this)}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span></button><PhotoEditForm itype="tour"/></div>
    }
}



function mapStateToProps(state) {
    console.log("tour photos", state);

    if (state) {
        // console.log("state-main", user_id, eluser_id);

        return {
        editphoto_list_main_id: state.tourstate.editphoto_list_main_id,
        editphoto_list_data: state.tourstate.editphoto_list_data
        }
    }

}


export default connect(mapStateToProps)(TourPhotoEdit)