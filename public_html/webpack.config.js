var SftpWebpackPlugin = require('sftp-webpack-plugin');
var OpenBrowserPlugin = require('open-browser-webpack-plugin');


var config = {
    entry: './poffice/index.js',

    output: {
        path: './res',
        filename: 'resindex.js',
    },
    devServer: {
        inline: true,
        port: 8080
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loader: 'babel',
                query: {
                    presets: ['es2015', 'react']
                }
            },
            {test: /\.css$/, loader: "style-loader!css-loader"},
            {test: /\.less$/, loader: "style!css!less"},
            {test: /\.svg/, loader: 'svg-url-loader'}
        ]
    },

    plugins: [new SftpWebpackPlugin({
        port: '22',
        host: 'pulcher.timeweb.ru',
        username: 'zzeeee',
        password: 'Cgou6LUX',
        from: './res',
        to: '/home/z/zzeeee/piligrimServer/public_html/js'
    })

    ]
}

module.exports = config;


//var opener = require('opener');

//opener('http://molrus.tmweb.ru/redux/index1.html');
