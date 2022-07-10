const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'public/js/src/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
    'public/css/src/oneui.css',
    'public/css/src/custom.css',
    'public/js/src/plugins/sweetalert2/sweetalert2.min.css',
], 'public/css/app.css');

mix.scripts([
    'public/js/src/oneui.core.min.js',
    'public/js/src/oneui.app.min.js',
    'public/js/src/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'public/js/src/plugins/bootstrap-notify/bootstrap-notify.min.js',
    'public/js/src/plugins/moment/moment.min.js',
    'public/js/lodash.min.js',
    'public/js/menu.js',
], 'public/js/core.js');

mix.scripts([
    'public/js/src/plugins/jquery-validation/jquery.validate.min.js',
    'public/js/src/pages/op_auth_signin.min.js',
    'public/js/src/pages/op_auth_signup.min.js'
], 'public/js/auth.js');

mix.scripts([
    'public/js/src/plugins/chart.js/Chart.bundle.min.js',
    'public/js/src/pages/be_pages_dashboard.min.js',
], 'public/js/dashboard.js');

mix.scripts([
    'public/plugins/highchart/highcharts.js',
    'public/plugins/highchart/highcharts-3d.js',
    'public/plugins/highchart/modules/exporting.js',
    'public/plugins/highchart/modules/export-data.js',
    'public/plugins/highchart/modules/accessibility.js',
], 'public/js/highchart-total-miles-week.js');

mix.scripts([
    'public/plugins/highchart/highcharts.js',
    'public/plugins/highchart/highcharts-3d.js',
    'public/plugins/highchart/modules/series-label.js',
    'public/plugins/highchart/modules/exporting.js',
    'public/plugins/highchart/modules/export-data.js',
    'public/plugins/highchart/modules/accessibility.js',
], 'public/js/highchart-miles-week-driver.js');

mix.scripts([
    'public/plugins/highchart/highcharts.js',
    'public/plugins/highchart/modules/exporting.js',
    'public/plugins/highchart/modules/export-data.js',
    'public/plugins/highchart/modules/accessibility.js',
], 'public/js/highchart-mpg-week-vehicle.js');

mix.scripts([
    'public/plugins/highchart/highcharts.js',
    'public/plugins/highchart/highcharts-more.js',
    'public/plugins/highchart/modules/exporting.js',
    'public/plugins/highchart/modules/export-data.js',
    'public/plugins/highchart/modules/accessibility.js',
], 'public/js/highchart-total-fuelcost-week.js');

mix.scripts([
    'public/plugins/highchart/highcharts.js',
    'public/plugins/highchart/highcharts-3d.js',
    'public/plugins/highchart/modules/cylinder.js',
    'public/plugins/highchart/modules/exporting.js',
    'public/plugins/highchart/modules/export-data.js',
    'public/plugins/highchart/modules/accessibility.js',
], 'public/js/highchart-total-revenue-week.js');

mix.scripts([
    'public/js/src/plugins/es6-promise/es6-promise.auto.min.js',
    'public/js/src/plugins/sweetalert2/sweetalert2.all.min.js',
], 'public/js/check-st.js');