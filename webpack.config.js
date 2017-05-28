const webpack = require('webpack')
const path = require('path')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const UglifyJsPlugin = webpack.optimize.UglifyJsPlugin

let plugins = [
  new CopyWebpackPlugin([
    {from: 'tinymce/plugins/cripfilesys/langs/**/*'}
  ])
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
  let outputPath = path.join(__dirname, './src/public')

  if (env.dev) {
    outputPath = path.join(__dirname, './../../public/vendor/crip/cripfilesys')
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
      path: outputPath,
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
