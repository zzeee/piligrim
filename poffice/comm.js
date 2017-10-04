/**
 * Created by леново on 13.03.2017.
 */
class Comm {
    loadTourData(i, okFunc, nokFunc) {
        let url = "/palomnichestvo/backdata/showtour/" + i;
        let rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
            }
        }).then(response => response.json())
            .then(json => okFunc(json), njson => nokFunc(njson));
    }

    loadHotelData(i, okFunc, nokFunc) {
        console.log('LHD');
        let url = "/palomnichestvo/backdata/showhotel/" + i;
        //  console.log(url);
        let rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
            }
        }).then(response => response.json())
            .then(json => okFunc(json), njson => nokFunc(njson));
    }


    getUserInfo(i, okFunc, nokFunc) {
        let url = "/palomnichestvo/backdata/getuserorders/" + i;
        let rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
            }
        }).then(response => response.json())
            .then(json => okFunc(json), njson => nokFunc(njson));
    }

    getUserFullInfo(i, okFunc, nokFunc) {
        let url = "/palomnichestvo/backdata/getuserfullinfo/" + i;
        let rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
            }
        }).then(response => response.json())
            .then(json => okFunc(json), njson => nokFunc(njson));
    }


    getUserOrderInfo(i, i2, okFunc, nokFunc) {
      //  console.log('gUOI');
        let url = "/palomnichestvo/backdata/getorderdetails/" + i + "/" + i2;
        //console.log(url);
        let rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
            }
        }).then(response => response.json())
            .then(json => okFunc(json), njson => nokFunc(njson));
    }


    sendOrder(req, okFunc, nokFunc) {
        console.log(req);
        fetch("/palomnichestvo/backpost/addorder",
            {
                method: "POST",
                credentials: "include",
                mode: "same-origin",
                headers: {
                    'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw==',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: JSON.stringify(req)
            }).then(res => {
            (res.json()).then(res => okFunc(res))
        }, res => console.log("!" + res));

    }

    changeUData(req, okFunc, nokFunc) {
        console.clear();
        console.log("changeudata");
        console.log(req, typeof(req),Array.from(req), JSON.stringify(req));
        fetch("/palomnichestvo/backpost/changeudata",
            {
                method: "POST",
                credentials: "include",
                mode: "same-origin",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: JSON.stringify(req)
            }).then(res => {
            (res.json()).then(res => okFunc(res))
        }, res => console.log("!" + res));

    }


    sendHotelOrder(req, okFunc, nokFunc) {
        console.log('sO');
        //console.log(req);
        fetch("/palomnichestvo/backpost/addhotelorder",
            {
                method: "POST",
                credentials: "include",
                mode: "same-origin",
                headers: {
                    'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw==',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: JSON.stringify(req)
            }).then(res => {
            (res.json()).then(res => okFunc(res))
        }, res => console.log("!" + res));

    }




    sendOrder2(req, okFunc, nokFunc) {
        console.log('sO2');
        console.log("res-s");
        console.log(req);
        var rreq = (JSON.stringify(req));
        console.log('1');
        // console.log(rreq);
        console.log("res-f");
        const url = "/palomnichestvo/backpost/addorder";
        fetch(url,
            {
                method: "POST",
                credentials: "include",
                mode: "same-origin",
                headers: {
                    'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
                },
                body: rreq
            })
            .then((function (response) {
                console.log(response);
                (response.text()).then((res) => okFunc(res))
            }), nokFunc);
    }


    getElitsyAuth(okFunc, nokFunc) {
        //TODO
        const url = "/profile/api/current/";
        fetch(url, {
                method: "GET", credentials: "include",
                headers: {'Accept': 'application/json', 'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='}
            }
        ).then(function (response) {
            okFunc(response.json())
        }, (resp) => nokFunc(resp));

    }


    getCurUserInfo(okFunc, nokFunc) {
      // console.log('gCUI');
        const url = "/palomnichestvo/backdata/userinfo/show";
        fetch(url, {
                method: "GET", credentials: "include",
                headers: {'Accept': 'application/json', 'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='}
            }
        ).then(function (response) {
            response.json().then(okFunc, nokFunc);
        }, (resp) => nokFunc(resp));

    }

    checkAuth(login, pwd, elid, okFunc, nokFunc) {
        console.log('cA');
        let url = "/palomnichestvo/dev/" + login + "/" + pwd            ;

        if (elid!="" && elid!="undefined") url=url+"/"+elid;
console.log(url);
        fetch(url, {
                method: "GET", credentials: "include",
                headers: {
                    'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='
                }
            }
        ).then(function (response) {
            response.json().then(okFunc, nokFunc)
        }, nokFunc);


    }

    regEl(ekUser, okFunc, nokFunc)
    {
        const url = "/palomnichestvo/backdata/regel/"+ekUser;
        fetch(url, {
                method: "GET", credentials: "include",
                headers: {'Accept': 'application/json', 'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='}
            }
        ).then(function (response) {
            response.json().then(okFunc, nokFunc);
        }, (resp) => nokFunc(resp));
    }

    getBills(ekUser, okFunc, nokFunc)
    {
        const url = "/palomnichestvo/api/userbill/"+ekUser;
        console.log(url);
        console.log('bills', url);
        fetch(url, {
                method: "GET", credentials: "include",
                headers: {'Accept': 'application/json', 'Authorization': 'Basic ZWxpdHN5OmVsaXRTeTY4Nw=='}
            }
        ).then(function (response) {
            response.json().then(okFunc, nokFunc);
        }, (resp) => nokFunc(resp));
    }


}
export default Comm;

