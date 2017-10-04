/**
 * Created by леново on 07.06.2017.
 */
export const LP_DATA = 'LOAD_USER';
export const LP_ORDER = 'LOAD_ORDER';
export const LP_USERINFO = 'LOAD_USERINFO2';
export const LP_TOURRESERVE = 'TOUR_RESERVE';
export const LP_HOTELRESERVE = 'HOTEL_RESERVE';
export const EL_USER = 'EL_USER';
export const EL_USERINFO = 'EL_USERINFO';
export const EL_FETCH_OK = 'FETCH_EL_OK';
export const EL_FETCH_NOK = 'FETCH_EL_NOK';
export const NOV_LOADPOINT='NOV_LOADPOINT';

export const NOV_READUSER = 'NOV_READUSER';
export const NOV_USER = 'USER_NOV';
export const NOV_FETCH_OK = 'NOV_FETCH_OK';
export const NOV_SAVEUSERINFO = 'NOV_SAVEUSERINFO';
export const NOV_ENTERSITE = 'NOV_enterSite';
export const NOV_POINTSLIST = 'NOV_POINTSLIST';
export const EL_FETCH_FAILED='EL_FETCH_FAILED';
export const NOV_POINTSLISTS_LOADED='NOV_POINTSLISTS_LOADED';
export const NOV_TOURLIST='EL_TOURLIST';
export const NOV_TOURLISTLOADED='EL_TOURLISTLOADED';
export const ACT_EDITPOINT='ACT_EDITPOINT';
export const ACT_POINTLIST='ACT_POINTLIST';
export const ACT_TOURSLIST='ACT_TOURSLIST';
export const ACT_EDITTOURS='ACT_EDITTOURS';

export const NOV_CHANGEACTIVEWINDOW='NOV_CHANGEACTIVEWINDOW';

export const ACT_UPDATEPOINT='ACT_UPDATEPOINT';
export const NOV_SAVEPOINTPICTURE='NOV_SAVEPOINTPICTURE';
export const NOV_SEARCHTOUR='NOV_SEARCHTOUR';
export const NOV_SEARCHTOURRESULT='NOV_SEARCHTOURRESULT';
export const NOV_UPDATEPOINTRESULT='NOV_UPDATEPOINTRESULT';

export const NOV_GET_ELPOINTDESCR='NOV_GET_ELPOINTDESCR';
export const NOV_RECEIVE_ELPOINTDESCR='NOV_RECEIVE_ELPOINTDESCR';
export const NOV_UPDATETOURRESULT='NOV_UPDATETOURRESULT';
export const ACT_UPDATETOUR='ACT_UPDATETOUR';
export const RELOADPOINTANDOPEN='RELOADPOINTANDOPEN';
export const NOV_ADDNEWTOUR='NOV_ADDNEWTOUR';
export const NOV_ADDNEWPOINT='NOV_ADDNEWPOINT';
export const NOV_DELPOINT='NOV_DELPOINT';
export const NOV_DEPPOINT='NOV_DEPPOINT';
export const NOV_PUBPOINT='NOV_PUBPOINT';
export const WIN_TOUR_RESERVE='WIN_TOUR_RESERVE';
export const WIN_HOTEL_RESERVE='WIN_HOTEL_RESERVE';
export const WIN_NOWINDOW='WIN_NOWINDOW';
export const WIN_SALES='WIN_SALES';
export const ACT_SALES='ACT_SALES';





export const selectUser = dod => ({
    type: LP_DATA,
    dod
});

export const selectOrder = dod => ({
    type: LP_ORDER,
    dod
});


export const loadUserInfo = dod => ({
    type: LP_USERINFO,
    userid:dod
});


export const LP_USERDETECTED = 'LP_USERDETECTED'

export function selectUserDetected(id) {
    return {
        type: LP_USERDETECTED,
        dat: "ertgret",
        id
    }
}

export function tourReserve(id, dat="ertgret") {
    return {
        type: LP_TOURRESERVE,
        dat: dat,
        id:id
    }
}

export function hotelReserve(id) {
    return {
        type: LP_HOTELRESERVE,
        dat: "ertgret",
        id
    }
}

export function userEl(id) {
    return {
        type: EL_USER,
        dat: "loaded",
        id
    }
}

