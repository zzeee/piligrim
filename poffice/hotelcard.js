/**
 * Created by zzeee on 26.04.2017.
 */


//
import React, {Component} from 'react';
import InputNumber  from 'rc-input-number';
import 'rc-input-number/assets/index.css';

import moment from 'moment/moment.js';
import Comm from './comm.js';
import RoomList from './RoomList.js';
import 'react-datepicker/dist/react-datepicker.css';
import PaymentType from './PaymentType.js';

import DatePicker from 'react-datepicker';

function FormatSumm(props)
{

    let  full="";
    let pre="";
    if (props.sum>0) full=<span>Полная стоимость: {props.sum} руб. </span>
    if (props.prepay>0) pre=<span>К оплате сейчас: {props.prepay} руб. </span>
    if (props.sum>0) return <div>{full}{pre}</div>; else return <div></div>;

}



function MiM(props) {
    let dt = props.txt;
    if (props.type == 1) return <span style={{color: "red"}}>{dt}</span>;
    if (props.type == 0) return <span style={{color: "black"}}>{props.txt}</span>;
    return <span></span>
}


function TrListItem(props) {
    return <input type="text" tabIndex="1" className="inpfam" name={"a" + props.data} id={props.data}
                  defaultValue={props.value}/>;
}

function TNList(props) {
    const travellers = props.travellers;
    if (travellers != null) {
        const listItems = travellers.map((number) => {
            if (typeof(number) != undefined) {
                //  console.log(number);
                return <TrListItem key={number.id} value={number.value} data={number.id}/>
            }
        });
        return (
            <div id={props.id}>
                {listItems}
            </div>
        )
    }
    else {
        return (<div></div>)
    }
}


class HotelCard extends Component {
    constructor(props) {
        super(props);

        this.comm = new Comm();
        const trav = [];
        trav[0] = {id: 34, value: ""};
        const pmin=500;
        const paymentI = [
            {id: 1, rec:1, value: "Предоплата "+pmin+" рублей с человека (рекомендовано)", sum: pmin},
            {id: 2, rec:0, value: "50% предоплата", sum:-300},
            {id: 3, rec:0, value: "100% предоплата", sum:-500},
            {id: 4, rec:0, value: "оплачу на месте", sum:0
            }];
        this.data="";
        this.state = {
            hid: this.props.hid,
            hotel_name: "",
            hotel_descr: "",
            hotel_photo: "",
            payment_min_price:pmin,
            totalsumm:0,
            service_id:-1,
            prepay:0,
            prepay_summ:0,
            startDate: moment(),
            endDate: moment().add(1, 'days'),
            travellers:trav,
            travellersNum:1,
            base_price:0,
            pay_variant:-1,
            room_variant: -1,
            payment: paymentI,
            warning_fio: 0,
            warning_phone:0,
            warning_room:0,
            warning_date:0,
            warning_pay:0,
            comment:"",
            phone:"",
            list:[],
            room_type:-1,
            loadedid: 0,
            fio_value:"",
            services:[]
        }
        this.handleChangeStart = this.handleChangeStart.bind(this);
        this.handleChangeEnd = this.handleChangeEnd.bind(this);
        this.handleRoomChange = this.handleRoomChange.bind(this);
        this.handlePayChange=this.handlePayChange.bind(this);
        this.handleNum=this.handleNum.bind(this);
        this.recalc= this.recalc.bind(this);
        this.handleSend=this.props.onSend.bind(this);
        this.handlePreSend=this.handlePreSend.bind(this)
        this.handleFio=this.handleFio.bind(this);
        this.handlePhone=this.handlePhone.bind(this);
    }

