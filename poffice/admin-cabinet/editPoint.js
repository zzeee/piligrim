/**
 * Created by Zienko on 18.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import PicEdit from './picEdit.js';
import {detectElUser,delPoint,showEditTour, actUpdatePoint, novSavePointPicture, changeActiveWindow, ACT_EDITPOINT, ACT_POINTLIST} from '../actions/actions.js';
//import {Editor, EditorState, RichUtils, ContentState, convertFromHTML, convertToRaw, createFromBlockArray, } from 'draft-js';
import EditPointForm from './EditPointForm.js';
import EditPointPhotoList from './editpointphotolist.js';
import PointAddinfoList from './pointaddinfolist.js';

import EU from '../ElitsyUtils.js';


class EditPoint extends Component {
    constructor(props) {
        super(props);

        this.state =
            {
                editPoint: [],
                //editorState:EditorState.createEmpty(),
                edit_point: 0,
                edit_picture:"",
                edit_pic_pointid:0,
                loaded: 0
            };
       /*
        this.onChange = (function (editorState) {
            console.log(editorState);
            this.setState({editorState})
        }).bind(this);*/
        this.handleKeyCommand = this.handleKeyCommand.bind(this);
        this.handleSave = this.handleSave.bind(this);
this.selectImage=this.selectImage.bind(this);
        this.handleSavePic = this.handleSavePic.bind(this);
        this.focus = () => this.refs.eeditor.focus();
    }

    handleSave(evt)
    {
        let raw="";
      //  if (this.state.editorState) raw=convertToRaw(this.state.editorState.getCurrentContent());
        /*Добавить сохранение текста из редактора*/
        //console.log("save-form",evt, raw);
        this.props.dispatch(actUpdatePoint(evt));
    }

    handleSavePic(evt)
    {

        //console.log("SP",this.state);
        this.props.dispatch(novSavePointPicture(evt, this.state.edit_pic_pointid, this.state.edit_point));
    }

    handleKeyCommand(command) {
     //   const newState = RichUtils.handleKeyCommand(this.state.editorState, command);
        if (newState) {
            this.onChange(newState);
            return 'handled';
        }
        return 'not-handled';
    }

    selectImage(e)
    {
        //console.log(e);
        this.setState({edit_picture:e})
    }

    _onBoldClick() {
     //   this.onChange(RichUtils.toggleInlineStyle(this.state.editorState, 'BOLD'));
    }

    componentDidUpdate() {
        //console.log("QQQQQQQQQQQ",this.props.editpoint, this.state.edit_point, this.state);
        if (this.props.editpoint && this.props.editpoint.id && (parseInt(this.state.edit_point) != (this.props.editpoint.id))) {
            this.setState({editPoint: this.props.editpoint,
                loaded: 1,
                edit_point: this.props.editpoint.id,
                edit_picture:(this.props.editpoint.mainfoto?"/palomnichestvo/img/"+this.props.editpoint.mainfoto:""),
                edit_pic_pointid:this.props.editpoint.mainfoto_id
            });



            if (this.props.editpoint[0].descr) {
                const blocksFromHTML = convertFromHTML(this.props.editpoint[0].descr);
                const state2 = ContentState.createFromBlockArray(
                    blocksFromHTML.contentBlocks,
                    blocksFromHTML.entityMap
                );
            //     this.setState({editorState: EditorState.createWithContent(state2)});
            }
            //console.log('t-inside', this.props.editpoint);
        }
    }

    render() {
        let raw="";
        const styles = {
            root: {
                fontFamily: '\'Helvetica\', sans-serif',
                padding: 20,
                width: 600,
            },
            editor: {
                border: '1px solid #ccc',
                cursor: 'text',
                minHeight: 80,
                padding: 10,
            },
            button: {
                marginTop: 10,
                textAlign: 'center',
            },
        };
        if (this.props.active_window != ACT_EDITPOINT) return <span></span>
        //let srcimage=this.state.edit_picture;

        //console.log("IMAGE:",srcimage);
let back="";
if (this.props.backtourid && parseInt(this.props.backtourid)>0) back=<button style={{cursor: "pointer"}}
       onClick={(event) => this.props.dispatch(showEditTour(this.props.backtourid))}>Вернуться к редактированию поездки</button>;
        if (parseInt(this.props.edit_id) > 0) {
console.log(this.props.edir);

            return <div className="col-md-12">
                    <EditPointForm onSubmit={this.handleSave} /><button onClick={(e)=>this.props.dispatch(delPoint(this.props.edit_id))}>Удалить</button><br/>
<EditPointPhotoList /><PointAddinfoList />
<br />{back}<br /><button className="btn" style={{cursor: "pointer"}}
               onClick={() => this.props.dispatch(changeActiveWindow(ACT_POINTLIST))}>Вернуться к списку точек</button>
                <br />
                <a className="btn" target="_blank" href={EU.getPMUrl(this.props.edir.type, this.props.edir.tname)}>На сайт</a>
                </div>

        } else return <span>Загрузка...</span>
    }
}
function mapStateToProps(state) {
    console.log("EDIT POINT MAIN FORM", state);
    if (state) {
        const {

            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            edit_id,
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
        let editpoint=state.novstate.editpoint;
        let lres=[];

        let controlid=state.novstate.edit_id;
        let qres={};
        if (state.novstate.pointsList) {
        qres=Array.from(state.novstate.pointsList).filter(((val) => {
            //console.log("mini-main", val.id, controlid, val.id == controlid);
            //if (val.id == controlid) console.log(val);
            return val.id == controlid;
        }).bind(this));}


        if (qres.length>0){ lres=qres[0]; //console.log("edit point exported:", lres);
            editpoint=lres;
        }


        return {
            eluser_id,
            edir:editpoint,
            eluser_name,
            eluser_photo,
            hotelid,
            edit_id,
            orderstatelist,
            tourid,
            NOV_STATUS,
            editpoint,
            user_email,
            active_window,
            user_id,
            user_name,
            pointsList,
            user_phone,
            location_userid,
            location_option1,
            backtourid: state.novstate.backtourid
        }
    }
}

export default connect(mapStateToProps)(EditPoint);