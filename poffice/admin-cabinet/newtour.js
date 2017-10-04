/**
 * Created by леново on 26.07.2017.
 */
/**
 * Created by леново on 24.06.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';
import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';


class NewTour extends Component {
    constructor(props) {
        super(props);
        this.state={title:"", type:1}
    }

    render() {
        return     <form onSubmit={(e)=>{this.props.dispatch(actc.novAddNewTour(this.state));e.preventDefault(); return false;}}>
            <h4>Добавление новой поездки</h4>
            <div className="row">
                <div className="form-group" style={{textAlign:"left"}}><label className="col-md-3" htmlFor="ph">Название:</label>
                    <input className="col-md-7 " value={this.state.title} onChange={(e)=>this.setState({title:e.target.value})}
                           type="text"/>
                </div></div>
            <div className="row">
                <div className="form-group" style={{textAlign:"left"}}><label  className="col-md-3" htmlFor="atyp">Тип:</label>
                    <select className="col-md-7" onChange={(e)=>this.setState({type:e.target.value})} name="atyp" id="atyp"><option value="1">Паломническая поездка</option><option value="2">Экскурсионная поездка</option>
                        <option value="3">Пешая экскурсия</option>
                        <option value="4">Семинар</option>
                        <option value="5">Трудническая поездка</option>
                    </select>
                </div></div>
            <div className="row">
                <button type="submit">Добавить</button>
            </div>
        </form>
    }


}

function mapStateToProps(state) {
    if (state) {
        const {
            user_id
        } = state.novstate;

        return {
            user_id
        }
    }
}

export default connect(mapStateToProps)(NewTour);


