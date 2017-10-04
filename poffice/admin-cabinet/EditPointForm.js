/**
 * Created by Zienko on 18.06.2017.
 */

import React from 'react'
import { Field, reduxForm  } from 'redux-form'
import {reloadAndOpenPoint} from '../actions/actions.js';
import {connect} from 'react-redux';
const  { DOM: { input, select, textarea } } = React;

let EditPointForm = (props) => {
    const { handleSubmit, pristine, reset, submitting, save_point_result } = props
        //console.log("AAAAAAAAAA",save_point_result, props);
    let save_point_text="";
    if (save_point_result!="") save_point_text=save_point_result;
    if (save_point_result=="ok") save_point_text="сохранено успешно";
    if (save_point_result=="SENT") save_point_text="сохраняем..";
  let cit=props.cities.map((e)=>{
    //console.log(e);

    return <option value={e.id} key={e.id}>{e.name}</option>  });
    //{cities.map(id)colorOption =>  <option value={colorOption} key={colorOption}>{colorOption}</option>)}



    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>Название</label>
                <div>
                    <Field name="name" component="input" size={120} type="text" placeholder="Название"/>
                </div>
            </div>

            <div>
                <label>Тип</label>
                <div>
                    <Field name="type" component="select">
                        <option value="1">Город</option><option value="2">Монастырь</option>
                        <option value="3">Храм, собор, часовни</option>
                        <option value="6">Святой источник</option>
                        <option value="7">Святые мощи</option>
                        <option value="8">Особо почитаемые иконы</option>
                        <option value="9">Особо почитаемые места и предметы</option>
                        <option value="10">Исторически значимые достопримечательности</option>
                        <option value="100">Паломнический центр</option>
                        <option value="5">Святой</option>
                    </Field>
                </div>
            </div>



            <div>
                <label>Краткое описание</label>
                <div>
                    <Field name="main_descr" cols={120} component="textarea" placeholder="Описание"/>
                </div>
            </div>
            <div>
                <label>Описание большое</label>
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
            </div><div>
                <label>cityid</label>
                <div>
                  <Field name="cityid" component="select">
                    <option value="">Выберите...</option>
                    {cit}
                  </Field>
                </div>
            </div>
          <div>
            <label>ID на сайте Elitsy</label>
            <div>
              <Field name="elitsy_url" component="input" type="text" placeholder="ID на сайте Elitsy"/>
            </div>
          </div>

          <div>
                <button type="submit">Сохранить текст </button>{save_point_text}
            </div>
        </form>
    )
}

function mapStateToProps(state) {
    //console.log("editPoint - editform",state);
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


        let cities=pointsList.filter((d)=>{return (d.type==1)});
    //  console.log("EF:",cities);

        let editpoint=state.novstate.editpoint;
        let lres=[];

        let controlid=state.novstate.edit_id;
        let qres=Array.from(state.novstate.pointsList).filter(((val) => {
            //console.log("mini-main", val.id, controlid, val.id == controlid);
            //if (val.id == controlid) console.log(val);
            return val.id == controlid;
        }).bind(this));



        if (qres.length>0){ lres=qres[0];/* console.log("edit point form msp:", lres);*/
            editpoint=lres;
        }



        return {
            eluser_id,
            editpoint,
            eluser_name,
            eluser_photo,
            cities,
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
    (state) =>{
        let qres=[];
        let lres={name:"тестовый тест"};
        //console.log("IMPORTANT(!)",state, state.novstate.edit_id);
        let controlid=state.novstate.edit_id;
        let aarr=Array.from(state.novstate.pointsList)
        if (aarr) {qres=aarr.filter(((val) => {return val.id == controlid;}).bind(this));}
        if (qres.length>0){ lres=qres[0];/* console.log("IMPORTANT editpointform loaded", lres);*/}
        //console.log(lres);
        if (lres.name=="тестовый тест") {
            //alert('defde');
            //this.props.dispatch(reloadAndOpenPoint(controlid))
            }

        return {
        initialValues: lres // pull initial values from account reducer
    }} // bind account loading action creator
)(EditPointForm);


export default connect(mapStateToProps)(EditPointForm);
