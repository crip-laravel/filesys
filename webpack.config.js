const path = require('path')
const webpack = require('webpack')
// const WebpackShellPlugin = require('webpack-shell-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CommonsChunkPlugin = webpack.optimize.CommonsChunkPlugin
const UglifyJsPlugin = webpack.optimize.UglifyJsPlugin

let plugins = [
  new CommonsChunkPlugin({name: 'vendor', filename: 'vendor.bundle.js'}),
  // new WebpackShellPlugin({onBuildEnd: ['node copy-to-output.js']})
  // new webpack.ProvidePlugin({jQuery: 'jquery', $: 'jquery', jquery: 'jquery'})
]

if (process.argv.indexOf('--minimize') !== -1) {
  plugins.push(new UglifyJsPlugin({compress: {warnings: false}}))
}

module.exports = {
  entry: {
    app: ['babel-polyfill', './src/resources/assets/app.js'],
    vendor: ['babel-polyfill', 'vue', 'vue-router', 'vuex', 'vue-resource']
  },
  output: {
    path: './../../public/vendor/crip/cripfilesys', // './src/public',
    filename: '[name].bundle.js'
  },
  module: {
    loaders: [
      {enforce: 'pre', test: /\.js$|\.vue$/, loader: 'eslint-loader', exclude: /node_modules/, query: {fix: true}},
      {test: /\.vue$/, loader: 'vue-loader', exclude: /node_modules/, options: {loaders: {js: 'babel-loader'}}},
      {test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/},
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: ExtractTextPlugin.extract({fallback: 'style-loader', use: ['css-loader', 'sass-loader']})
      },
      {test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/, loader: 'url-loader'},
    ]
  },
  plugins: [
    new ExtractTextPlugin('styles.css'),
    ...plugins
  ]
}