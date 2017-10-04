/*
 * Created by леново on 04.06.2017.
 */
var path = require('path');
var webpack = require('webpack');

var type;

type=1;//1 - production, no hot-load, 2 - hot load


config= {
    entry: ['babel-polyfill',
        './poffice/index.js',
        
            'webpack-dev-server/client?http://151.248.116.2:8080',
    'webpack/hot/only-dev-server',
    'react-hot-loader/patch'

        
        // the entry point of our app
    ],
    output: {
        path: './public_html/palomnichestvo/res',
        filename: 'resindex.js',
        publicPath: '/static/'
        // necessary for HMR to know where to load the hot update chunks
    },

    devtool: 'inline-source-map',

    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loader: 'babel-loader',

                query: {
                    presets: ['es2015', 'react']
                }
            },

            {test: /\.css$/, loader: "style-loader!css-loader"},

            ]

    },
    
    devServer:{
        host: '151.248.116.2',
            port: 8080,
            historyApiFallback: true,
            hot: true,
            },

    plugins: [
        /*
        new webpack.HotModuleReplacementPlugin(),
        // enable HMR globally

        new webpack.NamedModulesPlugin(),*/
        // prints more readable module names in the browser console on HMR updates

    //    new webpack.NoEmitOnErrorsPlugin(),
        // do not emit compiled assets that include errors
    ],
};

//config.entry.push{}

if (type==2){
    config.entry.push('webpack-dev-server/client?http://151.248.116.2:8080');
    config.entry.push('webpack/hot/only-dev-server');
    config.entry.push('react-hot-loader/patch');

    config.devServer={
        host: '151.248.116.2',
            port: 8080,
            historyApiFallback: true,
            hot: true,
            };

}

if (type==1)
{
    let rt=new webpack.DefinePlugin({
        'process.env': {NODE_ENV: JSON.stringify('production')}
    });
    config.plugins.push(rt);

    //config.plugins.push(new webpack.optimize.UglifyJsPlugin({minimize: true}));

}
console.log(config)
//console.log(path.resolve(__dirname, 'dist'));
module.exports=config;
