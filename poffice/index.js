import React from 'react';
import ReactDOM from 'react-dom';
import  { Router, Match, Route, IndexRoute, browserHistory }  from 'react-router';
import MainHeader from './MainHeader';
import { Provider } from 'react-redux';
import Acmain from './admin-cabinet/admincabinet.js';
import { createStore, applyMiddleware, combineReducers } from 'redux'
import reducer from './reducers/index.js'
import treducer from './reducers/tours-reducer.js'
import preducer from './reducers/points-reducer.js'
import mainreducer from './reducers/mainstate.js'
import orders_reducer from './reducers/orders.js'
import photos_reducer from './reducers/photos.js'
import public_reducer from './reducers/public.js'


import 'babel-polyfill'
import createSagaMiddleware from 'redux-saga';
import counterSaga from './saga.js';
//import devToolsEnhancer from 'remote-redux-devtools';
import { reducer as formReducer } from 'redux-form'


const sagaMiddleware = createSagaMiddleware();
//console.log(userstate);
const reducers2 = {
    // ... your other reducers here ...
    points:preducer,
    novstate:reducer,
    tourstate:treducer,
    mainstate:mainreducer,
    orderslist:orders_reducer,
    photos:photos_reducer,
    public:public_reducer,
    form: formReducer
    //,form: treducer
    // <---- Mounted at 'form'
}


const reducer3 = combineReducers(reducers2)
    const store = createStore(reducer3
        , applyMiddleware(sagaMiddleware)
    );

sagaMiddleware.run(counterSaga);

function doAuth(nextState, replace)
{

//    let rt=window.opener;
    //if (rt) rt.fourceAuth();
  //  console.log(rt);
  //  alert('efe');

}
//console.log("AAA",store);
ReactDOM.render(
  <Provider store={store}>
  <Router history={browserHistory}>
        <Route path="/palomnichestvo/" component={MainHeader} name="t02"/>
        <Route path="/palomnichestvo/points" component={MainHeader} name="t01" />
        <Route path="/palomnichestvo/users/adminer" component={Acmain} name="test10" />
        <Route path="/palomnichestvo/users/:id(/:option1)"  component={MainHeader} name="t0"/>
        <Route path="/palomnichestvo/tours(/:id)" component={MainHeader}  name="test2" />
        <Route path="/palomnichestvo/tours(/:num/:id)" component={MainHeader}  name="test21" />
        <Route path="/palomnichestvo/tours/palomnik(/:id(/:date))" component={MainHeader}  name="test3" />
        <Route path="/palomnichestvo/sp/(:id)" component={MainHeader} name="test4" />
        <Route path="/palomnichestvo/points/(:id)" component={MainHeader} name="test51" />
        <Route path="/palomnichestvo/schedule" component={MainHeader} name="test52" />
        <Route path="/palomnichestvo/search(/:id)" component={MainHeader} name="test9" />
        <Route path="/palomnichestvo/razm(/:id)" component={MainHeader} name="test10" />
        <Route path="/palomnichestvo/about" component={MainHeader} name="test59" />
        <Route path="/palomnichestvo/addtour" component={MainHeader} name="test5" />
        <Route path="/palomnichestvo/successpay" store={store} component={MainHeader} name="t3"/>
        <Route path="/palomnichestvo/failpay" store={store} component={MainHeader} name="t3"/>

  </Router>
  </Provider>
    ,
/*
    <Router history={browserHistory}>
        <Route path="/palomnik" component={MainHeader} name="t3">
        <IndexRoute component={MainHeader} />
            <Route path="users/:id(/:option1)" onEnter={doAuth} component={Ucmain} name="t0"/>

        <Route path="points" component={MainHeader} name="t01" />
        <Route path="tours(/:id)" component={MainHeader}  name="test2" />
        <Route path="tours(/:num/:id)" component={MainHeader}  name="test21" />
        <Route path="tours/palomnik(/:id)" component={MainHeader}  name="test3" />
        <Route path="sp/:id" component={MainHeader} name="test4" />
        <Route path="points/:id" component={MainHeader} name="test5" />
        <Route path="schedule" component={MainHeader} name="test5" />
        <Route path="addtour" component={MainHeader} name="test5" />
            </Route>

    </Router>,*/
    document.getElementById('root')
);


