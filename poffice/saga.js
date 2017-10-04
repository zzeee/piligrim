/**
 * Created by леново on 11.06.2017.
 */

import {call, put, fork, take, delay, select, takeEvery, takeLatest, effects} from 'redux-saga/effects'

import {
    NOV_READUSER, updateTourResult, NOV_UPDATETOURRESULT, reloadAndOpenPoint,
    LP_USERINFO, novGetElPointDescr, NOV_GET_ELPOINTDESCR, novReceiveElPointDescr,
    novFetchOk, NOV_CHANGEACTIVEWINDOW, ACT_TOURSLIST, ACT_POINTLIST,
    elOkFetch, NOV_SEARCHTOURRESULT, NOV_RECEIVE_ELPOINTDESCR, NOV_ADDNEWPOINT,
    EL_FETCH_OK, changeActiveWindow, reloadPoint, NOV_LOADPOINT, dePublishPoint, publishPoint,
    getElUserInfo, novAddNewPoint, delPoint, NOV_DELPOINT, NOV_PUBPOINT, NOV_DEPPOINT,
    LP_ORDER, showEditPoint, ACT_EDITPOINT,
    EL_USER, RELOADPOINTANDOPEN,
    detectElUser, showEditTour, ACT_EDITTOURS,
    selectUserDetected,
    novReadUser, updatePointResult,
    actUpdateTour, ACT_UPDATETOUR, NOV_UPDATEPOINTRESULT,
    NOV_POINTSLIST, NOV_TOURLIST,
    novPointsList, pointListLoaded,
    novTourList, tourListLoaded,
    novSavePointPicture, NOV_SAVEPOINTPICTURE,
    novExcept, searchResultLoaded,
    novSearchTour, NOV_SEARCHTOUR,
    NOV_FETCH_OK,
    LP_USERDETECTED, novSaveUserInfo, NOV_SAVEUSERINFO, actUpdatePoint, ACT_UPDATEPOINT
} from './actions/actions.js';


//import * as mact from './actions/actions-list.js';
import * as act from './actions/actions-list.js';
import * as actc from './actions/action-creators.js';


import ShowSearchResult from './searchResult.js';
import Comm from './comm.js';

function fetchU(param, param1) {
    return fetch(param, param1).then(response => (
        (response.status === 200) ? response.json() : Promise.reject('logon')
    ));
}

function fetchUT(param, param1) {
    return fetch(param, param1).then(response => (
        (response.status === 200) ? response.text() : Promise.reject('logon')
    ));
}

