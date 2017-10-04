/**
 * Created by Zienko on 27.06.2017.
 */
import * as mact from './actions.js'
import * as act from './actions-list.js'

export function novSendOrder(order) {
    return {
        type: act.NOV_SENDORDER,
        data: order
    }
}
export function novOkOrder(order, data = "", eleventid) {

    return {
        type: act.NOV_ORDERSUCCESS,
        orderid: order,
        data: data,
        elevent: eleventid
    }
}

export function getOrders() {
    return {
        type: act.ADM_GETORDERS
    }
}
export function getTourOrders(dateid) {
    return {
        type: act.ADM_GETTOURORDERS,
        dateid: dateid
    }
}

export function getOrder(dateid) {
    return {
        type: act.ADM_GETORDER,
        dateid: dateid
    }
}

export function ordersLoaded(orders) {
    return {
        type: act.ADM_ORDERS_LOADED,
        orders: orders
    }

}

export function showTourOrders(id) {
    return {
        type: act.WIN_SHOWTOUR,
        dateid: id
    }
}

export function orderLoaded(data, dateid) {
    return {
        type: act.WIN_SHOWTOURLOADED,
        data: data,
        dateid: dateid
    }
}
export function tourPointLoaded(data, tourid) {
    return {
        type: act.ADM_TOURPOINTSLOADED,
        data: data,
        tourid: tourid
    }
}
export function delPointFromTour(tourid, pointid) {
    return {
        type: act.ADM_DELPOINTFROMTOUR,
        tourid: tourid,
        pointid: pointid
    }
}
export function addPointToTour(tourid, pointid) {
    return {
        type: act.ADM_ADDPOINTTOTOUR,
        tourid: tourid,
        pointid: pointid
    }
}

export function getTourDates(tourid) {
    return {
        type: act.ADM_GETTOURDATES,
        tourid: tourid
    }
}

export function getTourServices(tourid) {
    return {
        type: act.ADM_GETTOURSERVICES,
        tourid: tourid
    }
}
export function getTourPhoto(tourid) {
    return {
        type: act.ADM_GETTOURPHOTO,
        tourid: tourid
    }
}

export function getPointsPhoto(id) {
    return {
        type: act.ADM_GETPOINTPHOTO,id
      }
}

export function getOrganizers() {
    return {
        type: act.EDITTOUR_GETORGANIZERS
      }
}

export function organizersLoaded(list) {
    return {
        type: act.EDITTOUR_ORGANIZERS_LOADED,list
      }
}



export function tourDatesLoaded(tourid, data) {
    return {
        type: act.ADM_TOURDATESLOADED,
        tourid: tourid,
        data: data
    }
}
export function tourServicesLoaded(tourid, data) {
    return {
        type: act.ADM_TOURSERVICESLOADED,
        tourid: tourid,
        data: data
    }
}

export function tourPhotoLoaded(tourid, data) {
    return {
        type: act.ADM_TOURPHOTOLOADED,
        tourid: tourid,
        data: data
    }
}

export function pointPhotoLoaded(id, data) {
    return {
        type: act.ADM_POINTPHOTOLOADED,
        id,data
    }
}


export function delTourDate2(dateid) {

    return {
        type: act.ADM_DELTOURDATES,
        dateid: dateid
    }
}

export function delTourServices(id) {

    return {
        type: act.ADM_DELTOURSERVICES,
        id: id
    }
}

export function delTourPhoto(id, tourid, placeid) {

    return {
        type: act.ADM_DELTOURPHOTO,
        id: id,
        tourid: tourid,
        placeid: placeid
    }
}
export function addTourDate(tourid, data, userid) {
    return {
        type: act.ADM_ADDTOURDATES,
        tourid: tourid,
        data: data,
        userid:userid
    }
}
export function updateTourDate(dateid, data) {
    return {
        type: act.ADM_WIN_SAVEDATE,
        dateid: dateid,
        data: data
    }
}

export function updateTourServices(id, data) {
    return {
        type: act.ADM_UPDATETOURSERVICES,
        service_id: id,
        data: data
    }
}

export function addTourServices(tourid, data) {
    return {
        type: act.ADM_ADDTOURSERVICES,
        tourid: tourid,
        data: data
    }
}

export function editDateWindow(dateid, datearray) {
    return {
        type: act.ADM_WIN_OPENEDITDATE,
        dateid: dateid,
        data: datearray
    }
}

export function AddDateWindow(turid) {
    return {
        type: act.ADM_WIN_OPENADDDATE,
        tourid: turid
    }
}
export function AddServiceWindow(turid) {
    return {
        type: act.ADM_WIN_OPENADDSERVICE,
        tourid: turid
    }
}

export function editServiceWindow(serviceid, data) {
    return {
        type: act.ADM_WIN_OPENEDITSERVICE,
        serviceid: serviceid,
        data: data
    }
}

export function AddPhoto(turid, pointid) {
    return {
        type: act.ADM_WIN_OPENADDPHOTO,
        turid,pointid
    }
}

