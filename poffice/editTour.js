/**
 * Created by Zienko on 23.06.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';
import {

    actUpdatePoint,
    novSavePointPicture,
    changeActiveWindow,
    ACT_EDITPOINT,ACT_EDITTOURS,actUpdateTour,
    ACT_POINTLIST
} from './actions/actions.js';
import {Field, reduxForm} from 'redux-form'


class EditTour extends Component {

    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(evt) {
        console.log('did', evt, this.props);


        this.props.dispatch(actUpdateTour(evt));
        evt.preventDefault();

    }

    render() {
        if (this.props.active_window != ACT_EDITTOURS) return <span></span>
        return <form onSubmit={this.handleSubmit}>
            <div>
                <label>Название</label>
                <div>
                    <Field name="title" component="input" size={120} type="text" placeholder="Название"/>
                </div>
            </div>

            <div>
                <label>Краткое описание</label>
                <div>
                    <Field name="main_descr" component="input" size={180} type="text" placeholder="Название"/>
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
                    <Field name="baseprice" component="input"  type="text" placeholder="Название"/>
                </div>
            </div>

            <div>
                <label>Дней</label>
                <div>
                    <Field name="blength" component="input"  type="text" placeholder="Название"/>
                </div>
            </div>

            <div>
                <label>Ночей</label>
                <div>
                    <Field name="nights" component="input"  type="text" placeholder="Название"/>
                </div>
            </div>




            <div>
                <button type="submit">Сохранить текст </button>
            </div>
        </form>

    }


}


function mapStateToProps(state) {
    console.log("editform tour", state);
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
    form: 'tourEditForm' // a unique identifier for this form
})(EditTour);

//export default connect(mapStateToProps)(EditPoint);

EditTour = connect((state) => {
        console.log("SSS", state);
        return {
            initialValues: state.novstate.edittour ? state.novstate.edittour[0] : 0 //this.props.edittour //state.novstate.edittour[0] // pull initial values from account reducer
        }
    }// bind account loading action creator
)(EditTour);


export default connect(mapStateToProps)(EditTour);
