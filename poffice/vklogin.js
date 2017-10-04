/**
 * Created by леново on 08.08.2017.
 */
import React, {Component, PropTypes } from 'react';


class VkLogin extends Component {
    constructor (props)
    {
        super(props);
        this.setFbAsyncInit=this.setFbAsyncInit.bind(this);
        this.loadSdkAsynchronously=this.loadSdkAsynchronously.bind(this);
        this.checkLoginState=this.checkLoginState.bind(this);
        this.click=this.click.bind(this);

        this.state = {
            isSdkLoaded: true,
            isProcessing: false,
            authorized:0,
            user_id:0,
            counter:0,
            qt:0,
            elementset:false,
            name:""
        };

    }

    componentDidMount() {
        this.setFbAsyncInit();
        this.loadSdkAsynchronously();

    }

    componentDidUpdate()
    {
        if (!this.props.disabled)
        if (this.state.isSdkLoaded && window.VK && window.VK.Auth && this.state.processing!=1)  {

            window.VK.Auth.login(this.checkLoginState);
            this.setState({processing:1});
            clearInterval(this.state.qt);
        }else
            {
             if (this.state.qt===0) {
                 let qt = setInterval((e)=>{this.setState({counter: this.state.counter + 1});}, 5000);
                 this.setState({qt:qt});
             }
            }
      }

    setFbAsyncInit() {
        const { apiId } = this.props;
        window.vkAsyncInit = () => {
            window.VK.init({ apiId });
            this.setState({ isSdkLoaded: true });
        };
    }

    sdkLoaded() {
        this.setState({ isSdkLoaded: true });
    }

    loadSdkAsynchronously() {
        if (!this.state.elementset) {
            const el = document.createElement('script');
            el.type = 'text/javascript';
            el.src = 'https://vk.com/js/api/openapi.js?139';
            el.async = true;
            el.id = 'vk-jssdk';
            document.getElementsByTagName('head')[0].appendChild(el);
            this.setState({elementset: true})
        }
    }

    checkLoginState (response) {
        this.setState({ isProcessing: false });
        if (response && response.session && response.session.user)
        {
          let   vkuser=response.session.user;
            this.setState({user_id:vkuser.id, name:vkuser.first_name+" "+vkuser.last_name})
        }

        if (this.props.callback) {
            this.props.callback(response);
        }
    };

    click  () {
        if (!this.state.isSdkLoaded || this.state.isProcessing || this.props.disabled) {
            return;
        }
        window.VK.Auth.login(this.checkLoginState);
        this.setState({ isProcessing: true });
    };



    render() {
        const { disabled, callback, apiId, ...buttonProps } = this.props;
        return (<span></span>);
    }
}

export default VkLogin;
