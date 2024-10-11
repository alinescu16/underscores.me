const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');  
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = {
    entry: {
        main: './src/js/index.js',          // Entry point for your main JS
        styles: './src/scss/style.scss'     // Entry point for your SCSS
    },
    output: {
        path: path.resolve(__dirname, 'dist'), 
        filename: 'js/[name].[contenthash].js',  
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                  MiniCssExtractPlugin.loader,
                  'css-loader',
                  'postcss-loader',
                  'sass-loader', 
                ],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,  
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
        ],
    },
    plugins: [
        new HtmlWebpackPlugin({
            title: 'Your Theme Name',
            filename: 'index.html',
            template: 'src/index.html',
        }),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[contenthash].css',
        }),
        new CleanWebpackPlugin(),
    ],
    resolve: { 
        alias: {
          '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
          '~tailwindcss': path.resolve(__dirname, 'node_modules/tailwindcss'),
        },
    },
};