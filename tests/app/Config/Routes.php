<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/', 'Home::landingPage');
$routes->get('/403', 'Home::error403');
$routes->get('/login', 'Web\Admin::login');

$routes->group('web', function ($routes) {
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'Home::profile');
        $routes->get('update', 'Home::update');
        $routes->post('save/(:any)', 'Home::save/$1');
        $routes->get('changePassword', 'Home::changePassword');
        $routes->post('changePassword', 'Home::changePassword');

    });
});

// App
$routes->group('web', ['namespace' => 'App\Controllers\Web'], function ($routes) {
    $routes->presenter('gtp');
    $routes->get('/', 'Gtp::index');

    $routes->group('estuaria', function ($routes) {
        $routes->presenter('estuaria');
        $routes->get('/', 'Unik::estuaria');
    });

    $routes->group('tracking', function ($routes) {
        $routes->presenter('tracking');
        $routes->get('/', 'Unik::index');
    });

    $routes->group('pieh', function ($routes) {
        $routes->presenter('pieh');
        $routes->get('/', 'Unik::pieh');
    });

    $routes->group('makam', function ($routes) {
        $routes->presenter('makam');
        $routes->get('/', 'Unik::makam');
    });

    $routes->group('talao', function ($routes) {
        $routes->presenter('talao');
        $routes->get('/', 'Talao::detail');
    });

    $routes->group('seni', function ($routes) {
        $routes->presenter('seni');
        $routes->get('/', 'Talao::seni');
    });
    $routes->get('package/extend/(:any)', 'Package::extend/$1', ['filter' => 'login']);
    $routes->post('detailreservation/addextend/(:any)', 'DetailReservation::addextend/$1', ['filter' => 'login']);

    $routes->presenter('attraction');
    $routes->presenter('event');
    $routes->presenter('package');
    $routes->presenter('ulakan');
    $routes->resource('homestay');
    $routes->presenter('homestay');
    $routes->presenter('culinaryPlace');
    $routes->presenter('souvenirPlace');
    $routes->presenter('worshipplace');
    $routes->presenter('servicepackage');
    $routes->resource('servicepackage');
    $routes->post('servicepackage/createservicepackage/(:segment)', 'ServicePackage::createservicepackage/$1');
    $routes->delete('servicepackage/delete/(:any)', 'ServicePackage::delete/$1');
    $routes->delete('package/deletepackage/(:any)', 'Package::delete/$1');

    $routes->get('reservation/custombooking/(:segment)', 'Reservation::custombooking/$1', ['filter' => 'login']);
    $routes->post('reservation/uploaddeposit/(:any)', 'Reservation::uploaddeposit/$1', ['filter' => 'login']);
    $routes->post('reservation/uploadfullpayment/(:any)', 'Reservation::uploadfullpayment/$1', ['filter' => 'login']);
    $routes->presenter('reservation', ['filter' => 'login']);
    $routes->resource('reservation', ['filter' => 'login']);

    $routes->get('detailreservation/packagecustom/(:any)', 'DetailReservation::packagecustom/$1', ['filter' => 'login']);//--------
    $routes->post('detailreservation/addcustom', 'DetailReservation::addcustom', ['filter' => 'login']);
    $routes->post('detailreservation/createday/(:segment)', 'DetailReservation::createday/$1');
    $routes->post('detailreservation/createactivity/(:segment)', 'DetailReservation::createactivity/$1');
    $routes->delete('detailreservation/deleteunit/(:any)', 'DetailReservation::deleteunit/$1');
    $routes->delete('detailreservation/deleteday/(:any)', 'DetailReservation::deleteday/$1');
    $routes->delete('detailreservation/delete/(:any)', 'DetailReservation::delete/$1');
    $routes->get('detailreservation/addhome/(:segment)', 'DetailReservation::addhome/$1', ['filter' => 'login']);
    $routes->get('detailreservation/review/(:any)', 'DetailReservation::review/$1');//--------
    $routes->post('detailreservation/savereview/(:any)', 'DetailReservation::savereview/$1');//--------
    $routes->post('detailreservation/savereviewunit/(:any)', 'DetailReservation::savereviewunit/$1');//--------
    $routes->post('detailreservation/savecancel/(:any)', 'DetailReservation::savecancel/$1');//--------
    $routes->post('detailreservation/saveresponse/(:any)', 'DetailReservation::saveresponse/$1');//--------
    $routes->post('detailreservation/saverefund/(:any)', 'DetailReservation::saverefund/$1');//--------
    $routes->post('detailreservation/savecheckdeposit/(:any)', 'DetailReservation::savecheckdeposit/$1');//--------
    $routes->post('detailreservation/savecheckpayment/(:any)', 'DetailReservation::savecheckpayment/$1');//--------
    $routes->post('detailreservation/savecheckrefund/(:any)', 'DetailReservation::savecheckrefund/$1');//--------
    $routes->presenter('detailreservation', ['filter' => 'login']);
    $routes->resource('detailreservation', ['filter' => 'login']);

    $routes->get('generatepdf/(:any)', 'PdfController::generatePDF/$1');

    // Profile
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'Profile::profile', ['filter' => 'login']);
        $routes->get('changePassword', 'Profile::changePassword', ['filter' => 'login']);
        $routes->post('changePassword', 'Profile::changePassword', ['filter' => 'login']);
        $routes->get('update', 'Profile::updateProfile', ['filter' => 'login']);
        $routes->post('update', 'Profile::update', ['filter' => 'login']);
    });
});

