/**
 * Created by Zienko on 01.06.2017.
 */
import React, {Component} from 'react';
import Comm from './comm.js';
import YaPay from './YaPay.js';
import {connect} from 'react-redux';


class BillList extends Component {
    constructor(props) {
        super(props);
        this.comm = new Comm();
        this.state = {
            loaded:0,
            bills: [],
            userid: props.user,
            formatlist: ""
        };
        this.formatArray = this.formatArray.bind(this);
    }

    formatArray() {
        let blist = Array.from(this.state.bills);
        if (blist.length > 0) {
            let listItems = blist.map((number) => {
                if (number != undefined) {
                    //console.log("numb",number);
                    let line=number;
                    let descr="Оплата по счету "+line.bid + "(за " + line.name + " "+line.phone+" "+
                        line.email+" "+line.uid+")";
                    return <tr>
                        <td>{line.bid}</td>
                        <td>{line.sum}</td>
                        <td>{descr}</td>
                        <td><YaPay
                            show="1"
                            sum={line.sum} descr={descr} />


                        </td>
                    </tr>
                }
            });
            this.setState({formatlist: listItems});
        }
    }

    componentDidMount() {
         }

    componentDidUpdate() {
        if (this.props.NOV_STATUS == "AUTHORIZED" && this.props.user_id > 0 && this.state.loaded == 0) {
            this.setState({loaded: 1});
            /*
            * перенести загрузку списка счетов в сагу!
            * */

            let okPay = function (rt) {
                let qres = Array.from(rt);
                this.setState({bills: qres}, this.formatArray);
            };
            let rt = this.comm.getBills(this.props.user_id, okPay.bind(this), (res) => console.log(res));

        }
    }

            render() {

        //console.log("bills",this.props);
        if (this.state.bills.length > 0 && this.props.show==1)
            return <div><h2>Счета</h2>
                <table className="table">
                    <tr>
                        <td>id</td>
                        <td>Сумма</td>
                        <td>Комментарий</td>
                    </tr>
                    {this.state.formatlist}</table>
            </div>
        else return <span></span>
    }
}
function mapStateToProps(state) {
    //console.log("state-ya-redux", state);
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


export default connect(mapStateToProps)(BillList);
