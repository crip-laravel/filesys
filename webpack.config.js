const webpack = require('webpack')
const path = require('path')
const CopyWebpackPlugin = require('copy-webpack-plugin')
// const WebpackShellPlugin = require('webpack-shell-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CommonsChunkPlugin = webpack.optimize.CommonsChunkPlugin
const UglifyJsPlugin = webpack.optimize.UglifyJsPlugin

let plugins = [
  // new CommonsChunkPlugin({names: ['tinymce/plugin.min', 'tinymce/plugin', 'vendor']}),
  new CopyWebpackPlugin([
    {from: 'tinymce/plugins/cripfilesys/langs/**/*'}
  ])
  // new WebpackShellPlugin({onBuildEnd: ['node copy-to-output.js']})
  // new webpack.ProvidePlugin({jQuery: 'jquery', $: 'jquery', jquery: 'jquery'})
]

if (process.argv.indexOf('--minimize') !== -1) {
  plugins.push(new UglifyJsPlugin({
    include: /\.min\.js|app\.js|vendor\.js$/,
    minimize: true,
    compress: {warnings: false}
  }))
} else {
  // min.js files anyway should be minimised
  plugins.push(new UglifyJsPlugin({
    include: /\.min\.js$/,
    minimize: true,
    compress: {warnings: false}
  }))
}

module.exports = {
  context: path.join(__dirname, 'src/resources/assets'),
  entry: {
    'app': ['./app.js'],
    'vendor': ['babel-polyfill', 'vue', 'vue-router', 'vuex', 'vue-resource'],
    'tinymce/plugin': ['./tinymce/plugin.js'],
    'tinymce/plugin.min': ['./tinymce/plugin.js'],
    'tinymce/plugins/cripfilesys/plugin': ['./tinymce/plugins/cripfilesys/plugin.js'],
    'tinymce/plugins/cripfilesys/plugin.min': ['./tinymce/plugins/cripfilesys/plugin.js']
  },
  output: {
    path: path.join(__dirname, '../../public/vendor/crip/filesys'), // './src/public',
    filename: '[name].js'
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
      {test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/, loader: 'url-loader'}
    ]
  },
  plugins: [
    new ExtractTextPlugin('styles.css'),
    ...plugins
  ]
}
