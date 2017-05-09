const CopyWebpackPlugin = require('copy-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const path = require('path')
const webpack = require('webpack')
const WebpackShellPlugin = require('webpack-shell-plugin')

module.exports = {
  context: path.join(__dirname, 'src/resources/assets'),
  entry: {
    'app': ['./app.js'],
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
    new CopyWebpackPlugin([{from: 'tinymce/plugins/cripfilesys/langs/**/*'}]),
    new WebpackShellPlugin({onBuildEnd: ['node copy-to-output.js']})
  ]
}

if (process.env.NODE_ENV === 'production') {
  module.exports.plugins.push(
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new webpack.optimize.UglifyJsPlugin({
      include: /\.min\.js|app\.js|vendor\.js$/,
      minimize: true,
      compress: {warnings: false}
    })
  )
} else {
  // minify min files in dev mode too
  module.exports.plugins.push(new webpack.optimize.UglifyJsPlugin({
    include: /\.min\.js$/,
    minimize: true,
    compress: {warnings: false}
  }))
}
