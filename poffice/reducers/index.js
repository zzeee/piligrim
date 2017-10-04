/**
 * Created by Zienko on 16.01.2017.

 */
import {
  LP_USERINFO,
  NOV_FETCH_OK,
  EL_FETCH_OK,
  NOV_READUSER,
  LP_ORDER,
  ACT_EDITPOINT,
  ACT_POINTLIST,
  ACT_EDITTOURS,
  LP_TOURRESERVE,
  NOV_SAVEUSERINFO,
  NOV_UPDATEPOINTRESULT,
  NOV_SEARCHTOURRESULT,
  NOV_ENTERSITE,
  NOV_POINTSLISTS_LOADED,
  NOV_TOURLISTLOADED,NOV_TOURLIST,
  NOV_CHANGEACTIVEWINDOW,
  ACT_UPDATEPOINT,
  WIN_TOUR_RESERVE,
  WIN_NOWINDOW
} from '../actions/actions.js';
import * as act from '../actions/actions-list.js';
const initialState = {
  user_id: false,
  user_phone: false,
  user_email: false,
  user_name: false,
  eluser_id: false,
  eluser_name: false,
  eluser_photo: false,
  order_date_num:"",
  order_date_id:"",
    backtourid:0,
    vkuser_id:0,
    vk_fullresponse:[],
    hotelid: false,
  tourid: false,
  orderstatelist: false,
    reservemode:0
};
export default function userstate(state = initialState, action) {
   // console.log("ac tion log from main:", action.type, action, state);
  if (typeof state === 'undefined') {
    return initialState
  }
  switch (action.type) {
    case EL_FETCH_OK:
      let qt = action.value;
      if ((typeof(qt) == "object") && qt["id"] && parseInt(qt["id"]) > 0) {
        return Object.assign({}, state, {
          ELSTATUS: "AUTHORIZED",
          eluser_id: qt["id"],
          eluser_name: qt["name"],
          eluser_photo: qt["avatar_38_url"]
        });
      } else {
        //console.log('ret err');
        return Object.assign({}, state, {
          ELSTATUS: "ERR_READ EL AUTH"
        });
      }
      break;
    case NOV_FETCH_OK  :
      let qrt = action.value;
//console.log("USERIDSET",qrt);
//alert('1');
      return Object.assign({}, state, {
        NOV_STATUS: "AUTHORIZED",
        user_id: (qrt && qrt["id"]) ? qrt["id"] : false,
        user_phone: (qrt && qrt["phone"]) ? qrt["phone"] : false,
        user_name: (qrt && qrt["name"]) ? qrt["name"] : false,
        user_email: (qrt && qrt["email"]) ? qrt["email"] : false,
        user_isadmin: (qrt && qrt["isadmin"]) ? parseInt(qrt["isadmin"]) : false,
        user_iseditor: (qrt && qrt["iseditor"]) ? parseInt(qrt["iseditor"]) : false,
        user_istourmaster: (qrt && qrt["istourmaster"]) ? parseInt(qrt["istourmaster"]) : false,
        user_isadv: (qrt && qrt["isadv"]) ? parseInt(qrt["isadv"]) : false
      });
      break;
    case NOV_SAVEUSERINFO:
      //qrt=action;
      return Object.assign({}, state, {
        user_phone: action.phone,
        user_name: action.username,
        user_email: action.email
      });
      break;
    case NOV_ENTERSITE:
      return Object.assign({}, state, {
        location_userid: action.userid,
        location_option1: action.option1
      });
      break;
    case NOV_POINTSLISTS_LOADED:
      //console.log("POINTS LOADED", action);
      return Object.assign({}, state, {
        points_status: "loaded",
        pointsList: action.list
      });
      break;
    case NOV_TOURLISTLOADED:
      //console.log("TOURS LOADED",action, state);
      let res = action.list;
      /*
       ����� �� ������� - ������� ���������� ������ � ������ ����� �� ���������� ���� � ����
       if (state.toursList) 
       {
       console.log('muys');
       res=action.list=state.tourList;
       res.push(action.list);
       } */
      return Object.assign({}, state, {
        tours_status: "loaded",
        toursList: res
      });
      break;
    case NOV_TOURLIST:
      return Object.assign({}, state, {
        tours_status: "loading",
        toursList: []
      });
      break;


    case NOV_CHANGEACTIVEWINDOW:
      return Object.assign({}, state, {
        active_window: action.id,
      });
      break;
    case ACT_EDITPOINT:
      return Object.assign({}, state, {
        active_window: ACT_EDITPOINT,
        edit_id: action.id,
          backtourid:action.backtourid,
        editpoint: Array.from(state.pointsList).filter(((num) => {
          if (num.id == action.id) return true
        }).bind(this))
      })
      break;
    case ACT_EDITTOURS:
      //console.log('chtour', Array.from(state.toursList).filter(((num) => {        if (num.id == action.id) return true      }).bind(this)));
      return Object.assign({}, state, {
        active_window: ACT_EDITTOURS,
        edit_tid: action.id,
        edittour: Array.from(state.toursList).filter(((num) => {
          if (num.id == action.id) return true
        }).bind(this))
      })
      break;
    case ACT_POINTLIST:
      return Object.assign({}, state, {
        active_window: ACT_POINTLIST,
        edit_id: 0,
        editpoint: []
      });
      break;
    case ACT_UPDATEPOINT:
      return Object.assign({}, state, {
        save_point_result: "SENT"
      });
      break;
    case NOV_SEARCHTOURRESULT:
      return Object.assign({}, state, {
        search_result: action.value
      });
      break;
    case NOV_UPDATEPOINTRESULT:
//            this.props.dispatch(novPointsList());
      return Object.assign({}, state, {
        save_point_result: (action.value.status)
      });
      break;
    case LP_TOURRESERVE:
      return Object.assign({}, state, {
        active_window: WIN_TOUR_RESERVE,
        tourid: action.id,
        param2: action.dat
      });
      break;


    case WIN_NOWINDOW:
      return Object.assign({}, state, {
        active_window: WIN_NOWINDOW
      });
      break;

      case act.ADM_WIN_CLOSEDITPHOTO:
          return Object.assign({}, state, {
              edit_tid: 0
          });
          break;

      case act.NOV_SENDORDER:

          return Object.assign({}, state, {
             // tourorderstatus: act.NOV_ORDERSUCCESS,
              orderid:0,
              order_date_num:0,
              order_date_id:0,
              prepaysum:0,
              tourorderstatus:"",
              paymentline:"",
              elevent:""
          });


              case act.NOV_ORDERSUCCESS:
      return Object.assign({}, state, {
        tourorderstatus: act.NOV_ORDERSUCCESS,
        tourid: action.data.tourid,
        orderid: action.orderid,
        order_date_num:action.data.date_num,
        order_date_id:action.data.tourdate,
        prepaysum:action.data.prepaysum,
        paymentline:action.data.paymentline,

        elevent: action.elevent
      });
    case act.NOV_SENDORDER:
      return Object.assign({}, state, {
        tourorderstatus: act.NOV_SENDORDER
      });
      break;
    case act.RESERVE_MODE:
      return Object.assign({}, state, {
        reservemode:1
      });
      break;
    case act.VK_AUTHORIZED:
      return Object.assign({}, state, {
        vkuser_id:action.response.id,
        vk_fullresponse:action.response
      });
      break;
    default:
      return state;
  }
}
//export default userstate;