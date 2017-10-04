/**
 * Created by леново on 12.03.2017.
 */
/**
 * Created by леново on 12.03.2017.
 */
/**
 * Created by Zienko on 22.02.2017.
 */
import React, { Component } from 'react';
import {Link} from 'react-router'
import {connect} from 'react-redux';
import Button from 'react-bootstrap/lib/Button';
import NoAuth from './noAuth.js';

class TopWhiteHeader extends Component {


    constructor (props)
    {
        super(props);
        this.openticket=this.openticket.bind(this);
    }


    componentWillMount()
    {
        // this.UpdateFromServer();

    }

    open()
    {
        window.opener.open();

    }

    openticket()
    {
        if (this.props.user_id && parseInt(this.props.user_id)>0) {
            let userid = this.props.user_id;
            const path = "/palomnichestvo/users/" + userid;
            window.location.assign(path);
        }


    }

    componentWillUpdate()
    {
    }

    render() {

        /*
        * если не аутентифицирован в ЕЛИЦАХ, и не аутентифицирован у нас
        * выводим кнопку "Вход"
        * иначе - мои билеты
        * */
        let resEl="";
       if (this.props.ELSTATUS=='AUTHORIZED' && this.props.eluser_id>0 || this.props.user_id>0) {resEl=<Button className="btn btn-info ticket " ref={(child) => {
                            this._child = child;
                        }} onClick={this.openticket}>Мои билеты</Button>

       }
        else {
           //console.log('111112222', );
           resEl=<NoAuth loc={window.location.pathname} />;
/*
           if (isNaN(this.props.eluser_id)  && (isNaN(this.props.user_id) || (this.props.user_id==0))) {
               resEl=<Button className="btn btn-info ticket " ref={(child) => {
                            this._child = child;
                        }} /*onClick={this.open}>Вход{ (this.props.user_id>0?this.props.user_id:"")}</Button>

           }else resEl=<div>Загрузка..</div>
       }*/
       }

       let admEl="";
        if (this.props.ELSTATUS=='AUTHORIZED' && (this.props.isadmin==1 || this.props.iseditor==1  ) && (this.props.eluser_id>0 || this.props.user_id>0))
        {admEl=<a className="btn btn-info" href="/palomnichestvo/users/adminer" type="button">*</a>

        }



//console.log(resEl);
        return (
            <div className="container" >
            <div className="row col-xs-12 lh38 padV">
                <div>
                    <div className="col-md-4 pl0 palomtop col-sm-5 col-xs-12"><a href="/palomnichestvo">Паломнические поездки</a></div><br className="hidden-md hidden-lg hidden-sm"/>
                    <div className="col-md-4 col-xs-12  col-sm-5  palomphone text-right">
                        <img style={{paddingRight:"5px"}}
                             src={"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAXCAYAAAA/ZK6/AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA29pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpBNTg4QjI3OThCMThFMzExQkNDRUQ5RTc4RjM4N0M5NCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpBRThERkJFREUyNjkxMUU2QTQ3MkIxNzlDRkQ0NDZGNiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpBRThERkJFQ0UyNjkxMUU2QTQ3MkIxNzlDRkQ0NDZGNiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2YTkwZjgwMC04YTU3LTRmYmUtOGIzNC1jOTk4MTY3MGM0MTgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NzAzNDQ4YjEtMzgzOS00ZWU4LWIxOGYtMzg2YzFlNTgyOGFhIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+emG6LgAAAShJREFUeNpi/P//PwMcVDJKAskoIN7M0P7/FgMWwAjXUMk4F0gmIcmdAWoyxa6hklEWyH6ExUB9oKZLyAJMUPoQA3bgiC7ABDSdDUgr4NDAjqmBgcGdATdYj02DMprYPyC+DsQRQPffRtfAgsXUKqDCTlxWMmERO4PHiWANd9HENAhpuIQmZopfQ/v/h0D6L5KYGSEbQOAlkpgyMRpOIImxASPThpCGiWji9bg0IKfWr0CSCyr+B4gFgf77gk/DYiAZgyQHCox90FB7AMTBQAN+IWsQA5LPcUQmJEW3/7dnRMtxq4FkCA4NIIWy6KbFAvFXXP4F4lxUDe3/fwBJe6hp2MBRVCchnCYG9TQHkugnoIH82D3Y/v8VEHMCWXHQ7FsKDmYgAAgwAB5OWngxw1tEAAAAAElFTkSuQmCC"} />
                        <Link
                        to="tel:+79161243243">8-916-124-32-43</Link>
                    </div>
                    <div className="hidden-xs col-md-1 col-sm-2 col-lg-1 col-xs-1"><Link style={{paddingRight:"5px"}} to="skype:andrey.zienko?call"><img
                        src="/palomnichestvo/imgi/skype.png"/></Link><Link style={{paddingRight:"5px"}}
                                                                     to="whatsapp:+79161243243"><img
                        src={"/palomnichestvo/imgi/viber.png"}/></Link>
                        <a  target="_blank" className="hidden-lg hidden-md" href="http://www.vk.com"><span className="atopvk"></span></a>
                    </div>
                    <div className="hidden-xs  col-md-1 col-xs-1 col-sm-1"><span className="soctop hidden-xs hidden-sm hidden-md">Соцсети:</span>
                        <a className="hidden-xs hidden-sm" href="http://www.vk.com/nov_life"><span className="atopvk"></span></a>
                    </div>
                    <div className="col-md-2 col-xs-8 text-right pr0 hidden-xs toptoptop">
                        {resEl}{admEl}</div></div></div></div>
        );

    }
}

function mapStateToProps(state) {
    if (state) {
        const {
            eluser_id,
            user_id,
            ELSTATUS,
            NOV_STATUS
        } = state.novstate
        return {
            eluser_id,
            ELSTATUS,
            NOV_STATUS,
            user_id,
            iseditor:state.novstate.user_iseditor,
            isadmin:state.novstate.user_isadmin,
            istourmaster:state.novstate.user_istourmaster,
            isadv:state.novstate.user_isadv
        }
    }
}


export default connect(mapStateToProps)(TopWhiteHeader);

