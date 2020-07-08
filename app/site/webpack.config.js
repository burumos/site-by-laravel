const path = require('path');
const outputPath = path.resolve(__dirname, 'public');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: './resources/js/app.js',
  output: {
    filename: 'app.js',
    path: outputPath,
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: "babel-loader"
      },
      {
        test: /\.(scss|sass|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader',
        ],
      },
    //  {
    //    test: /\.(jpe?g|png|gif|svg)$/i,
    //    loader: 'url-loader',
    //    options: {
    //      limit: 2048,
    //      name: './images/[name].[ext]',
    //    }
    //  },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css'
    })
  ],
}
