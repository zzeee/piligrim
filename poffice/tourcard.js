import React, {Component} from 'react';
//import logo from './logo.svg';
//import './App.css';
import Comm from './comm.js';
import {connect} from 'react-redux';
import * as act from './actions/actions.js'


function MiM(props) {
    let dt = props.txt;
    if (props.type == 1) return <span style={{color: "red"}}>{dt}</span>;
    if (props.type == 0) return <span style={{color: "black"}}>{props.txt}</span>;
}

class ShowMinus extends React.Component {
    render() {
        return <div>{this.props.v1}</div>
    }
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

function ListItem(props) {
    return <option value={props.data}>{props.value}</option>;
}

function DateNumberList(props) {
    function handleSelect(event) {
        //   console.log("sel", event);
        let rt = Array.from(props.dateList).filter(((e) => {
            //console.log(e);
            if (e.id == event.target.value) return true;
        }).bind(this));
        if (rt && rt.length > 0 && rt[0].elevent) props.onChange(rt[0]);
    }

    const dates = props.dateList;
    if (dates) {
        const listItems = dates.map((number) => {
            if (typeof(number) != undefined)
                return <ListItem key={number.id} value={number.date} pricefull={number.pricefull} data={number.id}
                                 elevent={number.elevent}/>
        });
        const fst = props.defDate;
        let fstdate = 0;
        if (fst) {
            fstdate = fst;
        }
        return (<span><select defaultValue={fstdate} onChange={handleSelect} id={props.id}>
                {listItems}
            </select></span>
        );
    } else return (<div></div>);
}

function AddList(props) {
    const tl = props.addservices;
    //console.log("tl:",tl);
    if (!tl) {
        //  console.log('NO ADDSERVICES!')
    }
    if (tl) {
        const listItems = tl.map((number) => {
            if (typeof(number) != undefined) {
                return <div className="checkbox"><label htmlFor={number.id}>
                    <input type="checkbox" onClick={props.onZChange} className="addNam" name={number.price}
                           id={number.id} defaultValue={number.title}/>
                    {number.title} - {number.price} ₽;
                </label>
                </div>;
            }
        });
        return (
            <div id={props.id}>
                {listItems}
            </div>
        )
    }
    else {
        return (<div id={props.id}></div>)
    }
}


function PaymentType2(props) {
    let dod = (function (event) {
        props.onZChange(event.target.id);
    }).bind(this);
    const payment = props.payment;
    if (payment != null) {
        const listItems = payment.map(((number) => {
            if (typeof(number) != undefined) {
                let value = number.value;
                if (number.sum < 0) value = (number.value + "  - " + props.defPrice + " руб.");
                let checked = (number.id == (props.payvariant + 1));
                let res = <input checked type="radio" onChange={dod} name="payment" id={number.id}/>
                if (checked == 0) res = <input type="radio" onChange={dod} name="payment" id={number.id}/>
                return <div className="radio"><label>{res}{value}</label></div>
            }
        }).bind(this));
        return (
            <div id={props.id}>
                {listItems}
            </div>
        )
    }
    else return <div></div>
}

class TourCard extends Component {
    constructor(props) {
        super(props);
        this.comm = new Comm();
        this.handlePlus = this.handlePlus.bind(this);
        this.handleMinus = this.handleMinus.bind(this);
        this.loadFromProps = this.loadFromProps.bind(this);
        this.paymentFunc = this.paymentFunc.bind(this);
        this.aListFunc = this.aListFunc.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handlePhone = this.handlePhone.bind(this);
        this.getPrepayArr = this.getPrepayArr.bind(this);
        this.setedate = this.setedate.bind(this);

        const trav = [];
        trav[0] = {
            id: (this.props.user_id ? this.props.user_id : Math.ceil(Math.random() * 1000)),
            value: (this.props.user_name ? this.props.user_name : " ")
        };
        //console.log("checkphone", this.props.user_phone, !this.props.user_phone);
        let newphone = "";
        if (this.props.user_phone) newphone = this.props.user_phone;
        //console.log("setphone",newphone);

        this.state =
            {
                tourid: this.props.tourid,
                userid: this.props.userid,
                tourdata: window.tourdata,
                tdate: this.props.tdate,
                travellers: trav,
                warning_fio: this.props.warning_fio,
                warning_phone: this.props.warning_phone,
                travellersNum: 1,
                loadedid: 0,
                title: "",
                baseprice: 0,
                main_descr: "",
                payment: this.getPrepayArr(500),
                payvariant: 0,
                payperone: 500,
                addSum: 0,
                elevent: 0,
                uloaded: 0,
                vkid: "",
                emid: "",
                oelid: "",
                nrid: "",
                email:this.props.user_email,
                phone: newphone
            };
    }