    handlePreSend(ev)
    {
        if (this.state.room_variant<0) {this.setState({warning_room:1});return}
        if (this.state.fio_value=="") { this.setState({warning_fio:1}); return}
        if (this.state.phone=="") { this.setState({warning_phone:1}); return}
        if (this.state.endDate<this.state.startDate) { this.setState({warning_date:1}); return}
        if (this.state.pay_variant<0) { this.setState({warning_pay:1}); return}

        const sendArray={
            room:this.state.room_variant,
            hid:this.state.hid,
            total:this.state.totalsumm,
            prepay: this.state.prepay_summ,
            fio:this.state.fio_value,
            phone:this.state.phone,
            service_id:this.state.service_id,
            sdate:moment(this.state.startDate).format("YYYY-MM-DD"),
            edate:moment(this.state.endDate).format("YYYY-MM-DD"),
            tnum:this.state.travellersNum,
            ptype:this.state.pay_variant,
            comment:this.state.comment
        }


this.handleSend(sendArray);

    }

    handleFio(event)
    {
//console.log(event);
        this.setState({fio_value: event.target.value, warning_fio:0});

    }

    handlePhone(event)
    {
//console.log(event);
        this.setState({phone: event.target.value, warning_phone:0});

    }


getSuns(dw1,dw2){
    let d1 = moment(dw1);
    let d2 = moment(dw2);
    let d3=d1;
    let dw="";
    let interval=Math.round(d2.diff(d1,'days', true));
    let i=0;
    let res=0;
    while(i<interval)
    {
        dw=d3.format("d");
        //console.log(dw);
        if (dw==5) res++;
        d3=d3.add(1, 'days');
        i++;
    }
return res;
}

    recalc()
    {
      //  console.log('recalc'+this.state.room_variant+" "+this.state.pay_variant+" "+this.state.travellersNum);
       // console.log(this.state.services);
        let sdate=this.state.startDate;
        let edate=this.state.endDate;
        let total=Math.round(edate.diff(sdate,'days', true));
        let sdiff=this.getSuns(sdate, edate);
        let p1=0;
        let p2=0;
        if (this.parseDate(this.state.startDate, this.state.endDate) && this.state.warning_date>0) {this.setState({warning_date:0});}

        try
        {
            if (this.state.room_variant>=0) {
            let lin=this.state.services[this.state.room_variant]
            p1=lin.price;
                p2=lin.price1;
           //     console.log(lin.price, lin.price1)
            }

        }catch(e) {console.log(e);}
        let tres=parseInt(p1*(parseInt(total-sdiff))+p2*sdiff)*this.state.travellersNum;

       // console.log("res:"+tres);
       if (this.state.totalsumm!=tres) this.setState({totalsumm:tres});

        //console.log("recalc:",sdate.format("MMMM Do YYYY"),edate.format("MMMM Do YYYY"), total," suns:", sdiff, "f");

    }
    handleRoomChange(param, uid) {

        this.setState({room_variant:param, warning_room:0, service_id:uid},  ()=>{this.recalc();});
      //  console.log("!!++!!!!variant="+param+"!!!++!!");
        this.recalc();
    }

    parseDate(date1, date2)
    {
        return (date2>date1);

    }

    handleNum(param)
    {
      //  console.log("ct"+param);

        if (parseInt(param)>0) this.setState({travellersNum:param}, ()=>{this.recalc();});
    }

    handlePayChange(param, sum) {
    //console.log(sum);
        this.setState({pay_variant:param, prepay_summ:sum}, ()=>{this.recalc();});
       // console.log("!!++!!!!variant="+param+"!!!++!!");
        this.recalc();
    }


    handleChangeStart(date) {

        this.setState({startDate: date}, ()=>{this.recalc();});
    }

    handleChangeEnd(date2) {
        this.setState({endDate: date2},            ()=>{this.recalc();}        );
    }

    sync() {
        let load = (function (res) {
            this.setState({hotel_name: res["common"]["title"]});
            this.setState({hotel_descr: res["common"]["descr"]});
            this.setState({hotel_foto: "/palomnichestvo/img/" + res["common"]["mainfoto"]});
            this.setState({loadedid: res["common"]["id"]});
            this.setState({services: res["services"]});
    //        console.log(res["services"][0]);
            if (res["services"][0] && res["services"][0]["price"] && typeof((res["services"][0]["price"])!='undefined')) this.setState({baseprice:res["services"][0]["price"]});
        });
        if (this.state.hid != this.state.loadedid)
            this.comm.loadHotelData(this.state.hid, load.bind(this), (res) => console.log("!" + res + "!"));

    }

