/**
 * Created by леново on 02.08.2017.
 */
/**
 * Created by леново on 04.07.2017.
 */
/**
 * Created by леново on 04.07.2017.
 */
import * as act from '../actions/actions-list.js';
import * as nact from '../actions/actions.js';


const initialState = {
    galwindow: 0,
    galwindow_id:0,
    galwindow_galname:""

};

export default function userstate(state = initialState, action) {
   // console.log("PUBLIC REDUCER:", action.type, action, state);
    if (typeof state === 'undefined') {
        return initialState
    }
    switch (action.type) {
        case act.GALLERYWINDOW_SHOW:
            return Object.assign({}, state, {
                galwindow: 1,
                galwindow_id:action.picid,
                galwindow_galname: action.galname
            });
            break;
        case act.GALLERYWINDOW_HIDE:
            return Object.assign({}, state, {
                galwindow: 0,
                galwindow_id:0,
                galwindow_galname:""
            });
            break;

        default:
            return state;
    }
}





