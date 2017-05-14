const webpack = require('webpack')
const path = require('path')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const WebpackShellPlugin = require('webpack-shell-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const UglifyJsPlugin = webpack.optimize.UglifyJsPlugin

let plugins = [
  new CopyWebpackPlugin([
    {from: 'tinymce/plugins/cripfilesys/langs/**/*'}
  ]),
  new WebpackShellPlugin({onBuildEnd: ['node copy-to-output.js']})
]

module.exports = (env) => {
  if (env.minimize) {
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

  return {
    context: path.join(__dirname, 'src/resources/assets'),
    entry: {
      'app': ['babel-polyfill', './app.js'],
      'tinymce/plugin': ['./tinymce/plugin.js'],
      'tinymce/plugin.min': ['./tinymce/plugin.js'],
      'tinymce/plugins/cripfilesys/plugin': ['./tinymce/plugins/cripfilesys/plugin.js'],
      'tinymce/plugins/cripfilesys/plugin.min': ['./tinymce/plugins/cripfilesys/plugin.js']
    },
    output: {
      path: path.join(__dirname, '../../public/vendor/crip/cripfilesys'),
      filename: '[name].js'
    },
    module: {
      loaders: [
        {
          enforce: 'pre',
          test: /\.js$|\.vue$/,
          loader: 'eslint-loader',
          exclude: /node_modules/,
          query: {fix: true}
        },
        {
          test: /\.vue$/,
          loader: 'vue-loader',
          exclude: /node_modules/,
          options: {loaders: {js: 'babel-loader'}}
        },
        {test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/},
        {
          test: /\.scss$/,
          exclude: /node_modules/,
          use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: ['css-loader', 'sass-loader']
          })
        },
        {
          test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/,
          loader: 'url-loader'
        }
      ]
    },
    plugins: [
      new ExtractTextPlugin('styles.css'),
      ...plugins
    ]
  }
}
