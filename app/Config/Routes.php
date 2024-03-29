<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');

$routes->group('admin', function ($routes) {
    $routes->get('users', 'Admin\UserController::tampilUsers');
    $routes->get('users/(:segment)', 'Admin\UserController::tampilUsersId/$1');
    $routes->post('users', 'Admin\UserController::tambahUser');
    $routes->put('users/(:segment)', 'Admin\UserController::editUser/$1');
    $routes->delete('users/(:segment)', 'Admin\UserController::hapusUser/$1');
    $routes->get('verifikasi', 'Admin\UserController::tampilVerification');
    $routes->post('verifikasi/(:segment)', 'Admin\UserController::verification/$1');

    $routes->get('getPengumuman', 'PengumumanController::tampilPengumuman');
    $routes->get('getPengumumanById/(:segment)', 'PengumumanController::tampilPengumumanById/$1');
    $routes->post('postPengumuman', 'PengumumanController::buatPengumuman');
    $routes->put('editPengumuman/(:segment)', 'PengumumanController::editPengumuman/$1');
    $routes->delete('deletPengumuman/$1', 'PengumumanController::deletePengumuman/$1');
});
$routes->group('auth', function($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
});

$routes->get('getDiskusi', 'DiskusiController::tampilDiskusi');
$routes->get('getDiskusi/(:segment)', 'DiskusiController::tampilDiskusiId/$1');
$routes->post('postDiskusi', 'DiskusiController::tambahDiskusi');

$routes->post('views', 'ViewsController::views');

$routes->group('tags', function($routes) {
    $routes->get('', 'TagsController::getTags');
    $routes->get('(:segment)', 'TagsController::getTagsId/$1');
});

$routes->get('getKoment/(:segment)', 'KomentarController::tampilKoment/$1');
$routes->post('postKoment', 'KomentarController::buatKoment');

$routes->get('getBlogs', 'BlogsController::tampilBlogs');
$routes->get('getBlogs/(:segment)', 'BlogsController::tampilBlogsById/$1');
$routes->post('postBlogs', 'BlogsController::buatBlogs');
$routes->delete('deleteBlogs/(:segment)', 'BlogsController::deleteBlogs/$1');
$routes->delete('editBlogs/(:segment)', 'BlogsController::editBlogs/$1');





// $routes->post('users', 'UserController::createUser');

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
