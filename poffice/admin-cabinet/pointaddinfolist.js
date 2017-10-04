/**
 * Created by леново on 09.08.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';

import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
import AddInfoEdit from './addinfo_edit.js';
//import TourServiceEditForm from './tourserviceeditform.js';

class PointAddinfoList extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        // console.log ('tour services main render');
        let l="";
        let flist=function(e)
        {
     //        console.log("AAAAAAAAAAAA",e);
            //return "1";
            return <div>{e.title} - {e.url}<button onClick={(q)=>this.props.dispatch(actc.editPointAddInfo(parseInt(e.id),e))}><span className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button><button onClick={((q)=>{this.props.dispatch(actc.delPointAddInfo(e.id,  this.props.point_id))}).bind(this)} ><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></button></div>
        }
        let narr=[];
        if (this.props.point_data) narr=Array.from(this.props.point_data);
        console.log('NNN',narr);
        if (narr.length>0)
        l=this.props.point_data.map(flist.bind(this));

        return <div className="well">Дополнительная информация:<br /><br />{l}<button onClick={((e)=>{console.log(e);
            //this.props.dispatch(actc.AddInfoWindow(parseInt(this.props.tur_services_main_id)));
        }).bind(this)}><span onClick={(e)=>this.props.dispatch(actc.editPointNewAddInfo(this.props.point_id))} className="glyphicon glyphicon-plus" aria-hidden="true"></span></button><AddInfoEdit /></div>
    }
}

function mapStateToProps(state) {
    console.log("point add", state);

    if (state) {
        // console.log("state-main", user_id, eluser_id);

        return {
            point_id:state.points.edit_points_ainfo_loaded_id,
            point_data: state.points.edit_points_ainfo_data//,
            //pointid:state.points.edit_point_id

        }
    }

}


export default connect(mapStateToProps)(PointAddinfoList)