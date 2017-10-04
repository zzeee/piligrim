/**
 * Created by Zienko on 18.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import PicEdit from './picEdit.js';
import {showEditPoint,ACT_EDITPOINT, ACT_POINTLIST } from './actions/actions.js';

class PointsList extends Component {
    constructor(props) {
        super(props);
        //this.parseClick=
    }
    render()
    {
        if (this.props.status!="loaded" || this.props.active_window!=ACT_POINTLIST) return <span></span>
        const getList=function (number)
        {
            return <div><a style={{cursor:"pointer"}} onClick={(event)=>this.props.dispatch(showEditPoint(number.id))}>{number.name}</a></div>
        }

        let listItems = this.props.pointsList.map(getList.bind(this));


                //if (typeof(number) != undefined) {

                return <div>{listItems}</div>}



}

function mapStateToProps(state) {
    console.log("state-yAAAAAAa-admin - TEST POINT",state);
    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            user_email,
            user_id,
            user_name,
            user_phone,
            location_userid,
            location_option1,
            pointsList,
            active_window,
            NOV_STATUS
        } = state.novstate;

        return {
            //todos: state.novstate.is,

            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            NOV_STATUS,
            user_email,
            active_window,
            user_id,
            user_name,
            pointsList,
            user_phone,
            location_userid,
            location_option1
        }
    }
}

export default connect(mapStateToProps)(PointsList)

