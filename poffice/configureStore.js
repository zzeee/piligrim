/**
 * Created by Zienko on 16.01.2017.
 */
import { createStore, applyMiddleware } from 'redux'
import rootReducer from './reducers'
export default function configureStore(initialState) {
    const store = createStore(rootReducer, initialState)
    return store
}