export function editPhotoWindow(id, data) {
    return {
        type: act.ADM_WIN_OPENEDITPHOTO,
        photoid: id,
        data: data
    }
}

export function closeEditDateWindow() {
    return {
        type: act.ADM_WIN_CLOSEDITDATE
    }

}


export function closeEditServiceWindow() {
    return {
        type: act.ADM_WIN_CLOSEDITSERVICE
    }

}

export function closeEditPhotoWindow() {
    return {
        type: act.ADM_WIN_CLOSEDITPHOTO
    }

}

export function reloadPhoto(photoid, phototype) {
    return {
        type: act.ADM_EDIT_RELOADPHOTO,
        photoid: photoid,
        phototype: phototype
    }
}


export function reloadPhotoLoaded(phototype, photoid, data) {
    return {
        type: act.ADM_PHOTOLOADED,
        phototype: phototype,
        photoid: photoid,
        data: data
    }
}

export function ActivateEditPictureWindow(photoid, phototype) {
    return {
        type: act.ADM_EDIT_PIC,
        photoid: photoid,
        phototype: phototype
    }
}

export function spreadPhoto(phototype) {
    return {
        type: act.ADM_SPREADPHOTO,
        phototype: phototype
    }
}

export function photoSpreaded(phototype) {
    return {
        type: act.ADM_SPREADED,
        phototype: phototype
    }
}

export function savePhoto(img, photoid, phototype, tourid = 0, placeid = 0, saveall=0) {
    return {
        type: act.SAVE_PHOTO,
        data: img,
        itype: phototype,
        photoid: photoid,
        tourid: tourid,
        placeid: placeid,
        saveall:saveall
    }
}
export function savePhotoSuccess(photoid, phototype) {
    return {
        type: act.SAVE_PHOTO_SUCCESS,
        photoid: photoid,
        phototype: phototype

    }
}
export function savePhototoStore(img, phototype) {
    return {
        type: act.SAVE_PHOTO_TOSTORE,
        img:img,
        phototype: phototype
    }
}
export function novAddNewTour(data) {
    return {
        type: act.ADD_NEW_TOUR,
        data:data
    }
}

export function showGalleryWindow(picid, galname) {
    return {
        type: act.GALLERYWINDOW_SHOW,
        picid:picid,
        galname:galname
    }
}

export function dePublishTour(id) {
    return {
        type: act.TOUR_DEPUBLISH,
        id
    }
}

export function closeGalleryWindow() {
    return {
        type: act.GALLERYWINDOW_HIDE

    }
}


export function publishTour(id) {
    return {
        type: act.TOUR_PUBLISH,
        id
    }
}
export function deleteTour(id) {
    return {
        type: act.TOUR_DELETE,
        id
    }
}

export function savePhotoComment(photoid, comment, sorder) {
    return {
        type: act.SAVE_PHOTO_COMMENT,
        photoid: photoid,
        comment: comment,
        sorder: sorder
    }
}

export function getTourOrganizators() {
    return {
        type: act.GET_TOURORGANIZATORS
    }
}

export function tourOrganizatorsLoaded(list) {
    return {
        type: act.TOURORGANIZATORS_LOADED,
        list
    }
}
export function saveAllPic() {
    return {
        type: act.SAVE_ALL_PIC

    }
}
export function saveAllPic_Received(itype) {
    return {
        type: act.SAVE_ALL_PIC_RECEIVED, itype
    }
}
export function SwitchToReserveMode() {
    return {
        type: act.RESERVE_MODE
    }
}
export function vkAuthorized(response) {
    return {
        type: act.VK_AUTHORIZED, response
    }
}

export function getPointAddInfo(point) {
    return {
        type: act.ADM_GET_POINT_ADDINFO, point
    }
}

export function delPointAddInfo(id, placeid) {
    return {
        type: act.ADM_DEL_POINT_ADDINFO, id, placeid
    }
}
export function editPointAddInfo(id) {
    return {
        type: act.ADM_EDIT_POINT_ADDINFO, id
    }
}

export function editPointNewAddInfo(id) {
    return {
        type: act.ADM_ADD_POINT_ADDINFO, id
    }
}
export function closeEditAddInfoWindow(id) {
    return {
        type: act.ADM_EDIT_POINT_ADDINFO_CLOSE, id
    }
}
export function savePointAddInfo(data) {
    return {
        type: act.ADM_SAVE_POINT_ADDINFO, data
    }
}

export function getPointAddInfoLoaded(pointid,loaded) {
    return {
        type: act.ADM_GET_POINT_ADDINFO_LOADED, pointid, loaded
    }
}

export function sendCoupon(orderid) {
    return {
        type: act.SEND_COUPON, orderid
    }
}
export function sendBill(orderid) {
    return {
        type: act.SEND_BILL, orderid
    }
}

export function editUserDataWindow(userid) {
    return {
        type: act.EDIT_USERDATA_WINDOW, userid
    }
}

export function changeReserveStatus(reserveid, itype, backtype, backid) {
    return {
        type: act.CHANGE_RESERVE_STATUS,
        reserveid, itype, backtype, backid
    }
}





