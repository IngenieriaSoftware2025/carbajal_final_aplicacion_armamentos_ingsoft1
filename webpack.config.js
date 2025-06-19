const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: 'development',
  entry: {
    // App.js debe ser el PRIMER entry point
    'js/app': './src/js/app.js',
    'js/inicio': './src/js/inicio.js',

    'js/login/index': './src/js/login/index.js',
    'js/registro/index': './src/js/registro/index.js',
    'js/aplicaciones/aplicacion': './src/js/aplicaciones/aplicacion.js',
    'js/permisos/permisos': './src/js/permisos/permisos.js',
    'js/permisos/permiso_aplicacion': './src/js/permisos/permiso_aplicacion.js',
    'js/asignaciones/asig_permisos': './src/js/asignaciones/asig_permisos.js',
    'js/historial/index': './src/js/historial/index.js',
    'js/rutas/rutas': './src/js/rutas/rutas.js',
    'js/mapeado/index': './src/js/mapeado/index.js',
    'js/arma/index': './src/js/arma/index.js',
    'js/tipo/index': './src/js/tipo/index.js',
    'js/graficas/index': './src/js/graficas/index.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build'),
    // Limpiar el directorio de salida en cada build
    clean: true
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          'css-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpe?g|gif)$/,
        type: 'asset/resource',
      },
      {
        // Soporte para fuentes de Bootstrap Icons
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: 'asset/resource',
      }
    ]
  },
  // Configuración para desarrollo
  devtool: 'source-map',
  resolve: {
    extensions: ['.js', '.scss', '.css']
  }
};