    handlePhone() {
        //console.log('set phone', event.target.value);
        this.setState({phone: event.target.value, warning_phone: 0});
    }

    handlePlus(e) {
        e.preventDefault();
        let arr = this.state.travellers;
        arr.push({id: Math.ceil(Math.random() * 1000), value: ""});
        /* TODO на переделку. сейчас нельзя отфильтровать массив по длине. Там все "". Нужно доделать handleChange*/
        /* let Arr2 = arr.filter(function (str) {
         let qt = str["value"];
         return str.value != "";
         });*/
        let tnum = arr.length;
        //  if (tnum == 0) tnum = 1;
        this.setState({travellers: arr, travellersNum: tnum});
    }

    handleMinus(e) {
        e.preventDefault();

        let arr = this.state.travellers;
        if (arr.length > 1) {
            arr.pop();
            this.setState({travellers: arr, travellersNum: arr.length});
        }
    }

    componentWillUnmount() {
//        console.log("unmount");
    }

    componentWillMount() {
        this.loadFromProps();
    }

    loadFromProps() {
        //   console.log('ldp', this.props);
        if (this.props.toursList && this.props.toursList.tourdata) {
            let res2 = this.props.toursList;
            let res3 = this.props.toursList.tourdata;
            let res4 = this.props.toursList.dates;
            //     console.log("DAT", res4);
            let prepay = 500;
            let price = (res3.baseprice ? res3.baseprice : "");
            if (res4 && res4[0] && parseInt(res4[0].pricefull) > 0) price = res4[0].pricefull;
            if (res4 && res4[0] && parseInt(res4[0].prepay) > 0) prepay = res4[0].prepay;

            let elevent = 0;
            if (res2.dates && res2.dates.length > 0) {
                if (this.state.tdate && this.state.tdate != "") {
                    elevent = parseInt(res2.dates.filter(((e) => {
                        return e.id == this.state.tdate
                    }).bind(this)));
                }
                else {
                    elevent = parseInt(res2.dates[0].elevent);
                }


            }
            this.setState({
                tourdata: res3,
                baseprice: price,
                topay: (res3.baseprice ? res3.baseprice : ""),
                title: (res3.title ? res3.title : ""),
                loadedid: (res3.id ? res3.id : 0),
                payperone: prepay,
                photos: (res3.photos ? res3.photos : ""),
                main_descr: (res3.main_descr ? res3.main_descr : ""),
                mainfoto: "/palomnichestvo/img/" + (res3.mainfoto ? res3.mainfoto : ""),
                freespaces: (res2.freespaces ? res2.freespaces : ""),
                dates: (res2.dates ? res2.dates : ""),
                payment: this.getPrepayArr(prepay),
                elevent: elevent,
                services: (res2.services ? res2.services : "")
            });
        }
    }

    getPrepayArr(sum) {
        return [
            {id: 1, rec: 1, value: `Предоплата ${sum} рублей с человека (рекомендовано)`, sum: sum},
            {id: 2, rec: 0, value: "100% предоплата", sum: -500},
            {
                id: 3, rec: 0, value: "оплачу на месте", sum: 0
            }];
    }

