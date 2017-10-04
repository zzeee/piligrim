/**
 * Created by Zienko on 18.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import PicEdit from './picEdit.js';
import {detectElUser, actUpdatePoint, novSavePointPicture, changeActiveWindow, ACT_EDITPOINT, ACT_POINTLIST} from './actions/actions.js';
import {Editor, EditorState, RichUtils, ContentState, convertFromHTML, convertToRaw, createFromBlockArray, } from 'draft-js';
import EditPointForm from './EditPointForm.js';

class EditPoint extends Component {
    constructor(props) {
        super(props);

        this.state =
            {
                editPoint: [],
                editorState:EditorState.createEmpty(),
                edit_point: 0,
                loaded: 0
            }
        this.onChange = (function (editorState) {
            console.log(editorState);
            this.setState({editorState})
        }).bind(this);
        this.handleKeyCommand = this.handleKeyCommand.bind(this);
        this.handleSave = this.handleSave.bind(this);

        this.handleSavePic = this.handleSavePic.bind(this);
        this.focus = () => this.refs.eeditor.focus();

    }

    handleSave(evt)
    {
        let raw="";
        if (this.state.editorState) raw=convertToRaw(this.state.editorState.getCurrentContent());
        /*Добавить сохранение текста из редактора*/
        console.log("save-form",evt, raw);
        this.props.dispatch(actUpdatePoint(evt));
    }

    handleSavePic(evt, name)
    {

        this.props.dispatch(novSavePointPicture(evt, name));
    }

    handleKeyCommand(command) {
        const newState = RichUtils.handleKeyCommand(this.state.editorState, command);
        if (newState) {
            this.onChange(newState);
            return 'handled';
        }
        return 'not-handled';
    }
    _onBoldClick() {
        this.onChange(RichUtils.toggleInlineStyle(this.state.editorState, 'BOLD'));
    }

    componentDidUpdate() {
        if (this.props.editpoint && this.props.editpoint[0] && this.props.editpoint[0].id && (parseInt(this.state.edit_point) != (this.props.editpoint[0].id))) {
            const blocksFromHTML = convertFromHTML(this.props.editpoint[0].descr);
            const state2 = ContentState.createFromBlockArray(
                blocksFromHTML.contentBlocks,
                blocksFromHTML.entityMap
            );


            console.log(this.props.editpoint[0].descr);
            this.setState({editorState:EditorState.createWithContent(state2)});
            //console.log('t-inside', this.props.editpoint);
            this.setState({editPoint: this.props.editpoint[0], loaded: 1, edit_point: this.props.editpoint[0].id});

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

        if (parseInt(this.props.edit_id) > 0) {
            return <div className="col-md-12">Редактирование точки
                    <EditPointForm onSubmit={this.handleSave} /><br/>
                    <div style={styles.root}><div style={styles.editor} onClick={this.focus}>
 <Editor ref="eeditor" placeholder={this.state.editPoint.descr} handleKeyCommand={this.handleKeyCommand}
editorState={this.state.editorState} onChange={this.onChange}/></div></div>
                        <PicEdit onSave={this.handleSavePic} src={"/palomnichestvo/img/" + this.state.editPoint.mainfoto}/>

                </div>

        } else return <span>Загрузка...</span>
    }
}
function mapStateToProps(state) {
    console.log("state-ya-admin3", state);
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
            editpoint,
            active_window,
            NOV_STATUS
        } = state.novstate

        return {
            eluser_id,
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
            location_option1
        }
    }
}

export default connect(mapStateToProps)(EditPoint);