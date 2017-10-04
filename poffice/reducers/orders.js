/**
 * Created by леново on 04.07.2017.
 */
import * as act from '../actions/actions-list.js';
import * as nact from '../actions/actions.js';


const initialState = {
     orders_array:[]
}

export default function userstate(state = initialState, action) {
//    console.log("action log from reducer2:", action.type, action, state);
    if (typeof state === 'undefined') {
        return initialState
    }

    switch (action.type) {
        case act.ADM_ORDERS_LOADED:
            //console.log("ORDERS LOADED", action, state);
            let res = action.orders;

            return Object.assign({}, state, {
                orders_status: "loaded",
                orders_array: res
            });

            break;
            case act.WIN_SHOWTOURLOADED:
            console.log("WIN_SHOWTOURLOADED", action, state);
            let res2 = action.data;

            return Object.assign({}, state, {
                show_reserve_status: "loaded",
                show_reserve_info: res2,
              show_reserve_date: action.dateid
              //show_reserve_dateid:
            });

            break;
        default:
            return state;


    }
}




/**
 * Created by леново on 06.07.2017.
 */