    componentDidUpdate() {
//        console.log("tourcard", this.props);
        if (this.props.NOV_STATUS == "AUTHORIZED" && this.state.uloaded == 0) {
            let trav = [];
            if (this.state.travellers && this.state.travellers[0] && this.props.user_id && this.state.travellers[0].id != this.props.user_id) {
                //       console.log('loading')
                trav[0] = {
                    id: (this.props.user_id ? this.props.user_id : Math.ceil(Math.random() * 1000)),
                    value: (this.props.user_name ? this.props.user_name : " ")
                };
            } else {
                trav = this.state.travellers;
            }
            this.setState({
                tourid: this.props.tourid,
                userid: this.props.user_id,
                uloaded: 1,
                phone: this.props.user_phone,
                travellers: trav
            });
        }
        if (this.props.toursList && this.props.toursList.id && this.state.loadedid == 0) {
            this.loadFromProps();
        }
    }

    handleChangeForm(e) {
        //alert('ch');
    }

    setedate(e) {

//    console.log("EVT",e);
        let price = 0;
        let elevent = 0;
        let prepay = 500;
        let curstate = this.state.payment;

        if (e.elevent && parseInt(e.elevent) > 0) elevent = parseInt(e.elevent);

        if (e.pricefull && parseInt(e.pricefull) > 0) price = parseInt(e.pricefull);
        if (e.prepay && parseInt(e.prepay) > 0) prepay = parseInt(e.prepay);


        this.setState({elevent: elevent, baseprice: price, payperone: prepay, payment: this.getPrepayArr(prepay)});


    }

    handleChange() {
    }

    paymentFunc(id) {
        let payperone = 0;
        if (id == 1) {
            payperone = 500
        }
        if (id == 2) {
            payperone = this.state.baseprice
        }
        if (id == 3) {
            payperone = 0
        }
        this.setState({payvariant: id, payperone: payperone});
    };

    aListFunc(id) {
        let qt = Array.from(document.getElementsByClassName("addNam"));
        let tprice = 0;
        qt.forEach((e) => {
            if (e.checked) tprice = tprice + parseInt(e.name);
        }, this);
        //console.log(tprice);
        this.setState({addSum: tprice});
    }


