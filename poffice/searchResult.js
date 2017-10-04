/**
 * Created by Zienko on 22.06.2017.
 */
import React, {Component} from 'react';

export default function ShowSearchResult(res) {

    let rt=Array.from(res);
    console.log(res);
    let rm=rt.map((res)=>{
        let qres="";
        if (res.type==1) qres="<div><a href='/palomnichestvo/"+res.surl+"'>"+res.name+"</a></div>";
        if (res.type==2) qres="<div><a href='/palomnichestvo/tours/"+res.surl+"/"+res.id+"'>"+res.name+"</a></div>";
        return qres });
    console.log("qe", rm);

    //console.log("tres",);
    return "<div style='padding-left:10px'>"+rm.join('')+"</div>";
}

/*
class ShowSearchResult extends Component
{
    constructor(props)
    {
        super(props);

    }

    render()
    {
        return <div>test</div>
    }



}
export default ShowSearchResult;*/
