/**
 * Created by Zienko on 23.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

import {

    novSavePointPicture,
    changeActiveWindow,
    ACT_EDITPOINT,ACT_EDITTOURS,actUpdateTour,
    ACT_POINTLIST
} from '../actions/actions.js';
import {Field, reduxForm} from 'redux-form'
import PointsList from './points.js';
import TourDatesEdit from './tourdatesedit.js';
import TourServicesEdit from './tourservicesedit.js';
import TourPhotoEdit from './edittourphotolist.js';

class EditTour extends Component {

    constructor(props) {
        super(props);
        this.handleSubmit = this.props.handleSubmit;
    }

    componentDidUpdate()
    {
        //console.log("ETTTTTTQ", this.props);

    }

    componentUnmount()
    {
        //console.log('UNMOUNT');

    }

    render() {
        //console.log("plus",this.props);
        if (this.props.active_window != ACT_EDITTOURS) return <span></span>
        return <div>
             <form onSubmit={this.handleSubmit}>
            <div>
                <label>Название</label>
                <div>
                    <Field name="title" component="input" size={120} type="text" placeholder="Название"/>
                </div>
            </div>
            <div>
                <label>Краткое описание</label>
                <div>
                    <Field name="main_descr" component="input" size={180} type="text" placeholder="Краткое описание"/>
                </div>
            </div>
            <div>
                <label>Описание</label>
                <div>
                    <Field name="description" component="textarea" cols={180} rows={15} />
                </div>
            </div>
            <div>
                <label>Программа</label>
                <div>
                    <Field name="program" component="textarea" cols={180} rows={10} type="text" />
                </div>
            </div>
            <div>
                <label>Включено в стоимость</label>
                <div>
                    <Field name="include" component="textarea" cols={180} rows={5} type="text" />
                </div>
            </div>
            <div>
                <label>Не включено в стоимость</label>
                <div>
                    <Field name="exclude" component="textarea" cols={180} rows={5} type="text" />
                </div>
            </div>
            <div>
                <label>Базовая цена</label>
                <div>
                    <Field name="baseprice" component="input"  type="text" placeholder="Базовая цена"/>
                </div>
            </div>
            <div>
                <label>Дней</label>
                <div>
                    <Field name="blength" component="input"  type="text" placeholder="Дней"/>
                </div>
            </div>
            <div>
                <label>Ночей</label>
                <div>
                    <Field name="nights" component="input"  type="text" placeholder="Ночей"/>
                </div>
            </div>
                <div>
                    <label>Город выезда</label>
                    <div>
                        <Field name="startcity" component="select"  type="text" placeholder="Город выезда">
                            <option />
                        <option value="17">Москва</option>
                            <option value="91">Санкт-Петербург</option></Field>
                    </div>
                </div>
                
            <div>
                <button type="submit">Сохранить текст </button>
            </div>
        </form><TourPhotoEdit /><TourDatesEdit /><br /><TourServicesEdit />

            <div><PointsList noeditlink={false}  addpointtotourlink={true} delpointfromtourlink={true}  noallpoints={true} backtourid={this.props.tourid} nonewpoint={true} status={"loaded"} noshowpublish={true} noonsite={true} noshowonsite={true}  noshowdel={true}    selpoints={this.props.pluslist}  forceshow={true} /></div>

        </div>
    }


}


function mapStateToProps(state) {
    //console.log("editform tour", state);
    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            edit_id,
            edit_tid,
            tourid,
            user_email,
            user_id,
            user_name,
            user_phone,
            location_userid,
            location_option1,
            pointsList,
            toursList,
            edittour,
            save_point_result,
            active_window,
            NOV_STATUS
        } = state.novstate

        //console.log("AAAAAAAAAAA",edit_tid,edittour);
        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            toursList,
            edittour,
            edit_id,
            edit_tid,
            orderstatelist,
            tourid,
            NOV_STATUS,
            user_email,
            active_window,
            user_id,
            user_name,
            pointsList
        }
    }
}

EditTour = reduxForm({
    form: 'tourEditForm', // a unique identifier for this form

})(EditTour);

//export default connect(mapStateToProps)(EditPoint);

EditTour = connect((state) => {
   // console.log("EDITTOUR",state);
    let qres=[];
    let lres ;

 lres=    state.novstate.edittour ? state.novstate.edittour[0] : 0;
 if (lres)     lres["noOverwriteOnInitialize"]=true;

        let tp=state.tourstate.turpoints.map((e)=>{return e.placeid});
        let gt=state.points.all_points.filter (((e)=>{if (tp.indexOf(e.id)>=0)return true;}).bind(this));


//        console.log("QTQ",state);
        return {
            initialValues:  lres, //this.props.edittour
            turpoints:state.tourstate.turpoints,
            points:state.points.all_points,
            tourid: state.novstate.edit_tid,
            pluspoints:gt,
            pluslist:tp.sort(),


            enableReinitialize: true//,            edittour:state.novstate.edittour ? state.novstate.edittour[0] : 0// state.novstate.edittour[0] // pull initial values from account reducer
        }
    }// bind account loading action creator
)(EditTour);


export default connect(mapStateToProps)(EditTour);
