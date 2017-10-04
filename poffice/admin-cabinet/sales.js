/**
 * Created by a.zienko on 06.07.2017.
 */
import React, {Component} from 'react';
import {connect} from 'react-redux';

class Sales extends Component {


  render()
  {
    return <div>A1</div>

  }

}

function mapStateToProps(state) {
    console.log("SALES", state);

    if (state) {
        const {
            eluser_id,
            NOV_STATUS
        } = state.novstate
        return {
            eluser_id,
            NOV_STATUS,
        }
    }
}


export default connect(mapStateToProps)(Sales);