    render() {
        //console.trace();
        //console.log('tourender',this.state);
        let adminline = "";
        if (this.props.isadmin) {

            adminline = <span><div className="form-group"><label className="col-md-3"
                                                                 htmlFor="vkid">Вконтакте id</label>&nbsp;
                <input id="vkid" name="vkid" value={this.state.vkid}
                       cols="5" onChange={(e) => this.setState({vkid: e.target.value})}
                       type="text"/>
            </div><div className="form-group"><label className="col-md-3" htmlFor="emid">E-mail:</label>&nbsp;
                <input id="emid" name="emid" value={this.state.emid}
                       cols="5" onChange={(e) => this.setState({emid: e.target.value})}
                       type="text"/>
            </div><div className="form-group"><label className="col-md-3" htmlFor="oelid">Елицы ID</label>&nbsp;
                <input id="oelid" name="oelid" value={this.state.oelid}
                       cols="5" onChange={(e) => this.setState({oelid: e.target.value})}
                       type="text"/>
            </div><div className="form-group"><label className="col-md-3" htmlFor="nrid">ID Новая Русь</label>&nbsp;
                <input id="nrid" name="nrid" value={this.state.nrid}
                       cols="5" onChange={(e) => this.setState({nrid: e.target.value})}
                       type="text"/>
            </div></span>;

        }

        return (

            <div>
                <input type="hidden" name="tourid" value={this.state.tourid}/>
                <input type="hidden" name="userid" value={this.state.userid}/>
                <input type="hidden" name="elevent" value={this.state.elevent}/>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <div className="col-md-6" style={{minHeigt: "500px"}}>
                                <div>{this.state.title}</div>
                                <div><img className="img-responsive" src={this.state.mainfoto}/></div>
                                <div>{this.state.main_descr}</div>
                                <div>Стоимость с человека:{this.state.baseprice} &#8381;</div>
                                <div>Человек: {this.state.travellersNum }</div>
                                <div >
                                    Итого:{this.state.baseprice * this.state.travellersNum + this.state.addSum} &#8381;</div>
                                <div style={{paddingLeft: "20px", paddingRight: "20px"}}>
                                    <div className="form-group">Дополнительные возможности:
                                        <AddList id="payment" onZChange={this.aListFunc}
                                                 addservices={this.state.services}/>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6 col-push-6">
                                <div className="form-group"><br /><label htmlFor="chooseDate">1. Выберите
                                    дату:</label>&nbsp;
                                    <DateNumberList id="chooseDate" defDate={this.state.tdate} onChange={this.setedate}
                                                    dateList={this.state.dates}/>
                                </div>
                                <div className="form-group"><label htmlFor="btnslist1"><MiM
                                    type={this.state.warning_fio}
                                    txt="2. Укажите ФИО каждого участника поездки:"/></label>
                                    <TNList id="btns1list" travellers={this.state.travellers}/>
                                    <button onClick={this.handlePlus}>+</button>
                                    <button onClick={this.handleMinus}>-</button>
                                    <ShowMinus vl={this.state.travellers}/>
                                </div>
                                <div className="form-group"><label>3. Выберите тип оплаты:</label>
                                    <PaymentType2
                                        onZChange={this.paymentFunc}
                                        id="paymentdialog" defPrice={this.state.baseprice}
                                        payvariant={this.state.payvariant} payment={this.state.payment}/>
                                </div>
                                <div className="form-group"><label htmlFor="ph"><MiM type={this.state.warning_phone}
                                                                                     txt={(this.props.isadmin ? "Номер телефона для оформления" : "4. Укажите Ваш номер телефона")}/></label>&nbsp;
                                    <input id="phnum" name="phonenum"
                                           value={this.state.phone != false ? this.state.phone : ""}
                                           onChange={this.handlePhone}
                                           type="text"/>
                                </div>            <div className="form-group"><label htmlFor="email"><span>5. Укажите e-mail - куда выслать посадочный купон</span></label>&nbsp;                                    <input id="email" name="email"
                                           value={this.state.email != false ? this.state.email : ""}
                                           onChange={(e) => this.setState({email: e.target.value})}
                                           type="text"/>
                                </div>
                                {adminline}
                                <div ><h3>Итого к оплате
                                    сейчас:&nbsp;{this.state.payperone * this.state.travellersNum + this.state.addSum} &#8381; </h3>
                                </div>

                                <div style={{paddingLeft: "15px", paddingRight: "15px"}} className="form-group"><label
                                    htmlFor="txtarea">Вопросы и пожелания?</label><textarea
                                    id="txtarea" ref={(input) => this.itxtarea = input} className="form-control"
                                    rows="3"></textarea>
                                </div>
                                <input type="hidden" name="prepay"
                                       value={this.state.payperone * this.state.travellersNum + this.state.addSum}/>
                                <input type="hidden" name="totalpay"
                                       value={this.state.baseprice * this.state.travellersNum + this.state.addSum}/>
                                <button type="submit" className="btn btn-info">Забронировать!</button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        );

    }
}

function mapStateToProps(state) {
    //console.log(state);
    if (state) {
        const {
            eluser_id,
            eluser_name,
            eluser_photo,
            hotelid,
            orderstatelist,
            tourid,
            toursList,
            active_window,
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
            active_window,
            toursList,

            NOV_STATUS,
            user_email,
            user_id,
            user_name,
            user_phone,
            isadmin: (state.novstate.user_isadmin == 1)
        }
    }
}

export default connect(mapStateToProps)(TourCard);

