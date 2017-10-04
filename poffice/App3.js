/**
 * Created by zzeee on 13.02.2017.
 */

import React, {Component} from 'react';
import LoginWindow from './login';
import {Link} from 'react-router'

import Popover from 'react-bootstrap/lib/Popover';
import Modal from 'react-bootstrap/lib/Modal';
import Button from 'react-bootstrap/lib/Button';
import Tooltip from 'react-bootstrap/lib/Tooltip';
import Form from 'react-bootstrap/lib/Form'
import FormControl from 'react-bootstrap/lib/FormControl'
import CheckBox from 'react-bootstrap/lib/CheckBox'
import FormGroup from 'react-bootstrap/lib/FormGroup'
import Col from 'react-bootstrap/lib/Col'
import ControlLabel from 'react-bootstrap/lib/ControlLabel'


/*
 //import {Link} from 'react-router';
 //import { connect } from 'react-redux'

 class App3 extends Component {
 render() {

 //    const { name, surname, age } = this.props.user
 return <div><a href="#" onClick={() => alert('test')} role="button">Вход</a>
 <LoginWindow />

 </div>;
 }
 }


 export default App3;
 */


const App3 = React.createClass({
    getInitialState() {
        return {showModal: false};
    },

    componentDidMount: function () {
        window.opener = this;
    },
    close() {
        this.setState({showModal: false});
    },

    open() {
        this.setState({showModal: true});
    },
    add()
    {
        alert("Добавление тура");
    },

    someMethod: function () {
        return 'bar';
    },
    render() {
        const popover = (
            <Popover id="modal-popover" title="popover">
                very popover. such engagement
            </Popover>
        );
        const tooltip = (
            <Tooltip id="modal-tooltip">
                wow.
            </Tooltip>
        );


        return (
            <div className="row col-xs-12 lh38 padV">

                <div>
                <div className="col-md-4 pl0 palomtop col-sm-6 col-xs-12"><Link to="/">Паломнические поездки</Link></div>
                <div className="col-md-4 col-xs-6  col-sm-6  palomphone text-right">
                    <img
                        src={"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAXCAYAAAA/ZK6/AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA29pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpBNTg4QjI3OThCMThFMzExQkNDRUQ5RTc4RjM4N0M5NCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpBRThERkJFREUyNjkxMUU2QTQ3MkIxNzlDRkQ0NDZGNiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpBRThERkJFQ0UyNjkxMUU2QTQ3MkIxNzlDRkQ0NDZGNiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2YTkwZjgwMC04YTU3LTRmYmUtOGIzNC1jOTk4MTY3MGM0MTgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NzAzNDQ4YjEtMzgzOS00ZWU4LWIxOGYtMzg2YzFlNTgyOGFhIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+emG6LgAAAShJREFUeNpi/P//PwMcVDJKAskoIN7M0P7/FgMWwAjXUMk4F0gmIcmdAWoyxa6hklEWyH6ExUB9oKZLyAJMUPoQA3bgiC7ABDSdDUgr4NDAjqmBgcGdATdYj02DMprYPyC+DsQRQPffRtfAgsXUKqDCTlxWMmERO4PHiWANd9HENAhpuIQmZopfQ/v/h0D6L5KYGSEbQOAlkpgyMRpOIImxASPThpCGiWji9bg0IKfWr0CSCyr+B4gFgf77gk/DYiAZgyQHCox90FB7AMTBQAN+IWsQA5LPcUQmJEW3/7dnRMtxq4FkCA4NIIWy6KbFAvFXXP4F4lxUDe3/fwBJe6hp2MBRVCchnCYG9TQHkugnoIH82D3Y/v8VEHMCWXHQ7FsKDmYgAAgwAB5OWngxw1tEAAAAAElFTkSuQmCC"} />
                        <Link to="tel:+74993901808">8-499-390-18-08</Link>, <Link
                        to="tel:+79161243243">8-916-124-32-43</Link>
                </div>
                <div className="col-md-2 col-lg-1 col-xs-3"><Link to="skype:andrey.zienko?call"><img
                    src="/imgi/skype.png"/></Link><Link
                    to="whatsapp:+79161243243"><img
                    src={"/imgi/viber.png"} hspace={"5"}/></Link></div>

                <div className="col-md-1 col-xs-2 col-sm-1"><span className="soctop hidden-xs hidden-sm hidden-md">Соцсети:</span>
                    <Link to="http://www.vk.com"><span className="atopvk"></span></Link>
                </div>
                <div className="col-md-2 col-xs-8 text-right pr0">
                </div>
                <Button className="btn btn-info ticket" ref={(child) => {
                    this._child = child;
                }} onClick={this.open}>Мои билеты</Button>


                <Modal show={this.state.showModal} onHide={this.close}>
                    <Modal.Header closeButton>
                        <Modal.Title>Вход на сайт</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <h4>Вход</h4>
                        <Form horizontal>
                            <FormGroup controlId="formHorizontalEmail">
                                <Col componentClass={ControlLabel} sm={2}>
                                    Email
                                </Col>
                                <Col sm={10}>
                                    <FormControl type="email" placeholder="Email"/>
                                </Col>
                            </FormGroup>

                            <FormGroup controlId="formHorizontalPassword">
                                <Col componentClass={ControlLabel} sm={2}>
                                    Пароль
                                </Col>
                                <Col sm={10}>
                                    <FormControl type="password" placeholder="Password"/>
                                </Col>
                            </FormGroup>

                            <FormGroup>
                                <Col smOffset={2} sm={10}>
                                    <CheckBox>Напомнить пароль</CheckBox>
                                </Col>
                            </FormGroup>

                            <FormGroup>
                                <Col smOffset={2} sm={10}>
                                    <Button type="submit">
                                        Вход
                                    </Button>
                                </Col>
                            </FormGroup>
                        </Form>

                    </Modal.Body>
                    <Modal.Footer>
                        <Button onClick={this.close}>Закрыть</Button>
                    </Modal.Footer>
                </Modal>
            </div>
</div>
    );
    }
    });
    export default App3;