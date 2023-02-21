const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.setPublicPath('themes/'+ process.env.MIX_THEME + '/assets/');

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      '@': __dirname + '/themes/' + process.env.MIX_THEME + '/assets',
      '@vue': __dirname + '/themes/' + process.env.MIX_THEME + '/assets/js',
      "@svg": __dirname + '/resources/svg',
    },
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(vue|js)$/,
        exclude: /(node_modules|bower_components)/,
        loader: 'eslint-loader',
        options: {
          fix: false,
          cache: false,
          formatter: require('eslint-friendly-formatter')
        }
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            'pug': [
              'pug-bem-plain-loader'
            ]
          }
        }
      },
      {
        test: /\.pug$/,
        oneOf: [
           {
              resourceQuery: /^\?vue/,
              use: ['pug-bem-plain-loader']
           },
           {
              use: ['raw-loader', 'pug-bem-plain-loader']
           }
        ]
      }
    ]
  },
});

mix.js('./themes/'+ process.env.MIX_THEME + '/assets/js/app.js', 'dist')
.vue()
.sass('./themes/'+ process.env.MIX_THEME + '/assets/scss/index.scss', 'dist')
.js('./themes/'+ process.env.MIX_THEME + '/assets/js/index.js', 'dist')
.version()
.sourceMaps()
.options({
  postCss: [
    require('postcss-url'),
    require('autoprefixer')({
      overrideBrowserslist: ['last 6 versions'],
          grid: true
    }),
    require('cssnano')({
        preset: ['default', {
            discardComments: {
                removeAll: true,
            },
        }]
    }),
  ]
})

mix.browserSync({
  proxy: process.env.MIX_HOST,
  host: process.env.MIX_HOST,
  notify: false,
  files: [
    "./themes/demo/assets/dist/css/*.css",
    "./themes/demo/assets/dist/js/*.js",
    "./themes/demo/**/*.htm",
  ]
});
