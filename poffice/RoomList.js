/**
 * Created by Zienko on 01.05.2017.
 */
import React, {Component} from 'react';

function RoomListItem(props) {
    function dod(event) {
        console.log(event);
        console.log('11');
        console.log(event.target.value);

        //console.log(this);
        props.onZChange(event.target.value);
    }
    let value = props.value;
    let checked = props.rec;
    let res = "";
    if (checked == 1)
        res = <div className="radio"><label><input value={props.idval} checked type="radio" onChange={dod} name="roomtype"
                                                   id={props.data}/>{props.value}</label></div>
    if (checked == 0) res = <div className="radio"><label><input value={props.idval} type="radio" onChange={dod} name="roomtype"
                                                                 id={props.data}/>{props.value}</label></div>
    return res;
}
class RoomList extends Component {

    constructor(props) {
        super(props);
        console.log('!+lqd!');
        console.log(props);
        console.log(props.list);
        //console.log('!+!lqd1');
        let selid=0;
        if (props.list.length>0 && props.variant>0 && typeof(props.list[props.variant])!='undefined') selid=props.list[props.variant]["id"];
        this.state = {selected:props.variant, list:props.list, selected_id:selid
        }
        this.onQChange=props.onZChange.bind(this);
        this.onHChange=this.onHChange.bind(this);
        this.syncVal=this.syncVal.bind(this);

    }

    onHChange(rt)
    {
        console.log("!!!"+rt);
        this.syncVal();

    }

    syncVal()
    {
        let qt=document.getElementsByName("roomtype");
        for (let i = 0, length = qt.length; i < length; i++) {
            if (qt[i].checked) {
                // do whatever you want with the checked radio
                console.log("synced val:"+i+" "+qt[i].value);
                this.setState({variant:i});
                this.onQChange(i, qt[i].value);
                // only one radio can be logically checked, don't check the rest
                break;
            }
        }
    }

    render()
    {
    const variant = this.state.variant;
    const listR = this.props.list;

    if (listR != null) {
        const listItems = listR.map((number) => {
            if (typeof(number) != undefined) {
                // console.log(number);
                let value = number.title;
                let p1 = number.price;
                let p2 = number.price1;
                let tvar=number.id;
                if (typeof(p1) != 'undefined' && typeof (p2) != 'undefined') value = value + ' - ' + p1 + '/' + p2 + ' руб.';
                let checked = 0;
                //if (number.sum<0) value=(number.value+"  - "+props.defPrice+ " руб.");
                return <RoomListItem key={number.id} onZChange={this.onHChange} rec={checked} idval={tvar} defPrice={p1} price1={p2} value={value}
                                     data={number.id}/>
            }
        });
        return (
            <div id={this.props.id}>
                {listItems}
            </div>
        )
    }
    else {
        return (<div>Загрузка..</div>)
    }
    return <div>no</div>
    }
}

export default RoomList;