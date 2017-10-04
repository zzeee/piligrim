/**
 * Created by a.zienko on 07.06.2017.
 */
import { createStore, applyMiddleware } from 'redux'
import rootReducer from './reducers'

const configureStore = preloadedState => createStore(
  rootReducer,
  preloadedState,
);

export default configureStore