// Dashboard
$routes->group('dashboard', ['namespace' => 'App\Controllers\Web', 'filter' => 'role:admin, master'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('gtp', 'Dashboard::gtp');
    $routes->get('users', 'Dashboard::users');
    $routes->get('attraction', 'Dashboard::attraction');
    $routes->get('event', 'Dashboard::event');
    $routes->get('package', 'Dashboard::package');
    $routes->get('facility', 'Dashboard::facility');
    $routes->get('souvenirplace', 'Dashboard::souvenirplace');
    $routes->get('worshipplace', 'Dashboard::worshipplace');
    $routes->get('culinaryplace', 'Dashboard::culinaryplace');
    $routes->get('servicepackage', 'Dashboard::servicepackage');
    $routes->post('package/updatecustom/(:any)', 'Package::updatecustom/$1');

    $routes->get('packageday/(:segment)', 'Packageday::newday/$1');
    $routes->post('packageday/createday/(:segment)', 'Packageday::createday/$1');
    $routes->post('packageday/createactivity/(:segment)', 'Packageday::createactivity/$1');
    $routes->delete('packageday/delete/(:any)', 'Packageday::delete/$1');
    $routes->delete('packageday/deleteday/(:any)', 'Packageday::deleteday/$1');
    
    // $routes->get('package/edit/(:segment)', 'Packageday::newday/$1');
    // $routes->post('package/edit/createday/(:segment)', 'Packageday::createday/$1');
    // $routes->post('package/edit/createactivity/(:segment)', 'Packageday::createactivity/$1');
    // $routes->delete('package/edit/delete/(:any)', 'Packageday::delete/$1');
    // $routes->delete('package/edit/deleteday/(:any)', 'Packageday::deleteday/$1');

    $routes->post('facilityhomestay/createfacility/(:segment)', 'Facilityhomestay::createfacility/$1');
    $routes->post('facilityhomestay/createfacilityhomestay/(:segment)', 'Facilityhomestay::createfacilityhomestay/$1');
    $routes->delete('facilityhomestay/delete/(:any)', 'Facilityhomestay::delete/$1');
    $routes->get('homestay', 'Dashboard::homestay');
    $routes->resource('homestay');
    $routes->presenter('homestay');
    $routes->post('homestay/createfacility/', 'Homestay::createfacility');
    $routes->get('unithomestay/new/(:segment)', 'UnitHomestay::new/$1');
    $routes->delete('unithomestay/delete/(:any)', 'UnitHomestay::delete/$1');
    $routes->delete('unithomestay/deletefacilityunit/(:any)', 'UnitHomestay::deletefacilityunit/$1');
    $routes->resource('unit');
    $routes->presenter('unit');

    // $routes->get('admin', 'Adminuser::index');
    // $routes->get('admin/index', 'Adminuser::index');
    // $routes->get('admin/(:num)', 'Adminuser::show/$1', ['filter' => 'role:admin']);
    $routes->post('users/admin/register', 'Users::adminregister');

    $routes->resource('village');
    $routes->resource('users');
    $routes->presenter('gtp');
    $routes->presenter('attraction');
    $routes->presenter('event');
    $routes->presenter('package');
    $routes->presenter('facility');
    $routes->presenter('culinaryplace');
    $routes->presenter('worshipplace');
    $routes->presenter('souvenirplace');
    $routes->presenter('packageday');

    $routes->presenter('servicepackage');
    $routes->resource('servicepackage');
    $routes->post('servicepackage/createservicepackage/(:segment)', 'ServicePackage::createservicepackage/$1');
    $routes->delete('servicepackage/delete/(:any)', 'ServicePackage::delete/$1');

    $routes->presenter('unithomestay');
    $routes->post('unithomestay/createunit/(:segment)', 'UnitHomestay::createunit/$1');
    $routes->post('unithomestay/createfacility/(:segment)', 'UnitHomestay::createfacility/$1');
    $routes->post('unithomestay/createfacilityunit/(:segment)', 'UnitHomestay::createfacilityunit/$1');
    
    $routes->get('reservation/report', 'Reservation::report');
    $routes->presenter('reservation');
    $routes->resource('reservation');
    $routes->presenter('managereservation');
    $routes->get('detailreservation/confirm/(:any)', 'DetailReservation::confirm/$1');
    $routes->post('detailreservation/saveconfirm/(:any)', 'DetailReservation::saveconfirm/$1');
    $routes->post('reservation/uploadrefund/(:any)', 'Reservation::uploadrefund/$1');
    $routes->get('detailreservation/review/(:any)', 'DetailReservation::review/$1');
    $routes->presenter('detailreservation', ['filter' => 'login']);
    $routes->resource('detailreservation', ['filter' => 'login']);
});