    componentWillMount() {
        this.sync();
    }

    componentWillUpdate(nextProps, nextState)
    {
        //console.log('cuw dates:'+this.state.startDate.format("dddd, MMMM Do YYYY, h:mm:ss a")+" - "+this.state.endDate.format("dddd, MMMM Do YYYY, h:mm:ss a"));
        this.recalc();


    }


    render() {

        return <div>
            <input type="hidden" name="tourid" value={this.state.hid}/>
            <input type="hidden" name="userid" value={this.state.userid}/>
            <table width="100%">
                <tbody>
                <tr>
                    <td>
                        <div className="col-md-6" style={{minHeigt: "500px"}}>
                            <h4>{this.state.hotel_name}</h4><br />
                            {this.state.hotel_descr}<br />
                            <img width="200" src={this.state.hotel_foto}/><br /><br />
                            <b><MiM type={this.state.warning_room}
                                      txt="Базовая цена (будни/вых):"/>
                            </b><br />
                            <RoomList list={this.state.services} variant={this.state.room_variant} onZChange={this.handleRoomChange}/>
                            <br />
                            Число мест: <InputNumber name="numb"
                                                     onChange={this.handleNum}
                                                     style={{border: "thin solid grey"}} defaultValue={this.state.travellersNum}
                                                     max={9} min={1}/>
                        </div>
                        <div className="col-md-6 col-push-6">
                            <div className="form-group"><br /><label><MiM type={this.state.warning_date} txt="1. Выберите
                                дату:" /></label><br />
                                <div className="row">
                                    <div className="col-md-5">С&nbsp;<DatePicker style={{border: "thin solid red"}}
                                                                                 id="dateStart"
                                                                                 selected={this.state.startDate}
                                                                                 selectsStart
                                                                                 dateFormat="DD.MM.YYYY"
                                                                                 startDate={this.state.startDate}
                                                                                 endDate={this.state.endDate}
                                                                                 onChange={this.handleChangeStart}
                                    /></div>
                                    <div className="col-md-6">по <DatePicker id="dateFinish"
                                                                             dateFormat="DD.MM.YYYY"
                                                                             selected={this.state.endDate}
                                                                             selectsEnd
                                                                             startDate={this.state.startDate}
                                                                             endDate={this.state.endDate}
                                                                             onChange={this.handleChangeEnd}
                                    />
                                    </div>
                                </div>
                            </div>
                            <div className="form-group"><label htmlFor="btnslist1"><MiM type={this.state.warning_fio}
                                                                                        txt="2. Укажите ФИО:"/></label>
                                <input type="text" value={this.state.fio_value} name="hotel_trav" onChange={this.handleFio} />
                                </div>
                            <div className="form-group"><label><MiM type={this.state.warning_pay}
                                                                    txt="3. Выберите тип оплаты:"/></label>
                                <PaymentType id="paymentdialog" defPrice={this.state.totalsumm}
                                             minPrice={this.state.payment_min_price}
                                             prepay={this.state.prepay}
                                             onZChange={this.handlePayChange}
                                             variant={this.state.pay_variant}
                                             payment={this.state.payment}/>
                            </div>
                            <div className="form-group"><label htmlFor="ph"><MiM type={this.state.warning_phone}
                                                                                 txt="4. Укажите Ваш номер телефона"/></label>&nbsp;
                                <input id="phnum" name="phonenum" value={this.state.phone} onChange={this.handlePhone}
                                       type="text"/>
                            </div>
                            <div ><FormatSumm sum={this.state.totalsumm} prepay={this.state.prepay_summ} />
                            </div>
                            <div style={{paddingLeft: "15px", paddingRight: "15px"}} className="form-group"><label
                                htmlFor="txtarea">Вопросы и пожелания?</label><textarea
                                id="txtarea"  value={this.state.comment} onChange={(event)=>this.setState({comment: event.target.value})} className="form-control"
                                rows="3"></textarea>
                            </div>
                            <button type="button"  onClick={this.handlePreSend} className="btn btn-info">Забронировать!</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    }

}

export default HotelCard;
