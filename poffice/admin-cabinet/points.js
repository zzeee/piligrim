/**
 * Created by Zienko on 18.06.2017.
 */
import React, { Component } from 'react';
import { connect } from 'react-redux';
import NewPoint from './newPoint.js';
import EU from '../ElitsyUtils.js';
import {
  showEditPoint,
  delPoint,
  publishPoint,
  dePublishPoint,
  ACT_EDITPOINT,
  ACT_POINTLIST
} from '../actions/actions.js';
import * as actc from '../actions/action-creators'
import * as act from '../actions/actions'
class PointsList extends Component {
  constructor(props) {
    super(props);
    //this.parseClick=
    this.state = {
      currenttype: (this.props.noallpoints ? 1 : 0)
    };
    this.delpub = this.delpub.bind(this);
    this.pubpoint = this.pubpoint.bind(this);
    this.depubpoint = this.depubpoint.bind(this);
    this.getCityName = this.getCityName.bind(this);
  }

  getCityName(id)
 {

   //console.log(id);
   function get(e)
   {
     //console.log("EDA",e,id, e.id)


     return e.id==id
   }
   let qt="";

   if (this.props.cities && this.props.cities.length>0 && id!=0) {
    let resarr=Array.from(this.props.cities);

    qt = resarr.filter(get.bind(this));

    if (qt && qt.length>0 && qt[0].name)      qt=qt[0].name;
   }

  return id+" ("+qt+")";
 }

  pubpoint(e) {
    this.props.dispatch(publishPoint(e));
  }

  depubpoint(e) {
    this.props.dispatch(dePublishPoint(e));
  }

  delpub(e) {
    // console.log("depub", e);
    this.props.dispatch(delPoint(e))
  }

  render() {
        //console.log("points1");
    if ((this.props.status != "loaded" || this.props.active_window != ACT_POINTLIST) && !this.props.forceshow)return <span></span>;
    //console.log("points2");
    const getList = function (number) {//Строка одной точки
      //console.log("!!!",number);
      if (this.state.currenttype != 0 && number.type != this.state.currenttype) return <div></div>
      let selectedpoint = (this.props.selpoints && this.props.selpoints.indexOf((number.id)) >= 0)
      let visible = "", reqvis = "";
      if (number.visible == 1 && this.props.iseditor || this.props.isadmin) visible =
        <button title="Опубликовано. Снять с публикации" onClick={(e) => this.depubpoint(number.id)}><span
          className="glyphicon glyphicon-minus"
          aria-hidden="true"></span></button>
      else if (parseInt(number.visible) == 1 && !this.props.isadmin) visible =
        <span className="glyphicon glyphicon-ok" aria-hidden="true"></span>
      let lookatsite = "";
      let sel = "";
      let url = EU.getPMUrl(number.type, number.tname);
      lookatsite = <a target="_blank" href={url}>Сайт </a>
      //if (parseInt(number.visible) != 1) lookatsite = <span></span>
      if (this.props.selpoints && this.props.selpoints.indexOf((number.id)) >= 0) sel = <span>*</span>
      if (parseInt(number.reqvis) == 1 && parseInt(number.visible) != 1) {
        if (this.props.iseditor || this.props.isadmin)
          reqvis = <button onClick={(e) => this.pubpoint(number.id)}>Разрешить<span
            className="glyphicon glyphicon-refresh"></span></button>
        else reqvis = <span>Запрос</span>
      }
      if (parseInt(number.reqvis) != 1 && parseInt(number.visible) != 1) {
        visible = <button onClick={(e) => this.pubpoint(number.id)}><span
          className="glyphicon glyphicon-thumbs-up"></span></button>
      }
      let del = "";
      let dp = "";
      let ap = "";
      let link = <a className="lead" style={{cursor: "pointer"}}
                    onClick={(event) => this.props.dispatch(showEditPoint(number.id, this.props.backtourid))}>{number.name}({number.id}-{this.getCityName(number.cityid)})</a>;
      if (this.props.iseditor || this.props.isadmin) del =
        <button type="button" onClick={(e) => this.delpub(number.id)} className="btn btn-default btn-xs"><span
          className="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
      if (this.props.noshowpublish) {
        visible = "";
        reqvis = "";
      }
      if (this.props.noshowdel) {
        del = "";
      }
      if (this.props.noonsite) {
        lookatsite = "";
      }
      if (this.props.noeditlink) {
        link = number.name;
      }
      if (this.props.delpointfromtourlink && selectedpoint) {
        dp = <button onClick={((e) => {
          this.props.dispatch(actc.delPointFromTour(this.props.edittourid, number.id))
        }).bind(this)}>Удалить из поездки</button>
      }
      if (this.props.addpointtotourlink && !selectedpoint) {
        ap = <button onClick={((e) => {
          this.props.dispatch(actc.addPointToTour(this.props.edittourid, number.id))
        }).bind(this)}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
      }
      let result = <div>{sel}{visible}{reqvis}{del}{lookatsite}{dp}{ap}{link}
      </div>
      if (this.props.showonlyselected && sel == "") return <span></span>
      return result;
    }

    let newpoint = <NewPoint />
    if (this.props.nonewpoint) newpoint = "";
    let listItems = this.props.pointsList.map(getList.bind(this));
    let all = <a className={this.state.currenttype == 0 ? "btn btn-primary" : "btn btn-default"}
                 onClick={() => this.setState({currenttype: 0})}>Все</a>
    if (this.props.noallpoints) all = "";
    return <div>
      <div className="row">{all} <a
        className={this.state.currenttype == 1 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 1})}>Города</a><a
        className={this.state.currenttype == 2 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 2})}>Монастыри</a><a
        className={this.state.currenttype == 5 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 5})}>Святые</a><a
        className={this.state.currenttype == 7 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 7})}>Святые мощи</a><a
        className={this.state.currenttype == 8 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 8})}>Иконы</a><a
        className={this.state.currenttype == 6 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 6})}>Святые источники</a><a
        className={this.state.currenttype == 100 ? "btn btn-primary" : "btn btn-default"}
        onClick={() => this.setState({currenttype: 100})}>Места размещения</a></div>
      <div className="row">
        <div className="well col-md-4 col-md-push-8">
          {newpoint} </div>
        <div className="col-md-8 col-md-pull-4">{listItems}</div>
      </div>
    </div>
  }
}
function mapStateToProps(state) {
  //console.log("state-ya-adminAAAAA", state);
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
            location_userid,
            location_option1,
            pointsList,
            active_window,
            NOV_STATUS
          } = state.novstate;

    let cities=[];
    if (pointsList && pointsList.length>0) cities=pointsList.filter((d)=>{return (d.type==1)});
    return {
      eluser_id,
      eluser_name,
      eluser_photo,
      hotelid,
      orderstatelist,
      tourid,
      NOV_STATUS,
      user_email,
      active_window,
      user_id,
      user_name,
      pointsList,
      user_phone,
      cities,
      iseditor: (state.novstate.user_iseditor == 1),
      isadmin: (state.novstate.user_isadmin == 1),
      edittourid: state.tourstate.tur_points_loaded,
      location_userid,
      location_option1
    }
  }
}
export default connect(mapStateToProps)(PointsList)

