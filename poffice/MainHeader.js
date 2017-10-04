/**
 * Created by zzeee on 13.02.2017.
 */
import regeneratorRuntime from "regenerator-runtime";
import React, {Component} from 'react';
import Addtour from './addtour.js';
import EHeader from './eheader.js';
import TopWhiteHeader from './topwhiteheader.js';
import Comm from './comm.js';
import Ucmain from './uc_main';
import {connect} from 'react-redux';
import VkLogin from './vklogin.js';

import {detectElUser,closeWindow, novTourList, tourReserve,novGetElPointDescr, novEnterSite, novSearchTour} from  './actions/actions.js';

import LoadingWin from './loadingWin.js';
import Gallery from './public/gallery.js';
import * as actc from './actions/action-creators.js';



/*
 * класс авторизации и входа - возможно под переделку (выделение авторизации);
 *
 * */


class MainHeader extends Component {
    constructor(props) {
        super(props);
        let tdate = "";
        if (this.props.location) tdate = this.props.location.query["date"];
        let mode = "";
        let tid = 0;
        let option1 = "";

        if (this.props.location && this.props.location.pathname.indexOf('/users') > 0 && parseInt(this.props.params.id) > 0) //Если мы в ЛК. Добавить еще проверку адреса
        {
            mode = 'LC';
            //console.log('NOOOOOOOOOOOOO');

            this.setState({showUC:true});
            option1 = this.props.params["option1"];
            tid = this.props.params.id;
            this.props.dispatch(novEnterSite(tid,option1))
        }

        //console.log("location",this.props.location, this.props.params.id);
        if (this.props.location && this.props.location.pathname.indexOf('/tours') > 0 && parseInt(this.props.params.id) > 0) //Если мы в ЛК. Добавить еще проверку адреса
        {
           // console.log("QPRS",this.props.location.search);
            if (this.props.location.search.indexOf('action=open')>0) {
                //alert('sss');
                this.props.dispatch(actc.SwitchToReserveMode());
                this.props.dispatch(tourReserve(this.props.params.id))
                //this.addtour(this.props.params.id);
            }



        }
      this.state = {
        showModal: false,
        ename: "",
        eauth: [],
        eluser: 0,
        userid: userid,
        username: "",
        userphone: "",
        disablevk:true,
        tdate: tdate,
        showUC: (mode == 'LC'),
        showAdd: false,
        option1: option1,
        open_ident: false,
        open_type: false
      }



            if (typeof(tdate) == "undefined") tdate = "";
        this.handleSubmit = this.handleSubmit.bind(this);
        this.open = this.open.bind(this);
        this.close = this.close.bind(this);
        this.showgal = this.showgal.bind(this);
        this.vkResponse = this.vkResponse.bind(this);
        //this.handleAuth = this.handleAuth.bind(this);



        this.CloseAdd = this.CloseAdd.bind(this);
        this.CloseAddT = this.CloseAddT.bind(this);

        this.addtour = this.addtour.bind(this);
        this.parseSearch=this.parseSearch.bind(this);

        this.addhotelreserve = this.addhotelreserve.bind(this);
        let userid = 0;
        //if (window.userid > 0) userid = window.userid;

        if (tid > 0 && userid == 0) userid = tid;//TODO для отладки
        //console.log(userid);

    }

    componentDidUpdate()
    {
        //console.log(this.props, this.state);
        if (this.props.NOV_STATUS=='AUTHORIZED' && this.state.eluser_id==0)
        {
            this.setState({eluser:this.props.eluser_id, ename:this.props.eluser_name,userid:this.props.user_id})

        }

    }

    CloseAdd() {
        this.setState({showUC: false});
    }

    CloseAddT() {
        this.setState({showAdd: false});
    }

    showgal(e)
       {
           //i.preventDefault();

           try
           {
               //console.log(e, e.getAttribute('data-id'));
               let i=e.getAttribute('data-id');
               let galname=e.getAttribute('galn');
               if (i && parseInt(i) && galname && galname!="")

               {
                   this.props.dispatch(actc.showGalleryWindow(i, galname));
               }




           }
           catch(ex)
           {
              // console.log("gal error",e);
           }


//    alert('gal');
        //this.props.dispatch(actc.showGalleryWindow());
    return false;
    }



    addtour(i) {

        //this.setState({open_ident: i});
        //this.setState({open_type: 0});
       // this.OpenAdd();
        this.props.dispatch(novTourList(i));
        this.props.dispatch(tourReserve(i))
        //this.addtour(this.props.params.id);
    }

    parseSearch(line)
    {
     //console.log(line);
     if (line.length>2) this.props.dispatch(novSearchTour(line));
    }

    addhotelreserve(i) {
        this.setState({open_ident: i});
        this.setState({open_type: 1});
        this.OpenAdd();
    }

    componentDidMount() {
        let els=Array.from(document.getElementsByClassName("order"));
//        console.log(els.length);
        if (els && els.length>0)
        Array
            .from(els)
            .forEach(element => element.addEventListener('click', (e)=>this.addtour(e.target.id)))
        let searchf=Array.from(document.getElementsByName("searchf"));

        if (searchf && searchf.length>0)
        Array
            .from(searchf)
            .forEach(element => element.addEventListener('keyup', (e)=>this.parseSearch(e.target.value)))

        let gallery=Array.from(document.getElementsByClassName("photogallery"));
        //console.log('testgal', gallery, gallery.length);

        if (gallery && gallery.length>0) Array
            .from(gallery)
            .forEach(element => element.addEventListener('click', (e)=>this.showgal(e.target)))

        let lpd=Array.from(document.getElementsByClassName("loadpointdescr"));
        //console.log(lpd, lpd.length);

        if (lpd && lpd.length>0)
        Array
            .from(lpd)
            .forEach(( (element)=>{
                this.props.dispatch(novGetElPointDescr(element.id));
                  //console.log("pel",element.id);
            }).bind(this) )
    }

    componentWillMount() {

        this.props.dispatch(detectElUser());

    }

    close() {
        this.setState({showModal: false});
    }


    componentWillUpdate(nextProps, nextState) {
        //if (this.state.userid != window.userid) this.setState({userid: window.userid});

    }

    open() {
        this.setState({showModal: true});
    }




    handleSubmit(event) {
        event.preventDefault(event);
        this.handleAuth(this.email.value, this.pwd.value, this.eluser);
    }

    vkResponse(e)
    {
        if (e && e.session && e.session.user) {
            let vkuser=e.session.user;
            this.props.dispatch(actc.vkAuthorized((vkuser)));
            //console.log("vkresponse:",vkuser, vkuser.id);
        }

    }



    render()

    {
        return (<div> <EHeader /><TopWhiteHeader />
                <VkLogin
                    appId="5776099"
                    apiId="5776099"
                    disabled={this.props.reservemode!=1}
                    fields="name,email,picture"
                    callback={this.vkResponse} />
                <Addtour
                    ident={this.state.open_ident}
                    type={this.state.open_type}
                    tdate={this.state.tdate}
/><LoadingWin />
                <Ucmain show={this.state.showUC} option1={this.state.option1}
                        onZClose={this.CloseAdd} /><Gallery />
            </div>
        );
    }
}

function mapStateToProps(state) {
    console.log("state-main", state);

    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            user_email,
            user_id,
            user_name,
            user_phone,
            NOV_STATUS
            } = state.novstate

//console.log("state-main", user_id, eluser_id);

        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            NOV_STATUS,
            user_email,
            user_id,
            user_name,
            user_phone,
            reservemode:state.novstate.reservemode

        }
    }
}

export default connect(mapStateToProps)(MainHeader);