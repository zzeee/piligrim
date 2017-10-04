/**
 * Created by Zienko on 02.06.2017.
 */
import React, {Component} from 'react';
import YaPay from './YaPay.js';
import EU from './ElitsyUtils.js';
import Button from 'react-bootstrap/lib/Button';
import {connect} from 'react-redux';
import {novSaveUserInfo, NOV_SAVEUSERINFO}  from  './actions/actions.js';


function FormatTour(props) {
    let res = "";
    if (props.status == 1) res = "Новый";
    if (props.status == 2) res = "Оформление";

    if (props.status == 3) res = "Оплачен";

    return <span>{res}</span>

}


class Ucorder extends Component {
    constructor(props) {
        super(props);
//        console.log("ucorder",props);

    }

    render() {
        let line = this.props.line;
        if (parseInt(this.props.user) == 0) return <div>Произошла ошибка</div>
        let pc = <div className="row"><Button onClick={() => {
            window.open(window.location.origin+'/palomnichestvo/printtour/' + this.props.user + '/v/' + line.order_id);
        }
        } bsStyle="primary">Скачать посадочный купон</Button></div>;
        //console.log(this.props.status, "-status");
        if (parseInt(this.props.status) < 3 && line.status<3) pc = <span></span>;
        let eshow = 1;

        if (this.props.status >= 3) eshow = 0;
        if (line.status>=3) eshow=0;


        return <div className="row">
            <div className="col-xs-12 col-md-12">
                <div className="caption">
                    <h3>{line.title}</h3> <FormatTour status={line.status}/>; <span>Мест: {line.cnt} .</span><span>
                Полная стоимость: {line.psum} руб;
                </span>
                    <span>дата
                        отправления: {line.date}</span>
                </div>
                <div href={EU.getOrderURL(this.props.user, line.order_id)}
                     className="thumbnail col-md-6 img-responsive">
                    <img src={EU.getPicURL(line.mainfoto)} alt="..."/>
                </div>
                <div className="row">
                    <div className="container"><YaPay
                        show={eshow }
                        sum={line.prepaysum}
                        descr={line.title + "(" + line.id + ")*" + line.cnt + " " + line.date + " " + line.order_id}/>
                    </div>
                </div>

                <p>{pc}
                </p>

            </div>

        </div>;

    }
}
function mapStateToProps(state) {
    //console.log("user info state-main", state);
    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            user_email,
            user_id,
            user_name,
            user_phone,
            NOV_STATUS
        } = state.novstate
        return {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            NOV_STATUS,
            user_email,
            user_id,
            user_name,
            user_phone,
        }
    }
}

export default connect(mapStateToProps)(Ucorder);

