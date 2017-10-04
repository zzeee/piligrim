/**
 * Created by Zienko on 18.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import EditTour from './editTour.js';
import EditPoint from './editPoint.js';
import NoAuth from '../noAuth.js';
import ToursList from './ToursList.js';
import AdminSales from './AdminSales.js';
import PointsList from './points.js';
import {
    detectElUser,
    actUpdateTour,
    novPointsList,
    ACT_SALES,
    changeActiveWindow,
    ACT_EDITPOINT,
    ACT_TOURSLIST,
    ACT_POINTLIST,
    novTourList
} from '../actions/actions.js';
import * as act from '../actions/actions-list.js';
import * as actm from '../actions/action-creators.js';


class Acmain extends Component {
    constructor(props) {
        super(props);
        this.state = {loaded: 0}
        this.pointsList = "";
        this.toursubmit = this.toursubmit.bind(this);
        props.dispatch(detectElUser());

    }

    toursubmit(e) {
        this.props.dispatch(actUpdateTour(e));
        // e.preventDefault();
    }

    componentDidUpdate() {
        if (this.props.NOV_STATUS == "AUTHORIZED" && this.state.loaded == 0) {
            this.props.dispatch(novPointsList());
            this.props.dispatch(novTourList());
            this.props.dispatch(actm.getOrders());
            this.props.dispatch(actm.getOrganizers());
            this.props.dispatch(changeActiveWindow(ACT_POINTLIST));
            //console.log("acmain", this.props);
            if (parseInt(this.props.user_isadmin) == 1) {
                this.setState({loaded: 1});
            }
            else {
                this.setState({loaded: -1});
            }
        }
        if (this.props.ELSTATUS == "ERR_READ EL AUTH" && this.state.loaded == 0) {
            this.setState({loaded: -1});
        }
    }

    render() {

        let resline = <div>Проверка прав доступа...</div>
        //console.log("ACCES",this.state);
        if (this.state.loaded == 1) {
            let str = "";
            let ltclass = (this.props.active_window == ACT_POINTLIST) ? "active" : "";
            let ltourclass = (this.props.active_window == ACT_TOURSLIST) ? "active" : "";
            let lsalesclass = (this.props.active_window == ACT_SALES) ? "active" : "";


            resline = <div className="col-md-12"><h1>Панель администратора</h1><br />
                <ul className="nav nav-tabs">
                    <li role="presentation" className={ltclass}><a style={{cursor: "pointer"}}
                                                                   onClick={() => this.props.dispatch(changeActiveWindow(ACT_POINTLIST))}>Точки</a>
                    </li>
                    <li role="presentation" className={ltourclass}><a style={{cursor: "pointer"}}
                                                                      onClick={() => this.props.dispatch(changeActiveWindow(ACT_TOURSLIST))}>Поездки</a>
                    </li>
                    <li role="presentation"><a style={{cursor: "pointer"}}
                                               onClick={() => this.props.dispatch(changeActiveWindow(ACT_SALES))}
                                               href="#">Продажи</a></li>
                    <li role="presentation"><a style={{cursor: "pointer"}}
                                               onClick={() => this.props.dispatch(actm.getOrders())}
                                               href="#">Refresh orders</a></li>
                    <li role="presentation"><a style={{cursor: "pointer"}}
                                               onClick={() => {
                                                   this.props.dispatch(actm.getOrders());
                                                   this.props.dispatch(novPointsList());
                                                   this.props.dispatch(novTourList());
                                               }}
                                               href="#">Refresh all</a></li>

                </ul>
                <div className="col-md-12">
                    <PointsList noshowpublish={false} noonsite={false} noshowonsite={false} noshowdel={false}
                                showonlyselected={false}
                                status={this.props.points_status}/><EditPoint /><ToursList /><EditTour
                    onSubmit={this.toursubmit}/><AdminSales /></div>
            </div>
        }


        if (this.state.loaded == -1) {
            //console.log(, window.location,"AAA");
            resline = <div><NoAuth loc={this.props.location.pathname}/></div>
        }

        return resline;


    }

}

function mapStateToProps(state) {
    //console.log("admin-cabinet", state);

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
            ELSTATUS,
            user_name,
            user_phone,
            user_isadmin,
            location_userid,
            location_option1,
            active_window,
            points_status, pointsList,
            tours_status, toursList,

            NOV_STATUS
        } = state.novstate

        // console.log("state-main", user_id, eluser_id);

        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            ELSTATUS,
            NOV_STATUS,
            user_email,
            user_id,
            user_isadmin,
            active_window,
            user_name,
            user_phone,
            points_status, pointsList,
            tours_status, toursList,
            location_userid,
            location_option1

        }
    }

}


export default connect(mapStateToProps)(Acmain)