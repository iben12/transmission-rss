"use strict";

const ExtractTextPlugin = require("extract-text-webpack-plugin");

const sass = new ExtractTextPlugin('style.css');
const html = new ExtractTextPlugin('index.html');

module.exports = {
    entry: ['./resources/js/app.js', './index.html'],
    output: {
        path: __dirname + '/build',
        publicPath: '/',
        filename: 'app.js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ],
        rules: [
            {
                test: /\.scss$/,
                use: sass.extract({
                    fallback: "style-loader",
                    use: [
                        {
                            loader: "css-loader",
                            options: {
                                sourceMap: true,
                                minimize: true
                            }
                        },
                         "sass-loader"
                    ]
                })
            },
            {
                test: /\.html$/,
                use: html.extract({use: 'raw-loader'})
            }
        ],
    },
    plugins: [
        sass,
        html
    ],
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.common.js'
        }
    },
    devtool: 'source-map',
    devServer: {
        inline: true,
        contentBase: 'build/'
    },
    //watch: true

}