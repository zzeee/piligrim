var xdevapi = require('@mysql/xdevapi');// Connect to server on localhost

var mySession = xdevapi.getSession(
    {
        host: 'localhost', port: 33060,
        dbUser: 'elitsy', dbPassword: 'Tkbws12!'
    }).then(function (session) {
    console.log('connected');
    console.log(session);


    return session.getSchema("elitsy")
});


/*

 .then(function (schema) {


 //            return schema.getCollection("tours");
 }, ()=>console.log('sss1')
 ................
 ................
 ................
 ................
 ................
 )/*.then(function (collection) {
 ........................
 collection.find("$.name == '%%'").execute(function (row) {
 console.log("Row: %j", row);
 })
 },x=>console.log('s2'))

 },x=>console.log('s_mai3n')*/

//);




