/**
 * Created by леново on 19.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

import * as actl from '../actions/actions-list.js';
import * as actc from '../actions/action-creators.js';
import DateEditForm from './dateeditform.js';

class TourDatesEdit extends Component {
    constructor(props) {
        super(props);
            }

    render() {
        let l="";
        if (parseInt(this.props.tur_dates_main_id)>0 && this.props.tur_dates.length>0) {
            //console.log ('tour dates render');
             l = this.props.tur_dates.map((e) => {
                return <div>#{e.id}. {e.date} {e.comment} {e.realmaxlimit} {e.elevent} {e.vkevent}
                    <button onClick={(q) => this.props.dispatch(actc.editDateWindow(parseInt(e.id), e))}><span
                        className="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                    <button onClick={((q) => {
                        this.props.dispatch(actc.delTourDate2(e.id))
                    }).bind(this)}><span className="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                </div>
            });
        }
            return <div className="well">Даты мероприятия:<br />{l}<br /><button onClick={((e)=>{console.log(e);
            this.props.dispatch(actc.AddDateWindow(parseInt(this.props.tur_dates_main_id)));
            }).bind(this)}><span className="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
              <DateEditForm />
            </div>


    }


}

function mapStateToProps(state) {
    //console.log("tour dates", state);
    if (state) {
        return {
            tur_dates_main_id: state.tourstate.tur_dates_main_id,
            tur_dates: state.tourstate.tur_dates
        }
    }

}


export default connect(mapStateToProps)(TourDatesEdit)