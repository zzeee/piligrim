/**
 * Created by леново on 04.07.2017.
 */
/**
 * Created by леново on 04.07.2017.
 */
import * as act from '../actions/actions-list.js';
import * as nact from '../actions/actions.js';


const initialState = {
    edit_point_status: "no",
    edit_point_id: 0,
    edit_point_data:[],
    all_points:[],
    edit_point_photo_data:[],
    edit_point_photo_main_id:0,
    edit_points_ainfo_loaded_id: 0,
    edit_points_ainfo_data:[],
    edit_points_ainfowindow:false,
    edit_points_ainfo_edit_id:0
};

export default function userstate(state = initialState, action) {
    //console.log("action log from points reducer2:", action.type, action, state);
    if (typeof state === 'undefined') {
        return initialState
    }

    switch (action.type) {
        case nact.NOV_POINTSLISTS_LOADED:
            let res = action.list;
            return Object.assign({}, state, {
                edit_point_status: "loaded",
                all_points: res
            });
            break;
            case nact.ACT_EDITPOINT:
            return Object.assign({}, state, {
                edit_point_id: action.id
            });
            break;
            case act.ADM_GET_POINT_ADDINFO_LOADED:
            return Object.assign({}, state, {
                edit_points_ainfo_loaded_id: action.pointid,
                edit_points_ainfo_data:action.loaded
            });
            break;
            case act.ADM_EDIT_POINT_ADDINFO:
            //console.log("POINTS ainfo 2  ADDLOADED", action, state);
            return Object.assign({}, state, {
                edit_points_ainfowindow:true,
                edit_points_ainfo_edit_id:action.id
            });
            break;
            case act.ADM_ADD_POINT_ADDINFO:
            //console.log("POINTS ainfo 2  ADDLOADED", action, state);
            return Object.assign({}, state, {
                edit_points_ainfowindow:true,
                edit_points_ainfo_edit_id:action.id
            });
            break;
            case act.ADM_EDIT_POINT_ADDINFO_CLOSE:
            //console.log("POINTS ainfo 2  ADDLOADED", action, state);
            return Object.assign({}, state, {
                edit_points_ainfowindow:false,
                edit_points_ainfo_edit_id:0
            });
            break;
/*        case act.ADM_POINTPHOTOLOADED:

            //console.log("POINTS2  LOADED", action, state);
            return Object.assign({}, state, {
                edit_point_photo_main_id: action.id,
                edit_point_photo_data: action.data
            });
            break;*/
        default:
            return state;


    }
}




