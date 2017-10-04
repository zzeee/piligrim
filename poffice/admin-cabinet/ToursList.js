/**
 * Created by a.zienko on 23.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import {showEditTour, ACT_EDITTOURS, ACT_TOURSLIST} from '../actions/actions.js';
import NewTour from './newtour.js';
import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';


class ToursList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            currenttype:  0
        };
    }
    render() {
       if (this.props.active_window != ACT_TOURSLIST) return <span></span>
        const getList = function (number) {
            if (this.state.currenttype != 0 && number.type != this.state.currenttype) return <div></div>

            let visible = "", reqvis = "";
            //console.log(number);

            if (parseInt(number.visible)==1 && this.props.iseditor && this.props.isadmin) visible =<button title="Опубликовано. Снять с публикации" onClick={(e) => this.props.dispatch(actc.dePublishTour(number.id))}><span                     className="glyphicon glyphicon-minus" aria-hidden="true"></span></button>;
            else if (parseInt(number.visible) == 1 && !this.props.isadmin) visible =<span className="glyphicon glyphicon-ok" aria-hidden="true"></span>;


            if (number.reqvis && parseInt(number.reqvis) == 1 && number.visible != 1) {
                if (this.props.iseditor || this.props.isadmin)
                    reqvis = <button onClick={(e) => this.props.dispatch(actc.publishTour(number.id))}>Разрешить<span
                        className="glyphicon glyphicon-refresh"></span></button>
                else reqvis = <span>Запрос</span>
            }

            if (number.reqvis && parseInt(number.reqvis) != 1 && number.visible != 1 && this.props.isadmin) {
                visible = <button onClick={(e) => this.props.dispatch(actc.publishTour(number.id))}>{number.reqvis}Опубликовать</button>
            }

            let del = "";
            let dp = "";
            let ap = "";
            let pub = "";
            let href="";
            let tpref="";
            if (number.type==1) tpref="palomnik";

            let lookatsite =
                <a target="_blank" href={`/palomnichestvo/tours/${tpref}/${number.id}`}>Сайт</a>
            let link = <a className="lead" style={{cursor: "pointer"}}
                          onClick={(event) => this.props.dispatch(showEditTour(number.id))}>{number.title}</a>

            if (this.props.isadmin) del =
                <button title="Удалить" type="button" onClick={(e) => this.props.dispatch(actc.deleteTour(number.id))}
                        className="btn btn-default btn-xs"><span
                    className="glyphicon glyphicon-remove" aria-hidden="true"></span></button>;

            if (this.props.isadmin && parseInt(number.visible) != 1) pub =
                <button onClick={(e) => this.props.dispatch(actc.publishTour(number.id))}><span
                    className="glyphicon glyphicon-thumbs-up"></span></button>
            ;


            return <div>{pub}{visible}{reqvis}{del}{lookatsite}  {link}</div>
        }
        let listItems = "Загрузка...";
        let all = <a className={this.state.currenttype == 0 ? "btn btn-primary" : "btn btn-default"}
                     onClick={() => this.setState({currenttype: 0})}>Все</a>

       if (this.props.toursList) listItems = this.props.toursList.map(getList.bind(this));
        return <div>
        <div className="row">{all} <a
                className={this.state.currenttype == 1 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 1})}>Паломничество</a><a
                className={this.state.currenttype == 2 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 2})}>Экскурсии</a><a
                className={this.state.currenttype == 3 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 3})}>Прогулки</a><a
                className={this.state.currenttype == 5 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 5})}>Трудничество</a><a
                className={this.state.currenttype == 4 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 4})}>Мастер-класс</a>
            <a
                className={this.state.currenttype == 6 ? "btn btn-primary" : "btn btn-default"}
                onClick={() => this.setState({currenttype: 6})}>Поход</a>
        </div>

        <div className="row">
            <div className="well col-md-4 col-md-push-8">
                <NewTour /></div>
            <div className="col-md-8 col-md-pull-4">{listItems}</div>
        </div></div>

    }
}


function mapStateToProps(state) {
    //console.log("tourslist",state);
    if (state) {

        return {
            iseditor: (state.novstate.user_iseditor == 1),
            isadmin: (state.novstate.user_isadmin == 1),
            toursList: state.novstate.toursList,
            active_window: state.novstate.active_window


        }
    }
}

export default connect(mapStateToProps)(ToursList)
