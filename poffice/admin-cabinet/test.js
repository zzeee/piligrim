/**
 * Created by a.zienko on 07.07.2017.
 */

export default function mapStateToProps(state) {
  console.log("admin_sales+++++++++++++++",state);

  if (state) {
    const {
      eluser_id,
      orderstatelist,
      ELSTATUS,
      user_isadmin,
      location_userid,
      location_option1,
      active_window,
      elorders,
      NOV_STATUS
    } = state.novstate

    const {orders_array, orders_status}=state.orderslist;
    return {
      eluser_id,
      elorders,
      orders_status,
      orders_array,
      orderstatelist,
      ELSTATUS,
      user_isadmin,
      location_userid,
      location_option1,
      active_window,
      NOV_STATUS
    }
  }

}
