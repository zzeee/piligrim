/**
 * Created by леново on 01.08.2017.
 */
import * as act from '../actions/actions-list.js';
import * as nact from '../actions/actions.js';


const initialState = {
    organizators:[]
}



export default function userstate(state = initialState, action) {

    if (typeof state === 'undefined') {
        return initialState
    }

    switch (action.type) {

        case act.TOURORGANIZATORS_LOADED:
//            console.log("action log from mainreducer!!!!!!:", action.type, action, 'state:', state);

            let res = action.list;

            return Object.assign({}, state, {
                organizators: res
            });
            break;
        default:
            return state;


    }

}
