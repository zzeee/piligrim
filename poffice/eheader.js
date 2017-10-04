/**
 * Created by леново on 12.03.2017.
 */
/**
 * Created by Zienko on 22.02.2017.
 */
import React, {Component} from 'react';
//import logo from './logo.svg';
//import './App.css';

import {connect} from 'react-redux';

class EHeader extends Component {


    constructor(props) {
        super(props);
        this.state={
            resa:<span></span>,
            loaded:0


        }
    }


    componentWillMount() {
        // this.UpdateFromServer();

    }

    componentDidUpdate() {

        if (this.props.ELSTATUS=='AUTHORIZED' && this.state.loaded==0 )
        {
            let resA = "";
            let ename = "";
            if (this.props.eluser_name) ename = this.props.eluser_name;
            if (this.props.eluser_id > 0) resA =
                <a target="_blank" className="dev" href={"/profile/" + this.props.eluser_id + "/news"}>{ename} <img
                    src={this.props.eluser_photo} height="16"/></a>;

            this.setState({loaded:1, resa:resA})

        }
     // console.log("ELA",this.state.resa, this.state.eluser_id);

    }

    render() {
        //let elparams = this.props.params;



        let res = <div className="container.fluid">
            <header className="headerstl hidden-xs ">
                <div className="container">
                    <div className="row tline">
                        <div className="elbrand-small"><a href="https://elitsy.ru"><img
                            src="https://s3-eu-west-1.amazonaws.com/elitsy/static/images/logo-small.png"/></a></div>
                        <ul id="testtop" className="hnav">
                            <li><a href="/posts">Публикации</a></li>
                            <li><a href="/users/top">Люди</a></li>
                            <li><a href="/parish/top/">Храмы</a></li>
                            <li><a href="/communities/">Сообщества</a></li>
                            <li><a href="/events">Мероприятия</a></li>
                            <li className="dropdown"><a id="dLabel4" type="button" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false" href="#"
                                                        className="dropdown-toggle">Наши проекты<span
                                className="caret"></span></a>
                                <ul className="dropdown-menu de" aria-labelledby="dLabel4">
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="http://dialog.elitsy.ru">Вопросы батюшке</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/voprosy-psychologu/">Вопросы психологу</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/musik">Музыка</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/psychologia/">Советы психолога</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/utro">Доброе утро</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/short">Коротко о важном</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/vecher">Добрый вечер</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/watercolor/">Словесная акварель</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/pritchi/">Крылатые притчи</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/psychologia">Советы психолога</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/sober">На трезвую голову</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/musik">Духовная музыка</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/prostranstvia">ПроСтранствия</a></li>
                                    <li><a className="dem" style={{"color":"#6bb012"}} href="/videos">Видео</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Город выезда: </a><select className="select">
                                <option>Москва</option>
                                <option>Спб</option>
                                </select></li>

                        </ul>
                        <div className="righticon-small">{this.state.resa}</div>
                    </div>

                </div>
                </header>
            <div className="container">
                <div style={{
                    marginTop: "3px",
                    paddingLeft: "10px",
                    background: "rgb(250, 242, 166)",
                    paddingRight: "50px",
                    fontSize:"16px",
                    paddingTop: "5px",
                    paddingBottom: "10px",background:"#faf2a6"}}>Если вы нашли ошибку, просим обязательно написать о ней в личный чат <a href="https://elitsy.ru/profile/46564/" target="_blank">руководителю проекта</a>. Помогите сделать наш с вами проект удобным! <br />Чтобы получать уведомления о поездках - <a href="https://elitsy.ru/communities/110628">вступайте в наше сообщество</a></div>
            </div>
                
            </div>;
        return (res);
    }
}

function mapStateToProps(state) {

    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            user_email,
            user_id,
            ELSTATUS,
            NOV_STATUS
        } = state.novstate

        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            ELSTATUS,
            NOV_STATUS,
            user_email,
            user_id,
            iseditor:state.novstate.user_iseditor,
            isadmin:state.novstate.user_isadmin,
            istourmaster:state.novstate.user_istourmaster,
            isadv:state.novstate.user_isadv
                  }
    }
}


export default connect(mapStateToProps)(EHeader);

