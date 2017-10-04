/**
 * Created by леново on 04.07.2017.
 */
import * as act from '../actions/actions-list.js';
import * as nact from '../actions/actions.js';


const initialState = {
    showtourid: 0,
    photo_loading:0,
    toursarray: [],
    loading_tour_id:0,
    loading_point_id:0,

    tur_points_loaded: 0,
    turpoints: [],
    tur_dates: [],
    tur_dates_main_id: 0,
    tur_services: [],
    tur_services_main_id: 0,
    editdate_window: false,
    allpicsaved:0,
    editdate_id: 0,
    editdate_data: [],
    editdate_turid: 0,
    editservice_window: false,
    editservice_id: 0,
    editservice_data: [],
    editservice_turid: 0,
    editphoto_list_main_id: 0,
    editphoto_list_data: [],
    editphoto_id: 0,
    editphoto_window: false,
    editphoto_turid: 0,
    editphoto_data: [],
    editpicwindow: "no",
    editphoto_thumb: "",
    editphoto_gallery: "",
    editphoto_source: "",
    editphoto_super: "",
    resetphoto_gallery: false,
    resetphoto_thumb: false,
    savephoto_gallery: false,
    savephoto_thumb: false,
    resetphoto_super: false,
    saved_thumb:0,
    saved_gallery:0,
    saved_source:0,
    saved_super:0,
    organizers_list:[]



};

