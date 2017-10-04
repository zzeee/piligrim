/**
 * Created by леново on 06.08.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actc from '../actions/action-creators.js';
import Modal from 'react-bootstrap/lib/Modal';
//import $ from "jquery";

class Gallery extends React.Component
{
constructor(props)
{
    super(props);

}

render()
{
    let im="";
    let fname="/palomnichestvo/img/"+this.props.gallerywindow_galname;
    if (fname && fname!="") im=<img src={fname} />;

    return (
            <Modal bsStyle="primary" bsSize="large" animation={false} dialogClassName="gallerywindow"
                   show={this.props.gallerywindow} onHide={(e) => this.props.dispatch(actc.closeGalleryWindow())}>
                <Modal.Header closeButton>
                    <Modal.Title>Фото</Modal.Title>
                </Modal.Header>
                <Modal.Body><div>{im}</div>
                </Modal.Body>
            </Modal>


    );

}


}

function mapStateToProps(state) {
    //console.log("gallery ", state);

        return {
            gallerywindow: state.public.galwindow,
            gallerywindow_id: state.public.galwindow_id,
            gallerywindow_galname: state.public.galwindow_galname
        }
    }


export default connect(mapStateToProps)(Gallery);


