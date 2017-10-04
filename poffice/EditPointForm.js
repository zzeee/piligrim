/**
 * Created by Zienko on 18.06.2017.
 */

import React from 'react'
import { Field, reduxForm  } from 'redux-form'
import {connect} from 'react-redux';
const  { DOM: { input, select, textarea } } = React;

let EditPointForm = (props) => {
    const { handleSubmit, pristine, reset, submitting, save_point_result } = props
        //console.log("AAAAAAAAAA",save_point_result, props);
    let save_point_text="";
    if (save_point_result!="") save_point_text=save_point_result;
    if (save_point_result=="ok") save_point_text="сохранено успешно";
    if (save_point_result=="SENT") save_point_text="сохраняем..";

    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>Название</label>
                <div>
                    <Field name="name" component="input" size={120} type="text" placeholder="Название"/>
                </div>
            </div>
            <div>
                <label>Краткое описание</label>
                <div>
                    <Field name="main_descr" cols={120} component="textarea" placeholder="Описание"/>
                </div>
            </div>
            <div>
                <label>Описание</label>
                <div>
                    <Field name="descr" component="textarea"  cols={120} rows={20} placeholder="Описание"/>
                </div>
            </div>
            <div>
                <label>Автор</label>
                <div>
                    <Field name="d_author" component="input" cols={120} type="textarea" placeholder="Автор"/>
                </div>
            </div>

            <div>
                <label>Адрес (если применимо)</label>
                <div>
                    <Field name="address" component="input" type="text" placeholder="Адрес (если применимо)"/>
                </div>
            </div>
            <div>
                <label>Широта (lat)</label>
                <div>
                    <Field name="lat" component="input" type="text" placeholder="Широта (lat)"/>
                </div>
            </div>
            <div>
                <label>Долгота (lon)</label>
                <div>
                    <Field name="lon" component="input" type="text" placeholder="Долгота (lon)"/>
                </div>
            </div>
            <div>
                <label>CityId</label>
                <div>
                    <Field name="cityid" component="input" type="text" placeholder="Cityid"/>
                </div>
            </div>
            <div>
                <button type="submit">Сохранить текст </button>{save_point_text}
            </div>
        </form>
    )
}

function mapStateToProps(state) {
    console.log("state-ya-admin - editform",state);
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
            save_point_result,
            active_window,
            NOV_STATUS
        } = state.novstate

        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            save_point_result,
            edit_id,
            orderstatelist,
            tourid,
            NOV_STATUS,
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
EditPointForm = reduxForm({
    form: 'pointEditForm' // a unique identifier for this form
})(EditPointForm);

//export default connect(mapStateToProps)(EditPoint);

EditPointForm = connect(
    state => ({
        initialValues: state.novstate.editpoint[0] // pull initial values from account reducer
    }) // bind account loading action creator
)(EditPointForm);


export default connect(mapStateToProps)(EditPointForm);