export default function userstate(state = initialState, action) {
//    console.log("удивительный tours-reducer action log from reducer2!!!!!!:", action.type, action, 'state:', state);

    if (typeof state === 'undefined') {
        return initialState
    }

    switch (action.type) {

        case act.NOV_TOURLISTLOADED:
      //      console.log("TOUR2S LOADED", action, state);
            let res = action.list;

            return Object.assign({}, state, {
                tours_status: "loaded",
                toursList: res
            });
            break;
        case act.ADM_TOURPOINTSLOADED:
    //        console.log("TOUR2S LOADED", action, state);
            //let res = action.list;

            return Object.assign({}, state, {
                tur_points_loaded: action.tourid,
                turpoints: action.data
            });
            break;
            case nact.ACT_EDITPOINT:
                //console.log('CLEAR:');
                return initialState;


                break;



        case act.ADM_TOURDATESLOADED:
            let res2 = action.data;

            return Object.assign({}, state, {
                tur_dates: res2,
                tur_dates_main_id: action.tourid
            });
            break;
        case act.ADM_TOURSERVICESLOADED:
            let res3 = action.data;

            return Object.assign({}, state, {
                tur_services: res3,
                tur_services_main_id: action.tourid
            });
            break;
        case act.ADM_TOURPHOTOLOADED:
            let res4 = action.data;
            return Object.assign({}, state, {
                photo_loading:0,
                editphoto_list_data: res4,
                editphoto_list_main_id: action.tourid
            });
            break;
        case act.EDITTOUR_ORGANIZERS_LOADED:
            //console.log('org loaded');
            return Object.assign({}, state, {
                organizers_list:action.list
            });
            break;

        case act.ADM_POINTPHOTOLOADED:
            return Object.assign({}, state, {
                photo_loading:0,
            });
            break;

        case act.ADM_WIN_OPENEDITDATE:
            return Object.assign({}, state, {
                editdate_window: true,
                editdate_id: action.dateid,
                editdate_data: action.data
            });
            break;

        case act.ADM_WIN_OPENADDDATE:
            return Object.assign({}, state, {
                editdate_window: true,
                editdate_id: 0,
                editdate_tourid: action.tourid
            });

            break;
        case act.ADM_WIN_CLOSEDITDATE:
            return Object.assign({}, state, {
                editdate_window: false,
                editdate_id: 0,
                editdate_tourid: 0,
                editdate_data: []
            });

            break;
        case act.ADM_WIN_OPENEDITSERVICE:
            return Object.assign({}, state, {
                editservice_window: true,
                editservice_id: action.serviceid,
                editservice_data: action.data
            });
            break;

        case act.ADM_WIN_OPENADDSERVICE:
            return Object.assign({}, state, {
                editservice_window: true,
                editservice_id: 0,
                editservice_tourid: action.tourid
            });

            break;
        case act.ADM_WIN_CLOSEDITSERVICE:
            return Object.assign({}, state, {
                editservice_window: false,
                editservice_id: 0,
                editservice_tourid: 0,
                editservice_data: []
            });

            break;

        case act.ADM_WIN_OPENEDITPHOTO:
            return Object.assign({}, state, {
                editphoto_window: true,
                editphoto_id: action.photoid,
                editphoto_data: action.data
            });
            break;
        case act.ADM_GETTOURPHOTO:
            return Object.assign({}, state, {
                photo_loading:1,
                loading_tour_id:action.tourid
                });
            break;
            case act.ADM_GETTOURPHOTO:
            return Object.assign({}, state, {
                photo_loading:1,
                loading_point_id:action.id
                });
            break;

        case act.ADM_WIN_OPENADDPHOTO:
            return Object.assign({}, state, {
                editphoto_window: true,
                editphoto_tourid: action.tourid
            });
            break;

        case act.ADM_WIN_CLOSEDITPHOTO:
            return Object.assign({}, state, {
                editphoto_window: false,
                allpicsaved:0,
                editphoto_list_main_id:0,
                saved_thumb:0,
                saved_gallery:0,
                saved_source:0,
                saved_super:0,
                editphoto_id: 0,
                editphoto_tourid: 0,
                editphoto_data: [],
                editphoto_thumb: "",
                editphoto_super: "",
                editphoto_gallery: "",
                editphoto_source: ""

            });

            break;
        case act.ADM_EDIT_PIC:
            return Object.assign({}, state, {
                editpicwindow: action.phototype
            });

            break;
        case act.SAVE_PHOTO_SUCCESS:
    //console.log("SA PH SUCCESS", action);
    let allpicsaved=0;
    //console.log("STATE:",state);
   //  if (state.saved_thumb==1 && state.saved_gallery==1 && state.saved_source==1) allpicsaved=1;

            if (action.phototype=="thumb")
            return Object.assign({}, state, {
                editphoto_id: action.photoid,
                saved_thumb:1, allpicsaved:((state.saved_gallery==1 && state.saved_source==1)?1:0)
            });
            if (action.phototype=="gallery")
            return Object.assign({}, state, {
                editphoto_id: action.photoid,
                saved_gallery:1, allpicsaved:((state.saved_thumb==1 && state.saved_source==1)?1:0)
            });
            if (action.phototype=="super")
                return Object.assign({}, state, {
                editphoto_id: action.photoid,
                    saved_super:1
            });
            if (action.phototype=="source")
                return Object.assign({}, state, {
                editphoto_id: action.photoid,
                    saved_source:1,allpicsaved:((state.saved_gallery==1 && state.saved_thumb==1)?1:0)
            });
            break;
        case act.SAVE_ALL_PIC:
            return Object.assign({}, state, {
                savephoto_thumb: true,
                savephoto_gallery: true});
            break;

            case act.SAVE_ALL_PIC_RECEIVED:
            //    console.log("SAVALL", action);
                if (action.itype==="thumb") return Object.assign({}, state, {
                savephoto_thumb: false
                });
                if (action.itype==="gallery") return Object.assign({}, state, {
                savephoto_gallery: false
                });
            break;
        case act.ADM_PHOTOLOADED:
            if (action.phototype == "thumb")             return Object.assign({}, state, {
                editphoto_thumb: action.data
            });
            if (action.phototype == "gallery")             return Object.assign({}, state, {
                editphoto_gallery: action.data
            });
            if (action.phototype == "source")             return Object.assign({}, state, {
                editphoto_source: action.data
            });
            if (action.phototype == "super")             return Object.assign({}, state, {
                editphoto_super: action.data
            });

            return state;
            break;
        case act.ADM_SPREADPHOTO:
            //console.log('SPREADPHOTO',state);
            if (action.phototype == "thumb") return Object.assign({}, state, {
                resetphoto_thumb: true,
                editphoto_thumb: state.editphoto_source
            });
            if (action.phototype == "gallery") return Object.assign({}, state, {
                resetphoto_gallery: true,
                editphoto_gallery: state.editphoto_source
            });
            if (action.phototype == "super") return Object.assign({}, state, {
                resetphoto_super: true,
                editphoto_super: state.editphoto_source
            });
            return state;

            break;
        case act.SAVE_PHOTO:
//console.log('sv photo', action);
            if (action.itype == "thumb") return Object.assign({}, state, {
                saved_thumb:0,
             });if (action.itype == "gallery") return Object.assign({}, state, {
                saved_gallery:0,
             });if (action.itype == "super") return Object.assign({}, state, {
                saved_super:0,
             });if (action.itype == "source") return Object.assign({}, state, {
                saved_source:0,
             });
            break;

        case act.SAVE_PHOTO_TOSTORE:
            if (action.phototype == "thumb") return Object.assign({}, state, {
                editphoto_thumb: action.img
            });
            if (action.phototype == "gallery") return Object.assign({}, state, {
                editphoto_gallery: action.img
            });
            if (action.phototype == "super") return Object.assign({}, state, {
                editphoto_super: action.img
            });
            return state;

            break;


        case act.ADM_SPREADED:
            if (action.phototype == "thumb") return Object.assign({}, state, {
                resetphoto_thumb: false
            });
            if (action.phototype == "gallery") return Object.assign({}, state, {
                resetphoto_gallery: false
            });
            if (action.phototype == "super") return Object.assign({}, state, {
                resetphoto_super: false
            });
            return state;

            break;


        default:
            return state;


    }
}




