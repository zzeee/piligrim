/**
 * Created by леново on 17.06.2017.
 */
import React, {Component} from 'react';
import AvatarEditor from 'react-avatar-editor';
import * as actc from '../actions/action-creators'
import {connect} from 'react-redux';


class PicEdit extends Component {
    constructor(props) {
        super(props);
        this.state = {
            position: {x: 0.5, y: 0.5},
            scale: 1,
            rotate: 0,
            borderRadius: 0,
            preview: null,
            src: "",
            savephoto_thumb:0,
            savephoto_gallery:0,
            width: parseInt(this.props.width) > 0 ? parseInt(this.props.width) : 600,
            height: parseInt(this.props.height) > 0 ? parseInt(this.props.height) : 600

        };
        this.editor = "";
        this.setEditorRef = this.setEditorRef.bind(this);
        this.reset = this.reset.bind(this);
        this.getEditPic = this.getEditPic.bind(this);
        this.rotateLeft = this.rotateLeft.bind(this);
        this.rotateRight = this.rotateRight.bind(this);
        this.handleSave = this.handleSave.bind(this);
        this.handleReload = this.handleReload.bind(this);
        this.handleScale = this.handleScale.bind(this);
        this.handleBorderRadius = this.handleBorderRadius.bind(this);
        this.handleYPosition = this.handleYPosition.bind(this)
        this.handleXPosition = this.handleXPosition.bind(this)
        this.handleWidth = this.handleWidth.bind(this)
        this.handleHeight = this.handleHeight.bind(this)
        this.logCallback = this.logCallback.bind(this)
        this.handlePositionChange = this.handlePositionChange.bind(this)

    }

    setEditorRef(editor) {
        if (editor) this.editor = editor
    }

    reset() {
        this.state = {
            position: {x: 0.5, y: 0.5},
            scale: 1,
            rotate: 0,
            borderRadius: 0,
            preview: null,
            width: parseInt(this.props.width) > 0 ? parseInt(this.props.width) : 600,
            height: parseInt(this.props.height) > 0 ? parseInt(this.props.height) : 600
        }
    }

    getEditPic()
    {
        let image = this.editor.getImageScaledToCanvas();
        image.crossOrigin = "Anonymous";
        const img = image.toDataURL();
        return img;
    }

    handleSave() {
        const img=this.getEditPic();
        this.props.onSave(img, this.props.photoid, this.props.itype);

        const rect = this.editor.getCroppingRect()
        this.setState({
            preview: {
                img,
                rect,
                scale: this.state.scale,
                width: this.state.width,
                height: this.state.height,
                borderRadius: this.state.borderRadius
            }
        })
    }

    handleScale(e) {
        const scale = parseFloat(e.target.value)
        this.setState({scale})
    }

    rotateLeft(e) {
        e.preventDefault()
        this.setState({
            rotate: this.state.rotate - 90
        })
    }

    componentDidMount() {
        //if (this.props.height && this.props.height>0) this.setState({height:this.props.height});
        //if (this.props.width && this.props.width>0) this.setState({width:this.props.width});
        //console.log('cdm', this.props, this.state)
    }


    rotateRight(e) {
        e.preventDefault()
        this.setState({
            rotate: this.state.rotate + 90

        })

    }

    handleReload() {

        //imageType="gallery" imageId={this.props.editphoto_data.id}
        if (this.props.imageType) {
            console.log("reload", this.props.imageType);

            const {type, imageId} = this.props.imageType;
            this.props.dispatch(actc.reloadPhoto(imageId, type));
        }

    }

    handleBorderRadius(e) {
        const borderRadius = parseInt(e.target.value);
        this.setState({borderRadius})
    }


    handleXPosition(e) {
        const x = parseFloat(e.target.value);
        this.setState({position: {...this.state.position, x}})
    }


    handleYPosition(e) {
        const y = parseFloat(e.target.value);
        //      console.log('savey');
//        console.log({...this.state.position,y});
        //console.log({...this.state.position, y});
        this.setState({position: {...this.state.position, y}})
    }


    handleWidth(e) {
        const width = parseInt(e.target.value)
        this.setState({width})
   }


    handleHeight(e) {
        const height = parseInt(e.target.value)
        this.setState({height})
    }


    logCallback(e) {
    //    console.log('callback', e)
    }

    componentDidUpdate() {
        const itype = this.props.itype;
        console.log("data cupdaye:", itype, this.props,this.props.resetphoto_thumb, this.props.resetphoto_gallery);
        if ((itype === "thumb" && this.props.resetphoto_thumb) || (itype == "gallery" && this.props.resetphoto_gallery) || (itype == "super" && this.props.resetphoto_super)) {
            this.setState({
                position: {x: 0.5, y: 0.5},
                scale: 1,
                rotate: 0,
                borderRadius: 0
            });

            this.props.dispatch(actc.photoSpreaded(itype));
        }

        if (itype == "thumb" && this.props.savephoto_thumb)
        {
            //console.log('SIGNAL TO SAVE RECEIVED');
            this.props.dispatch(actc.saveAllPic_Received("thumb"));
            this.handleSave();
        }
        if (itype == "gallery" && this.props.savephoto_gallery)
        {
            //console.log('SIGNAL TO SAVE RECEIVED');
            this.props.dispatch(actc.saveAllPic_Received("gallery"));
            this.handleSave();
        }



     //   this.props.dispatch(actc.savePhototoStore(this.getEditPic(),itype));
    }

