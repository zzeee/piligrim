/**
 * Created by a.zienko on 23.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import {showEditTour,ACT_EDITTOURS, ACT_TOURSLIST} from './actions/actions.js';



class ToursList extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    if (this.props.active_window != ACT_TOURSLIST) return <span></span>
  const getList=function (number)
      {
          //console.log(number);
          return <div><a style={{cursor:"pointer"}} onClick={(event)=>this.props.dispatch(showEditTour(number.id))}>{number.title}</a></div>
      }

      let listItems = this.props.toursList.map(getList.bind(this));


      //if (typeof(number) != undefined) {

      return <div>{listItems}</div>

  }
}


  function mapStateToProps(state) {
  console.log("tourslist",state);
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
        toursList,
      active_window,
      NOV_STATUS
    } = state.novstate;

    return {
      eluser_id,
      eluser_name,
      eluser_photo,
      hotelid,
      orderstatelist,
      tourid,
        toursList,
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

  export default connect(mapStateToProps)(ToursList)
