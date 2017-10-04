/**
 * Created by Zienko on 01.05.2017.
 */
import React, {Component} from 'react';


function PaymentListItem(props) {
    function dod(event) {
        //   console.log(event);
          console.log('!+---+!');
        props.onZChange('change-ins');
    }

      console.log(props);
    let value = props.value;
    let checked = props.rec;
    let res = "";
    if (checked == 1)
        res = <div className="radio"><label><input checked type="radio" onChange={dod} name="payment"
                                                   value={props.sumval}/>{props.value}</label></div>
    if (checked == 0) res = <div className="radio"><label><input type="radio" onChange={dod} name="payment"
                                                                 value={props.sumval}/>{props.value}</label></div>
    return res;
}

class PaymentType extends Component {

    constructor(props) {
        super(props);
        this.onQChange = props.onZChange.bind(this);
        this.onHChange = this.onHChange.bind(this);
        this.syncVal = this.syncVal.bind(this);

    }

    onHChange(rt) {
        this.syncVal();
    }

    syncVal() {
        let qt = document.getElementsByName("payment");
        for (let i = 0, length = qt.length; i < length; i++) {
            if (qt[i].checked) {
                this.setState({variant: i});
                this.onQChange(i, qt[i].value);
                break;
            }
        }
    }

    getNNL(arr) {
        let newarr = arr.filter((val) => {
            return val != null;
        });
        let rt = newarr.length;
        return rt;
    }

    render() {
        const defPrice = this.props.defPrice;
        const payment = this.props.payment;
        if (payment != null) {
             console.log(payment);
            let i = 0;
            let listItems = payment.map(((number) => {
                if (typeof(number) != undefined) {

                    let checked = 0;
                    if (this.props.variant == i) checked = 1;
                    i++;
                    let value = number.value;
                    let sumval = 0;
                    if (number.sum == -300 && (this.props.defPrice < 2 * this.props.minPrice )) return null;
                    if (number.sum == -300) {
                        value = (number.value + (this.props.defPrice > 0 ? "  - " + Math.round(this.props.defPrice / 2) + " руб." : ""));
                        sumval = Math.round(this.props.defPrice / 2);
                    }
                    if (number.sum == -500) {
                        value = (number.value + (this.props.defPrice > 0 ? "  - " + this.props.defPrice + " руб." : ""));
                        sumval = this.props.defPrice;
                    }
                    if (number.sum > 0) sumval = number.sum;
                    if (number.sum > this.props.defPrice) return null;
                    if (number.sum == 0 && this.props.prepay == 0) return null;

                    return <PaymentListItem key={number.id} onZChange={this.onHChange} rec={checked}
                                            sumval={sumval}
                                            value={value} data={number.id}/>
                }
            }).bind(this));

            let rt = 0;
            try {
                rt = (listItems.filter((val) => {
                    return val != null;
                })).length;

            }
            catch (e) {
                console.log(e);
            }
            console.log(rt);//TODO ДОДЕЛАТЬ: ЕСЛИ rt==1 не выводить список, а выбирать по умолчанию!

            return (
                <div id={this.props.id}>
                    {listItems}
                </div>
            )
        }
        else {
            return (<div>Загрузка..</div>)
        }
        return <div>--</div>
    }

}

export default PaymentType;