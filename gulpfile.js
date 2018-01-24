const elixir = require('laravel-elixir');

// require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.copy('node_modules/font-awesome/fonts', 'public/fonts')
       .copy('node_modules/animate.css/animate.min.css', 'public/libs/animate/animate.min.css')
       .copy('node_modules/pace-js/pace.min.js', 'public/libs/pace/pace.min.js')
       .copy('node_modules/bootstrap-daterangepicker/daterangepicker.js', 'public/libs/daterangepicker/daterangepicker.js')
       .copy('node_modules/bootstrap-daterangepicker/daterangepicker.css', 'public/libs/daterangepicker/daterangepicker.css')
       .copy('node_modules/jquery-ui/themes', 'public/libs/jquery-ui/themes')
       .copy('node_modules/blueimp-file-upload', 'public/libs/blueimp-file-upload')
       .copy('node_modules/blueimp-gallery', 'public/libs/blueimp-gallery')
       .copy('node_modules/blueimp-tmpl', 'public/libs/blueimp-tmpl')
       .copy('node_modules/blueimp-load-image', 'public/libs/blueimp-load-image')
       .copy('node_modules/blueimp-canvas-to-blob', 'public/libs/blueimp-canvas-to-blob')
       .copy('node_modules/bootstrap-tagsinput', 'public/libs/bootstrap-tagsinput')
       .copy('node_modules/bootstrap-typeahead', 'public/libs/bootstrap-typeahead')
       .copy('node_modules/switchery/dist/switchery.js', 'resources/assets/js/switchery.js')
       .copy('node_modules/switchery/dist/switchery.css', 'resources/assets/sass/switchery.css')
       .copy('node_modules/toastr', 'public/libs/toastr')
       .copy('node_modules/sweetalert', 'public/libs/sweetalert')
       .copy('node_modules/icheck', 'public/libs/icheck')
       

    mix.sass('app.scss')
       .webpack('app.js')
       .browserSync({
        proxy: 'ixtron.test'
    });
});