// Upload files
$routes->group('upload', ['namespace' => 'App\Controllers\Web'], function ($routes) {
    $routes->post('photo', 'Upload::photo');
    $routes->post('video', 'Upload::video');
    $routes->delete('photo', 'Upload::remove');
    $routes->delete('video', 'Upload::remove');
});


// API
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->resource('gtp');

    $routes->post('village', 'Village::getData');
    $routes->post('villages', 'Village::getDataKK');
    $routes->post('homestay', 'Homestay::getData');
    $routes->post('culinary', 'Culinaryplace::getData');
    $routes->post('souvenir', 'SouvenirPlace::getData');
    $routes->post('worship', 'Worshipplace::getData');
    $routes->post('facility', 'Facility::getData');

    $routes->resource('users');
    $routes->resource('connection');
    $routes->resource('tracking');
    $routes->get('attraction/maps', 'Attraction::maps');
    $routes->get('attraction/detail/(:any)', 'Attraction::detail/$1');
    $routes->resource('attraction');
    $routes->resource('servicepackage');
    $routes->resource('facility');
    $routes->post('facility/findByRadius', 'Facility::findByRadius');
    $routes->post('facility/findByTrack', 'Facility::findByTrack');
    $routes->resource('event');

    $routes->get('package/detail/(:any)', 'Package::detail/$1');
    $routes->get('package/type', 'Package::type');
    $routes->resource('package');
    $routes->get('packageday/(:any)', 'PackageDay::getDay/$1');
    $routes->post('package/findByName', 'Package::findByName');
    $routes->post('package/findByType', 'Package::findByType');

    $routes->get('homestay/detail/(:any)', 'Homestay::detail/$1');
    $routes->get('homestay/maps', 'Homestay::maps');
    $routes->resource('homestay');
    $routes->post('homestay/findByRadius', 'Homestay::findByRadius');

    $routes->resource('culinaryplace');
    $routes->presenter('culinaryplace');
    $routes->post('culinaryplace/findByRadius', 'Culinaryplace::findByRadius');
    $routes->resource('souvenirPlace');
    $routes->presenter('souvenirplace');
    $routes->post('souvenirPlace/findByRadius', 'SouvenirPlace::findByRadius');
    $routes->resource('worshipplace');
    $routes->presenter('worshipplace');
    $routes->post('worshipplace/findByRadius', 'Worshipplace::findByRadius');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
