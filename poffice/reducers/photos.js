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
    edit_photo_main_id:0,
    editphoto_thumb:"",
    editphoto_gallery:"",
    editphoto_source:"",
    editphoto_super:"",
    edit_photo_data:[]
};

export default function userstate(state = initialState, action) {
   // console.log("action log from photoreducer:", action.type, action, state);
    if (typeof state === 'undefined') {
        return initialState
    }
    switch (action.type) {
        case act.ADM_POINTPHOTOLOADED:

            return Object.assign({}, state, {
                edit_photo_main_id: action.id,
                edit_photo_data: action.data
            });
            break;
        case act.ADM_PHOTOLOADED:
            if (action.phototype == "thumb")             return Object.assign({}, state, {
                edit_photo: action.id,
                editphoto_thumb: action.data[0]["thumbname"]
            });
            if (action.phototype == "gallery")             return Object.assign({}, state, {
                edit_photo: action.id,
                editphoto_gallery: action.data
            });
            if (action.phototype == "source")             return Object.assign({}, state, {
                edit_photo: action.id,
                editphoto_source: action.data
            });
if (action.phototype === "super")             return Object.assign({}, state, {
    edit_photo: action.id,
                editphoto_super: action.data
            });

            return state;
            break;

        case act.ADM_WIN_OPENEDITPHOTO:

            return Object.assign({}, state, {
                edit_photo: action.photoid
            });

            return state;
            break;
        case act.ADM_WIN_CLOSEDITPHOTO:
            return Object.assign({}, state, {

            });
            break;
        case act.ACT_EDITPOINT:
            return initialState;
            break;



                default:
            return state;


    }
}




