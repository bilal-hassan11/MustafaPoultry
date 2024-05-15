let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.styles([
//     "resources/frontend/css/flatpickr.min.css",
//     "resources/frontend/css/select2.min.css",
//     "resources/frontend/css/bootstrap.min.css",
//     "resources/frontend/css/style.css",
//     "resources/frontend/css/font-awesome.css",
//     "resources/frontend/css/et-line-fonts.css",
//     "resources/frontend/css/owl.carousel.css",
//     "resources/frontend/css/owl.style.css",
//     "resources/frontend/css/flaticon.css",
//     // "resources/frontend/css/magnific-popup.css",
//     "resources/frontend/css/defualt.css",
//     "resources/frontend/css/animate.min.css",
//     "resources/frontend/css/bootstrap-dropdownhover.min.css",
//     "resources/frontend/css/intlTelInput.css",
//     "resources/backend/css/sweetalert2.min.css",
// ], 'public/frontend_assets/css/bundled.css').options({
//     postCss: [
//         require('postcss-discard-comments')({
//             removeAll: true
//         })
//     ]
// });

// mix.babel([
//     "resources/frontend/js/jquery.min.js",
//     "resources/frontend/js/popper.min.js",
//     "resources/frontend/js/bootstrap.min.js",
//     "resources/frontend/js/bootstrap-dropdownhover.min.js",
//     "resources/frontend/js/easing.js",
//     "resources/frontend/js/jquery.countTo.js",
//     "resources/frontend/js/jquery.waypoints.js",
//     "resources/frontend/js/jquery.appear.min.js",
//     // "resources/frontend/js/jquery.shuffle.min.js",
//     // "resources/frontend/js/carousel.min.js",
//     "resources/frontend/js/jquery-migrate.min.js",
//     // "resources/frontend/js/color-switcher.js",
//     // "resources/frontend/js/jquery.magnific-popup.min.js",
//     // "resources/frontend/js/theia-sticky-sidebar.js",
//     "resources/frontend/js/app.js",
//     "resources/frontend/js/jquery.counterup.js",
//     "resources/frontend/js/parsley.min.js",
//     "resources/frontend/js/sweetalert2.min.js",
//     "resources/frontend/js/jquery.form.js",
//     "resources/frontend/js/select2.min.js",
//     "resources/frontend/js/flatpickr.js"
// ], 'public/frontend_assets/js/bundled.js');



//admin setup
mix.styles([
    "resources/admin_assets/libs/flatpickr/flatpickr.min.css",
    "resources/admin_assets/libs/select2/css/select2.min.css",
    "resources/admin_assets/libs/ladda/ladda.min.css",
    "resources/admin_assets/libs/ladda/ladda-themeless.min.css",
    "resources/admin_assets/css/bootstrap.min.css",
    "resources/admin_assets/css/app.min.css",
    "resources/admin_assets/css/icons.min.css",
    "resources/admin_assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css",
    "resources/admin_assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css",
    "resources/admin_assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css",
    "resources/admin_assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css",
    "resources/admin_assets/libs/sweetalert2/sweetalert2.min.css",
], 'public/admin_assets/css/bundled.min.css').options({
    postCss: [
        require('postcss-discard-comments')({
            removeAll: true
        })
    ]
});

mix.babel([
   	"resources/admin_assets/js/vendor.min.js",
    "resources/admin_assets/libs/flatpickr/flatpickr.min.js",
    "resources/admin_assets/libs/ladda/spin.min.js",
    "resources/admin_assets/libs/ladda/ladda.min.js",
    "resources/admin_assets/libs/select2/js/select2.min.js",
	"resources/admin_assets/libs/parsleyjs/parsley.min.js",
	"resources/admin_assets/libs/sweetalert2/sweetalert2.all.min.js",
	"resources/admin_assets/js/jquery.form.min.js",
	"resources/admin_assets/js/app.min.js",
], 'public/admin_assets/js/bundled.min.js');

mix.babel([
	"resources/admin_assets/libs/datatables.net/js/jquery.dataTables.min.js",
    "resources/admin_assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js",
    "resources/admin_assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js",
    "resources/admin_assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js",
    "resources/admin_assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js",
    "resources/admin_assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js",
    "resources/admin_assets/libs/datatables.net-buttons/js/buttons.html5.min.js",
    "resources/admin_assets/libs/datatables.net-buttons/js/buttons.flash.min.js",
    "resources/admin_assets/libs/datatables.net-buttons/js/buttons.print.min.js",
    "resources/admin_assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js",
    "resources/admin_assets/libs/datatables.net-select/js/dataTables.select.min.js",
	// "resources/backend/datatable/pdfmake.min.js",
	// "resources/backend/datatable/vfs_fonts.js"
], 'public/admin_assets/js/dataTable_bundled.min.js');
