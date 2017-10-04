/**
 * Created by леново on 19.07.2017.
 */
/**
 * Created by леново on 19.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
import TourServiceEditForm from './tourserviceeditform.js';

class TourServicesEdit extends Component {
    constructor(props) {
        super(props);
    }

    render() {
       // console.log ('tour services main render');
        let l="";
        if (parseInt(this.props.tur_services_main_id)>0 && this.props.tur_services && this.props.tur_services.length>0)

        {
            console.log ('tour services n render');
            l=this.props.tur_services.map((e)=>{return <div>{e.title} - ({e.id}) {e.price} {e.description}  <button onClick={(q)=>this.props.dispatch(actc.editServiceWindow(parseInt(e.id),e))}><span className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button><button onClick={((q)=>{this.props.dispatch(actc.delTourServices(e.id))}).bind(this)} ><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></button></div>});


        }
        return <div className="well">Дополнительные возможности:<br />{l}<br /><button onClick={((e)=>{console.log(e);
            this.props.dispatch(actc.AddServiceWindow(parseInt(this.props.tur_services_main_id)));
        }).bind(this)}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span></button><TourServiceEditForm /></div>
    }


}

function mapStateToProps(state) {
    console.log("tour services", state);

    if (state) {
        // console.log("state-main", user_id, eluser_id);

        return {
            tur_services_main_id: state.tourstate.tur_services_main_id,
            tur_services: state.tourstate.tur_services
        }
    }

}


export default connect(mapStateToProps)(TourServicesEdit)