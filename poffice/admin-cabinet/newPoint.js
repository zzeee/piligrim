/**
 * Created by леново on 24.06.2017.
 */

import React, {Component} from 'react';
import {connect} from 'react-redux';
import {novAddNewPoint} from '../actions/actions.js';



class NewPoint extends Component {
    constructor(props) {
        super(props);
        this.state={name:"", type:1}
    }

    render() {

return     <form onSubmit={(e)=>{this.props.dispatch(novAddNewPoint(this.state));e.preventDefault(); return false;}}>
    <h4>Добавление нового места</h4>
            <div className="row">
                <div className="form-group" style={{textAlign:"left"}}><label className="col-md-3" htmlFor="ph">Название:</label>
                <input className="col-md-7 " value={this.state.name} onChange={(e)=>this.setState({name:e.target.value})}
                       type="text"/>
            </div></div>
    <div className="row">
    <div className="form-group" style={{textAlign:"left"}}><label  className="col-md-3" htmlFor="atyp">Тип:</label>
        <select className="col-md-7" onChange={(e)=>this.setState({type:e.target.value})} name="atyp" id="atyp"><option value="1">Город</option><option value="2">Монастырь</option>
            <option value="3">Храм, собор, часовни</option>
            <option value="6">Святой источник</option>
            <option value="7">Святые мощи</option>
            <option value="8">Особо почитаемые иконы</option>
            <option value="9">Особо почитаемые места и предметы</option>
            <option value="10">Исторически значимые достопримечательности</option>
            <option value="100">Паломнический центр</option>

            <option value="5">Святой</option></select>
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

export default connect(mapStateToProps)(NewPoint);


