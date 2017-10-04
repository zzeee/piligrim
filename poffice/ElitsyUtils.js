/**
 * Created by Zienko on 02.06.2017.
 */

class EU {

    static getOrderURL(userid, orderid)
    {
        return "/palomnichestvo/users/" + userid + "/" + orderid;
    }

    static getPicURL(id)
    {
        return "/palomnichestvo/img/" +id;
    }

    static  getPMUrl(type, id)
    {
        //console.log("getPMUrl:",type,id);
        let base="/palomnichestvo/";
        if (type==1) return base+"points/"+id;
        if (type==2) return base+"sp/"+id;
      if (type==3) return base+"sp/"+id;
        if (type==5) return base+"sp/"+id;
        if (type==7) return base+"sp/"+id;
        if (type==8) return base+"sp/"+id;

        return "";
        //"https://www.elitsy.ru/palomnichestvo/" + href + number.tname
        //if ([2, 3, 7].indexOf(parseInt(number.type)) >= 0) href = "sp/";
        //if ([1].indexOf(parseInt(number.type)) >= 0) href = "points/";

    }


}
export default EU;