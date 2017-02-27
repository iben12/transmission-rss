"use strict";

const ExtractTextPlugin = require("extract-text-webpack-plugin");

const sass = new ExtractTextPlugin('style.css');

module.exports = {
    entry: ['./resources/js/app.js'],
    output: {
        path: __dirname + '/assets',
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
            }
        ],
    },
    plugins: [
        sass
    ],
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.common.js'
        }
    },
    devtool: 'source-map',
    watch: true

}