export function detectElUser() {
    return {
        type: EL_USER,
        dat: "loaded"
    }
}


export function getElUserInfo(data) {
    return {
        type: EL_USERINFO,
        info: data
    }
}


export function userNov(id) {
    return {
        type: NOV_USER,
        dat: "loaded",
        id
    }
}

export function elOkFetch(data) {
    return {
        type: EL_FETCH_OK,
        value: data
    }
}

export function elNokFetch(data) {
    return {
        type: EL_FETCH_NOK,
        value: data
    }
}

export function novReadUser(id, data) {
    return {
        type: NOV_READUSER,
        id: id,
        value: data
    }
}

export function novFetchOk(data) {
    return {
        type: NOV_FETCH_OK,
        value: data
    }
}

export function novSaveUserInfo(name, phone, email, id) {
    return {
        type: NOV_SAVEUSERINFO,
        username:name,
        phone: phone,
        email:email,
        userid:id
    }
}

export function novExcept(ex)
{
    console.trace();
    return {
        type:EL_FETCH_FAILED,
        descr:ex
    }

}

export function novEnterSite(id, option1) {
    return {
        type: NOV_ENTERSITE,
        userid: id,
        option1: option1
    }
}

export function novPointsList() {
    return {
        type: NOV_POINTSLIST
    }
}


export function pointListLoaded(list) {
    return {
        type: NOV_POINTSLISTS_LOADED,
        list:list
    }
}



export function novTourList(id=0) {
    return {
        type: NOV_TOURLIST,
        tourid:id
    }
}


export function tourListLoaded(list, id=0) {
    return {
        type: NOV_TOURLISTLOADED,
        list:list,
        tourid:id
    }
}


export function showEditPoint(id, backtourid=0) {
    return {
        type: ACT_EDITPOINT,
        id:id, backtourid
    }
}


export function showEditTour(id) {
    return {
        type: ACT_EDITTOURS,
        id:id
    }
}

export function changeActiveWindow(id)
{
    return{
        type:NOV_CHANGEACTIVEWINDOW,
        id:id
    }
}



export function actUpdatePoint(data)
{
    return{
        type:ACT_UPDATEPOINT,
        data:data
    }
}

export function actUpdateTour(data)
{
    return{
        type:ACT_UPDATETOUR,
        data:data
    }
}


export function novSavePointPicture(data, picid,pointid)
{
    return     {
        type:NOV_SAVEPOINTPICTURE,
        data:data,
        picid:picid,
        pointid:pointid,
    }
}


export function novSearchTour(line)
{
    return     {
        type:NOV_SEARCHTOUR,
        value:line
    }
}

export function searchResultLoaded(res)
{
    return     {
        type:NOV_SEARCHTOURRESULT,
        SEARCH_TOUR_RESULT:"ok",
        value:res
    }
}

export function updatePointResult(res)
{
    return     {
        type:NOV_UPDATEPOINTRESULT,
        value:res
    }
}

export function updateTourResult(res)
{
    return     {
        type:NOV_UPDATETOURRESULT,
        value:res
    }
}


export function novGetElPointDescr(id)
{
    return     {
        type:NOV_GET_ELPOINTDESCR,
        id:id
    }
}
export function novReceiveElPointDescr(res)
{
    return     {
        type:NOV_RECEIVE_ELPOINTDESCR,
        value:res
    }
}

export function novAddNewPoint(data)
{
    return     {
        type:NOV_ADDNEWPOINT,
        data:data
    }

}

export function reloadAndOpenPoint(id, count=0)
{
    return     {
        type:RELOADPOINTANDOPEN,
        id:id,
        count:count
    }
}
export function reloadPoint(id)
{
    return     {
        type:NOV_LOADPOINT,
        id:id
    }
}
export function delPoint(id)
{
    return     {
        type:NOV_DELPOINT,
        id:id
    }
}

export function dePublishPoint(id)
{
    return     {
        type:NOV_DEPPOINT,
        id:id
    }
}


export function publishPoint(id)
{
    return     {
        type:NOV_PUBPOINT,
        id:id
    }
}



export function closeWindow()
{
    return {
        type: WIN_NOWINDOW,
    }
    }