    handlePositionChange(position) {
//        console.log('Position set to', position)
        this.setState({position})
//        this.props.dispatch(actc.savePhototoStore())

    }

    render() {
        if (this.props.src && this.props.src.length < 100) console.log("PICLOAD:" + this.props.src);
        if (this.props.src == "") return <div></div>

        let borderradiuscontrol = <div className="col-md-2">Радиус закругления:<input
            name='scale'
            type='range'
            onChange={this.handleBorderRadius}
            min='0'
            max='100'
            step='1'
            defaultValue='0'
        /></div>

        let widthcontrol = <div>Ширина:
            <input
                name='width'
                type='number'
                onChange={this.handleWidth}
                min='50'
                max='900'
                step='10'
                value={this.state.width}

            /></div>;
        let savebutton = <input type='button' className="btn  btn-primary" onClick={this.handleSave}
                                value='Сохранить изображение'/>

        let heightcontrol = <div>Высота:
            <input
                name='height'
                type='number'
                onChange={this.handleHeight}
                min='50'
                max='600'
                step='10'
                value={this.state.height}
            /></div>


        let scalecontrol = <span>Масштаб: <br /><input
            name='scale' type='range'
            onChange={this.handleScale}
            min='1'
            max='2'
            step='0.01' value={this.state.scale}

        /></span>

        let xpos = <input
            name='scale'
            type='range'
            onChange={this.handleXPosition}
            min='0'
            max='1'
            step='0.01'
            value={this.state.position.x}
        />

        let ypos = <input
            name='scale'
            type='range'
            onChange={this.handleYPosition}
            min='0'
            max='1'
            step='0.01'
            value={this.state.position.y}
        />
        let rotator = <span><button onClick={this.rotateLeft}><img src="/palomnichestvo/imgi/rotateleft.png"
                                                                   width="16"/></button><button
            onClick={this.rotateRight}><img src="/palomnichestvo/imgi/rotateright.png" width="16"/></button></span>

        let saver = <span><button onClick={this.handleReload} title="Загрузить текущее изображение с сервера"><span
            className="glyphicon glyphicon-refresh" aria-hidden="true"></span></button><button
            title="Сохранить изображение" onClick={this.handleSave}><span className="glyphicon glyphicon-floppy-open"
                                                                          aria-hidden="true"></span></button></span>
        if (this.props.BorderRadiusControl == "no") borderradiuscontrol = "";
        if (this.props.WidthControl == "no") widthcontrol = "";
        if (this.props.HeightControl == "no") heightcontrol = "";

        let avataredit = <AvatarEditor
            image={this.props.src}
            ref={this.setEditorRef}
            scale={parseFloat(this.state.scale)}
            width={this.state.width}
            height={this.state.height}
            position={this.state.position}
            onPositionChange={this.handlePositionChange}
            rotate={parseFloat(this.state.rotate)}
            borderRadius={this.state.borderRadius}
            onSave={this.handleSave}
            onLoadFailure={this.logCallback.bind(this, 'onLoadFailed')}
            onLoadSuccess={this.logCallback.bind(this, 'onLoadSuccess')}
            onImageReady={this.logCallback.bind(this, 'onImageReady')}
            onImageLoad={this.logCallback.bind(this, 'onImageLoad')}
            onDropFile={this.logCallback.bind(this, 'onDropFile')}
        />;

        let result = <div className="col-md-12">
            <div className="col-md-6 col-md-pull-2">{avataredit}
            </div>
            <div className="col-md-6 col-md-push-8">
                <div className="row">
                    {scalecontrol}
                    {borderradiuscontrol}{widthcontrol}{heightcontrol}</div>
                <div className="row">
                    <div className="col-md-6">X:{xpos}</div>
                    <div className="col-md-6">Y:{ypos}</div>
                </div>
                <div className="row">{rotator}{saver}</div>
            </div>
        </div>;

        if (this.props.ControlLocation == "top") result = <div className="col-md-12">
            <div className="row">
                <span className="col-md-pull-1">{rotator}{saver}</span>
                <span className="col-md-3">{scalecontrol}</span>
                {borderradiuscontrol}{widthcontrol}{heightcontrol}
                <span className="col-md-2" style={{marginBotton:"5px"}}>X:{xpos}</span>
                <span className="col-md-2">Y:{ypos}</span>
            </div>
            <div className="row">{avataredit}</div>
        </div>;

        return result;
    }
}


function mapStateToProps(state) {
    //console.log("pic edit_s", state);

    if (state) {
        // console.log("state-main", user_id, eluser_id);

        return {
            editphoto_list_main_id: state.tourstate.editphoto_list_main_id,
            photoid: state.tourstate.editphoto_id,
            resetphoto_thumb: state.tourstate.resetphoto_thumb,
            resetphoto_gallery: state.tourstate.resetphoto_gallery,
            resetphoto_super: state.tourstate.resetphoto_super,
            savephoto_thumb: state.tourstate.savephoto_thumb,
            savephoto_gallery: state.tourstate.savephoto_gallery
        }
    }

}


export default connect(mapStateToProps)(PicEdit);