function* incrementAsync(action) {
    if (action.type == EL_FETCH_OK) {
        let qt = action.value;
        if ((typeof(qt) == "object") && qt["id"] && parseInt(qt["id"]) > 0) {
            //alert(document.cookie);
            //alert(qt["id"]);
            yield put(novReadUser(qt["id"], qt));
        }
    }
}
function* makeA2uth(action) {
    if (action.type == EL_USER) {
        let logd = "";
        let logd2 = "";
        let count = 0;
        try {
            logd = yield call(fetchU, "https://elitsy.ru/profile/api/current/", {
                    method: "GET",
                    credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(elOkFetch(logd));
            if ((typeof(logd) == "object") && logd["id"] && parseInt(logd["id"]) > 0) {
                let id = logd["id"];
                logd2 = yield call(fetchU, "/palomnichestvo/backdata/regel/" + id, {
                        method: "GET",
                        credentials: "include"
                    }
                );

                if (typeof(logd2) == "object" /*&& logd.status==200*/) yield put(novFetchOk(logd2));


            }
        }
        catch (ex) {
            yield put(novExcept(ex));

        }
    }
}
/*
 function* make3th(action) {
 if (action.type == NOV_READUSER) {
 let logd = "";
 try {
 logd = yield call(fetchU, "/palomnichestvo/backdata/regel/" + action.id, {
 method: "GET",
 credentials: "include"
 }
 );
 console.log('3th', logd, typeof logd);
 if (typeof(logd) == "object" ) yield put(novFetchOk(logd));
 }
 catch (ex) {
 yield put(novExcept(ex));
 }

 }
 }
 */
function* make5th(action) {
    if (action.type == NOV_SAVEUSERINFO) {
//        console.log("nov-save", action);
        let qarr = {
            uphone: action.phone,
            uemail: action.email,
            uname: action.username,
            userid: action.userid
        };
        let okfunc = function (e) {
            //console.log(e);
        };
        let comm = new Comm();
        comm.changeUData(qarr, okfunc, okfunc);
    }
}

function* getPoints(action) {
    if (action.type == NOV_POINTSLIST) {
        // console.log("loading points");
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/placeinfo", {
                    method: "GET",
                    credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(pointListLoaded(logd));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* getOnePoint(action) {
    if (action.type == NOV_LOADPOINT) {
        //  console.log("loading points");
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/placeinfo/" + action.id, {
                    method: "GET",
                    credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(pointListLoaded(logd));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* getOrders(action) {
    if (action.type == act.ADM_GETORDERS) {
        // console.log("loading orders");
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/showreserves", {
                    method: "GET",
                    credentials: "include"
                }
            );
            //console.log("loading orders2", logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) {

                yield put(actc.ordersLoaded(logd));

            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* getTours(action) {
    if (action.type == NOV_TOURLIST) {

        let logd = "";

        try {
            let url = "/palomnichestvo/api/tourdescr" + (action.tourid != 0 ? "/" + action.tourid : "")

            logd = yield call(fetchU, url, {
                    method: "GET", credentials: "include"
                }
            );

            //  console.log("tours", typeof(logd), logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(tourListLoaded(logd, action.tourid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* updatePoint(action) {

    if (action.type == ACT_UPDATEPOINT) {
        try {
            let logd = ""
            logd = yield call(fetchU, "/palomnichestvo/api/apiupdatepointdata", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)
            });

            //console.log('UPDATE_POINT:', action.data)
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(updatePointResult(logd.updated_id))
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* updateTour(action) {

    if (action.type == ACT_UPDATETOUR) {
        try {
            let logd = ""
            logd = yield call(fetchU, "/palomnichestvo/api/updatetourdata", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)
            });
            //console.log(typeof logd, logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                alert('Успешно сохранено');
                yield put(novTourList());
                yield put(changeActiveWindow(ACT_TOURSLIST));
            } else alert('Ошибка сохранения')
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* savePointPic(action) {
    if (action.type == NOV_SAVEPOINTPICTURE) {
        try {
            //console.log(JSON.stringify(action.data));

            let resarr =
                {
                    file: action.data,
                    pointid: action.pointid,
                    picid: action.picid
                };
            let logd = "";
            logd = yield call(fetchU, "/palomnichestvo/api/uploadpointpic", {
                method: "POST",
                credentials: "include", body: JSON.stringify(resarr)
            });
            if (typeof(logd) == "object" && logd.resid > 0) yield put(novPointsList());


        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* searchTour(action) {
    if (action.type == NOV_SEARCHTOUR && action.value && action.value.length > 2) {
        try {
            let logd = "";
            // console.log('search' + action.value);
            logd = yield call(fetchU, "/palomnichestvo/api/search/" + action.value, {
                method: "GET",
                credentials: "include"
            });
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(searchResultLoaded(logd))
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}


function* showSearchRes(action) {
    if (action.type == NOV_SEARCHTOURRESULT) {
        //console.log("SAFA", action.value);
        let rt = document.getElementById("searchresultzone");
        rt.innerHTML = ShowSearchResult(action.value);
    }

}

function* getElPointDescr(action) {
    if (action.type == NOV_GET_ELPOINTDESCR) {
        let logd = "";
        let url = "https://elitsy.ru/holy/" + action.id + "/?format=json";
        //   console.log(url);
        try {
            logd = yield call(fetchU, url, {
                    method: "GET", credentials: "include"
                }
            );
            // console.log("point", typeof(logd), logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novReceiveElPointDescr(logd));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}

function* pointDescrLoaded(action) {
    if (action.type == NOV_RECEIVE_ELPOINTDESCR) {
//    console.log("saga.res"+action.value);
        let lpd = Array.from(document.getElementsByClassName("loadpointdescr"));
        let qp = lpd[0];
        //   console.log(qp);
        window.loadedpointinfo = action.value;
        if (qp) qp.dispatchEvent(new Event("loadeddata", {datatest: 238, bubbles: true, result: action.value}));
        //Array
        //    .from(lpd)
        //  .forEach(( (element)=>{
//            this.props.dispatch(novGetElPointDescr(element.id));
        //console.log("pel",element.id);
        //    element.;

        // }).bind(this) )*/

    }

}


function* changeWindow(action) {
    if (action.type == NOV_CHANGEACTIVEWINDOW) {

    }

}

function* AddNewPoint(action) {
    if (action.type == NOV_ADDNEWPOINT) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/addnewpoint", {
                    method: "POST", credentials: "include", body: JSON.stringify(action.data)
                }
            );
            //console.log("newpoint", typeof(logd), logd);
            if (typeof(logd) == "object" && logd.resid > 0) {
                yield put(novPointsList());
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}
function* AddNewTour(action) {
    if (action.type == act.ADD_NEW_TOUR) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/addnewtour", {
                    method: "POST", credentials: "include", body: JSON.stringify(action.data)
                }
            );
            //console.log("newtour", typeof(logd), logd);
            if (typeof(logd) == "object" && logd.resid > 0) {
                yield put(novTourList());
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* sendOrder(action) {
    if (action.type == act.NOV_SENDORDER) {
        let logd;
        yield put(changeActiveWindow(act.WIN_LOADING));
        //yield put()
        let elevent = 0;

        //console.log("startorder",action.data, action.data.elevent);
        if (action.data.elevent) elevent = action.data.elevent;
        // console.log("order_res-1",  elevent);

        try {
            logd = yield call(fetchU, "/palomnichestvo/backpost/addorder", {
                    method: "POST", credentials: "include", body: JSON.stringify(action.data)
                }
            );
//            console.log("order_res0", typeof(logd), logd, elevent);

            if (typeof(logd) == "object" && logd.orderid > 0) {
                console.log("order_res", typeof(logd), logd, elevent);
                yield put(actc.novOkOrder(logd.orderid, logd, elevent));
                // yield delay(1000000);
                //  yield put (showEditPoint(logd.resid));
                //yield put (reloadAndOpenPoint(logd.resid));

            } else alert('Что-то пошло не так. Заказ не был принят. Наберите +79161243243')
        }
        catch (ex) {
            yield put(novExcept(ex));
        }


    }


}

function* reloadOpenPoint(action) {
    //NEED ONLY OPEN
    if (action.type == RELOADPOINTANDOPEN) {
        //  console.log("safs reloadopen", action.id);
        let miniarr = {};
        let pointArr = {};
        let ready = 1;
        let controlid = action.id;
//        yield delay(1000000);
        const mystate = yield select(state => state.novstate);


        /*
         while (miniarr.length == 0 && ready==0) {
         //yield put(novPointsList());
         yield getPoints();
         let pointArr = yield select(state => state.novstate.pointsList);
         console.log("p", pointArr.length);
         let newarr = Array.from(pointArr);
         miniarr = newarr.filter(((val) => {
         console.log("qmini", val, controlid);
         return val.id != controlid;
         }).bind(this));
         if (miniarr.length > 0) ready = 1;
         }
         */

        //  console.log('how to check are they loaded?', mystate);
        //if (ready==0 && action.count<100) yield put (reloadAndOpenPoint(controlid, action.count+1))
        //if (ready == 1) yield put(showEditPoint(controlid));
    }
}

function* nDelPoint(action) {
    if (action.type == NOV_DELPOINT) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/delpoint/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            //   console.log('del', logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novPointsList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* nDePublishPoint(action) {
    if (action.type == NOV_DEPPOINT) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/depublish/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novPointsList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* nPublishPoint(action) {
    if (action.type == NOV_PUBPOINT) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/publishpoint/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novPointsList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* updatePointData(action) {
    if (action.type == NOV_UPDATEPOINTRESULT) {

        //console.log('update poibnt sasga', action);
        yield put(novPointsList());
        yield put((changeActiveWindow(ACT_POINTLIST)))

    }
}


function* loadEditTour(action) {
    if (action.type == ACT_EDITTOURS) {
        //console.log("ACT_EDITTOURS", action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/gettourlocations/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            // console.log('LET',action);
            yield put(actc.getTourDates(action.id));
            yield put(actc.getTourOrganizators());
            yield put(actc.getTourServices(action.id));
            yield put(actc.getTourPhoto(action.id));

            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(actc.tourPointLoaded(logd, action.id));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* getTourReserve(action) {
    if (action.type == act.WIN_SHOWTOUR) {
        // console.log("readty", action);

        // console.log("loading orders one:" + action.dateid);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/showreserves/" + action.dateid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(actc.orderLoaded(logd, action.dateid));
                yield put(changeActiveWindow(act.WIN_SHOWTOUR));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }

}

function* AddPointToTour(action) {
    if (action.type == act.ADM_ADDPOINTTOTOUR) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/addtourlocations/" + action.tourid + "/" + action.pointid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //     console.log("apadd",logd, typeof (logd), action.tourid, typeof action.tourid);
            if (typeof(logd) == "number" /*&& logd.status==200*/) {
                yield put(showEditTour(parseInt(action.tourid)));
                // yield put(actc.orderLoaded(logd, action.dateid));
                // yield put(changeActiveWindow(act.WIN_SHOWTOUR));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }


    }

}
function* delPointFromTour(action) {
    if (action.type == act.ADM_DELPOINTFROMTOUR) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/deletetourlocations/" + action.tourid + "/" + action.pointid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //     console.log("apdel",logd);

            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(showEditTour(parseInt(action.tourid)));

                //yield call (novPointsList());
                // yield put(actc.orderLoaded(logd, action.dateid));
                // yield put(changeActiveWindow(act.WIN_SHOWTOUR));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }


    }

}


function* getTourDates(action) {

    if (action.type == act.ADM_GETTOURDATES) {
        // console.log('GET TOUR DATES', action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/gettourdate/" + action.tourid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //     console.log("apadd", logd, typeof (logd), action);

            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(actc.tourDatesLoaded(action.tourid, logd));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}

function* getTourPhoto(action) {

    if (action.type == act.ADM_GETTOURPHOTO) {
        // console.log('GET TOUR PHOTO', action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/gettourphoto/" + action.tourid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //console.log("apadd", logd, typeof (logd), action);

            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(actc.tourPhotoLoaded(action.tourid, logd));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}
function* getPointsPhoto(action) {

    if (action.type == act.ADM_GETPOINTPHOTO) {
        // console.log('GET TOUR PHOTO', action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/getplacephoto/" + action.id, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //console.log("apadd", logd, typeof (logd), action);

            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(actc.pointPhotoLoaded(action.id, logd));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}


function* getTourServices(action) {

    if (action.type == act.ADM_GETTOURSERVICES) {
        let logd = "";
        try {

            logd = yield call(fetchU, "/palomnichestvo/api/gettourservices/" + action.tourid, {
                    method: "GET",
                    credentials: "include"
                }
            );
//            console.log("tur apadd", logd, typeof (logd), action);

            if (typeof(logd) == "object" /*&& logd.status==200*/) {
                yield put(actc.tourServicesLoaded(action.tourid, logd));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}

function* delTourDateS(action) {
    if (action.type == act.ADM_DELTOURDATES) {

//        console.log("deltourdate",action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/deltourdate/" + action.dateid, {
                    method: "GET",
                    credentials: "include"
                }
            );
//            console.log("del date result", logd, typeof (logd), logd.tourid, (typeof(logd) == "object"  && logd.tourid && parseInt(logd.tourid>0)));

            if (typeof(logd) == "object" && logd.tourid && parseInt(logd.tourid) > 0) {
//                console.log("get dates");
                yield put(actc.getTourDates(logd.tourid));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}


function* delTourServices(action) {
    //console.log("outside deltourservice", action);

    if (action.type == act.ADM_DELTOURSERVICES) {

        //   console.log("deltourservice", action);

        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/deltourservice/" + action.id, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //         console.log("del service result", logd, typeof (logd), logd.tourid, (typeof(logd) == "object"  && logd.tourid && parseInt(logd.tourid>0)));

            if (typeof(logd) == "object" && logd.tourid && parseInt(logd.tourid) > 0) {
                //            console.log("get services");
                yield put(actc.getTourServices(logd.tourid));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }


    }
}
function* delTourPhoto(action) {
    //console.log("outside deltourservice", action);

    if (action.type == act.ADM_DELTOURPHOTO) {

        //  console.log("deltourphoto", action);

        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/deltourphoto/" + action.id, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //       console.log("del photo result", logd, typeof (logd), logd.tourid, (typeof(logd) == "object"  && logd.tourid && parseInt(logd.tourid>0)));

            if (typeof(logd) == "object") {
                //           console.log("get photos");
                if (action.tourid && parseInt(action.tourid) > 0) yield put(actc.getTourPhoto(action.tourid));
                if (action.placeid && parseInt(action.placeid) > 0) yield put(actc.getPointsPhoto(action.placeid));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }


    }
}


function* addTourDate(action) {
    if (action.type == act.ADM_ADDTOURDATES) {

        try {
            let logd = "";
            let param = action.data;
            param["userid"] = action.userid;
            //console.log('ADD TOUR DATE:',param);


            logd = yield call(fetchU, "/palomnichestvo/api/addtourdate", {
                method: "POST",
                credentials: "include", body: JSON.stringify(param)
            });

            //         console.log('ADD TOUR DATE:', action.data, logd)
            if (typeof(logd) == "object" /*&& logd.status==200*/)
                yield put(actc.getTourDates(action.tourid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}
function* addTourService(action) {
    if (action.type == act.ADM_ADDTOURSERVICES) {

        try {
            let logd = ""
            logd = yield call(fetchU, "/palomnichestvo/api/addtourservice", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)
            });

            //       console.log('ADD TOUR SERVICE:', action.data, logd)
            if (typeof(logd) == "object" /*&& logd.status==200*/)
                yield put(actc.getTourServices(action.tourid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* updateTourDate(action) {
    if (action.type == act.ADM_WIN_SAVEDATE) {
        try {
            //         console.log('UPDATE TOUR DATES:', action)
            let logd = ""
            logd = yield call(fetchU, "/palomnichestvo/api/updatetourdate", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)
            });

//            console.log('UPDATE TOUR DATE:', action.data, logd)
            if (typeof(logd) == "object" && logd.tourid && parseInt(logd.tourid) > 0) yield put(actc.getTourDates(logd.tourid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}
function* updateTourService(action) {
    if (action.type == act.ADM_UPDATETOURSERVICES) {
        try {
            //     console.log('UPDATE TOUR SERVICES:', action)
            let logd = ""
            logd = yield call(fetchU, "/palomnichestvo/api/updatetourservices", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)
            });

            //        console.log('UPDATE TOUR SERVICE:', action.data, logd)
            if (typeof(logd) == "object" && logd.tourid && parseInt(logd.tourid) > 0) yield put(actc.getTourServices(logd.tourid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* reloadPhoto(action) {
    if (action.type == act.ADM_EDIT_RELOADPHOTO) {
        let logd = "";

        //     console.log('reload',action)

        try {
            logd = yield call(fetchU, "/palomnichestvo/api/loadphoto/" + action.phototype + "/" + action.photoid, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //       console.log("photo res", typeof logd, action,logd);
            if (typeof(logd) == "object" && logd.photo) yield put(actc.reloadPhotoLoaded(action.phototype, action.photoid, logd.photo));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* loadAllSizesPhoto(action) {
    if (action.type == act.ADM_TOURPHOTOLOADED) {
        //     console.log(act.ADM_TOURPHOTOLOADED, action)

    }
}


function* savePhoto(action) {
    if (action.type == act.SAVE_PHOTO) {
        try {
            let resarr =
                {
                    file: action.data,
                    type: action.itype,
                    id: action.photoid,
                    tourid: action.tourid,
                    placeid: action.placeid
                };
            let res2 = resarr;
            let logd = "";
            logd = yield call(fetchU, "/palomnichestvo/api/photouploader", {
                method: "POST",
                credentials: "include", body: JSON.stringify(resarr)
            });
            if (typeof(logd) == "object") {
                yield put(actc.savePhotoSuccess(logd.id, action.itype));
                if (action.saveall == 1) {
                    yield put(actc.saveAllPic());
                    if (action.tourid && parseInt(action.tourid) > 0) yield put(actc.getTourPhoto(action.tourid));
                    if (action.placeid && parseInt(action.placeid) > 0) yield put(actc.getPointsPhoto(action.placeid));
                }
            }
        }
        catch (ex) {
         //   console.log("pic save err", ex.message);
            yield put(novExcept(ex));
        }
    }
}

function* savePhotoComment(action) {
    if (action.type == act.SAVE_PHOTO_COMMENT) {
        try {
            //console.log((action.data));
            let resarr =
                {
                    photoid: action.photoid,
                    comment: action.comment,
                    sorder: action.sorder
                };
            let logd = "";
            logd = yield call(fetchU, "/palomnichestvo/api/savephotocomment", {
                method: "POST",
                credentials: "include", body: JSON.stringify(resarr)
            });
            if (typeof(logd) == "object") {/*console.log("SAVE COMMENT STATUS:"+logd)*/
            }//yield put(actc.savePhotoSuccess(logd.id, action.itype));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* dePublishTour(action) {
    if (action.type == act.TOUR_DEPUBLISH) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/depublishtour/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novTourList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }

}

function* publishTour(action) {
    if (action.type == act.TOUR_PUBLISH) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/publishtour/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novTourList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }

}

function* deleteTour(action) {

    if (action.type == act.TOUR_DELETE) {
        //console.log("delete:", action);
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/deletetour/" + action.id, {
                    method: "GET", credentials: "include"
                }
            );
            //      console.log(logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(novTourList());
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }

}

function* getTourOrganizators(action) {
    if (action.type === act.GET_TOURORGANIZATORS) {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/gettourorganizators", {
                    method: "GET", credentials: "include"
                }
            );
            //      console.log(logd);
            if (typeof(logd) == "object" /*&& logd.status==200*/) yield put(actc.tourOrganizatorsLoaded(logd));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}

function* changeReserveStatus(action) {
    if (action.type === act.CHANGE_RESERVE_STATUS) {

        let list = {itype: action.itype, reserveid: action.reserveid};

        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/changereservestatus", {
                    method: "POST", credentials: "include", body: JSON.stringify(list)
                }
            );
            //console.log("newpoint", typeof(logd), logd);
            if (typeof(logd) == "object") {
                if (action.backtype == 'EDITRESERVEDATE') yield put(actc.showTourOrders(action.backid));
            }

        }
        catch (ex) {
            yield put(novExcept(ex));
        }
    }
}


function* sShowEditPoint(action) {
    if (action.type === ACT_EDITPOINT) {
        //console.log ('sed', action);
        yield put(actc.getPointsPhoto(action.id));
        yield put(actc.getPointAddInfo(action.id));
    }
}

function* delPointAddInfo(action) {
    if (action.type === act.ADM_DEL_POINT_ADDINFO) {
        console.log('delinfo');
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/delpointaddinfo/" + action.id, {
                    method: "GET",
                    credentials: "include"
                }
            );
            console.log(logd);

            if (typeof (logd) == "object") yield put(actc.getPointAddInfo(action.placeid));//дописать id


        }
        catch (Ex) {
        }
    }
}

function* getPointAddInfo(action) {
    if (action.type === act.ADM_GET_POINT_ADDINFO) {
 //       console.log('TEST INFO', action.point);

        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/getpointaddinfo/" + action.point, {
                    method: "GET",
                    credentials: "include"
                }
            );
            //       console.log("del photo result", logd, typeof (logd), logd.tourid, (typeof(logd) == "object"  && logd.tourid && parseInt(logd.tourid>0)));

            if (typeof(logd) == "object") {
   //             console.log("get addd", logd);

                yield put(actc.getPointAddInfoLoaded(action.point, logd));
                //if (action.tourid && parseInt(action.tourid)>0) yield put(actc.getTourPhoto(action.tourid));
                //if (action.placeid && parseInt(action.placeid )>0) yield put(actc.getPointsPhoto(action.placeid));
            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }
}


function* savePointAddInfo(action) {
    if (action.type == act.ADM_SAVE_POINT_ADDINFO) {
        try {
            let logd = "";
            let param = action.data;
            console.log('SAVE ADDINFI', action.data, JSON.stringify(action.data));

            logd = yield call(fetchU, "/palomnichestvo/api/updatepointaddinfo", {
                method: "POST",
                credentials: "include", body: JSON.stringify(action.data)

            });

            console.log('ADD TOUR DATE:', action.data, logd)
            if (typeof(logd) == "object" && logd.status == "ok")
                yield put(actc.getPointAddInfo(action.data[0].pointid));

            yield put(actc.closeEditAddInfoWindow(action.data[0].pointid));
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }

}

function* checkPhotoSuccess(action)
{
    if (action.type==act.SAVE_PHOTO_SUCCESS)
    {
        try {
            let store=yield select(state=>state);
            console.log("STORE:",store, action, store.tourstate.allpicsaved);

            if (store.tourstate.allpicsaved==1) {

                yield put(actc.closeEditPhotoWindow());
                if (action.tourid && parseInt(action.tourid) > 0) yield put(actc.getTourPhoto(action.tourid));
                if (action.placeid && parseInt(action.placeid) > 0) {
                    //yield put(actc.getPointsPhoto(action.placeid));
                    yield put(actc.showEditPoint(action.placeid));

                }


            }


        }
        catch(ex) {
            yield put(novExcept(ex));
        }
    }

}


function* getOrganizers(action)
{
 if (action.type==act.EDITTOUR_GETORGANIZERS)
 {
     let logd = "";
     try {
         logd = yield call(fetchU, "/palomnichestvo/api/gettourorganizers" , {
                 method: "GET",
                 credentials: "include"
             }
         );

         if (typeof(logd) == "object") {

             yield put(actc.organizersLoaded(logd));

         }
     }
     catch (ex) {
         yield put(novExcept(ex));
     }

 }

}

function* sendCoupon(action)
{

    if (action.type==act.SEND_COUPON)
    {
        let logd = "";
        try {
            logd = yield call(fetchU, "/palomnichestvo/api/sendcoupon/"+action.orderid , {
                    method: "GET",
                    credentials: "include"
                }
            );

            if (typeof(logd) == "object") {

                //yield put(actc.organizersLoaded(logd));
                console.log(logd);

            }
        }
        catch (ex) {
            yield put(novExcept(ex));
        }

    }


}

function* counterSaga(action) {
    // console.log("SAGA LOGGER:", action);
    //  while(true) {
//    console.log('saga', (action?action:""));

    yield takeEvery(novPointsList, getPoints)
    //yield takeEvery(elOkFetch, incrementAsync)

    yield takeEvery(detectElUser, makeA2uth)


    yield takeEvery(actc.novSendOrder, sendOrder)


    yield takeEvery(novTourList, getTours)
    yield takeEvery(actc.getOrders, getOrders)

    yield takeEvery(novSaveUserInfo, make5th)
    yield takeEvery(actUpdatePoint, updatePoint)
    yield takeEvery(actUpdateTour, updateTour)
    yield takeEvery(novSavePointPicture, savePointPic);
    yield takeEvery(novSearchTour, searchTour);
    yield takeEvery(searchResultLoaded, showSearchRes);
    yield takeEvery(novGetElPointDescr, getElPointDescr);
    yield takeEvery(novReceiveElPointDescr, pointDescrLoaded);
    yield takeEvery(changeActiveWindow, changeWindow);
    yield takeEvery(novAddNewPoint, AddNewPoint);
    yield takeEvery(showEditTour, loadEditTour);
    yield takeEvery(actc.addPointToTour, AddPointToTour);
    yield takeEvery(actc.delPointFromTour, delPointFromTour);


    yield takeEvery(delPoint, nDelPoint);
    yield takeLatest(reloadPoint, getOnePoint);

    yield takeEvery(dePublishPoint, nDePublishPoint);
    yield takeEvery(publishPoint, nPublishPoint);


    yield takeEvery(updatePointResult, updatePointData);
    yield takeEvery(actc.showTourOrders, getTourReserve);

    yield takeEvery(actc.delTourDate2, delTourDateS);
    yield takeEvery(actc.getTourDates, getTourDates);


    yield takeEvery(actc.getTourServices, getTourServices);
    yield takeEvery(actc.getTourPhoto, getTourPhoto);


    yield takeEvery(actc.delTourServices, delTourServices);
    yield takeEvery(actc.addTourDate, addTourDate);

    yield takeEvery(actc.addTourServices, addTourService);


    yield takeEvery(actc.updateTourDate, updateTourDate);
    yield takeEvery(actc.updateTourServices, updateTourService);

    yield takeEvery(actc.reloadPhoto, reloadPhoto);
    yield takeEvery(actc.tourPhotoLoaded, loadAllSizesPhoto);
    yield takeEvery(actc.savePhoto, savePhoto);
    yield takeEvery(actc.delTourPhoto, delTourPhoto);
    yield takeEvery(actc.savePhotoComment, savePhotoComment);
    yield takeEvery(actc.novAddNewTour, AddNewTour);
    yield takeEvery(actc.dePublishTour, dePublishTour);
    yield takeEvery(actc.publishTour, publishTour);
    yield takeEvery(actc.deleteTour, deleteTour);
    yield takeEvery(actc.getTourOrganizators, getTourOrganizators);
    yield takeEvery(actc.changeReserveStatus, changeReserveStatus);
    yield takeEvery(showEditPoint, sShowEditPoint);
    yield takeEvery(actc.getPointsPhoto, getPointsPhoto);
    yield takeEvery(actc.getPointAddInfo, getPointAddInfo);
    yield takeEvery(actc.delPointAddInfo, delPointAddInfo);
    yield takeEvery(actc.savePointAddInfo, savePointAddInfo);
    yield takeEvery(actc.savePhotoSuccess, checkPhotoSuccess);
    yield takeEvery(actc.getOrganizers, getOrganizers);
    yield takeEvery(actc.sendCoupon, sendCoupon);
}


export default counterSaga;