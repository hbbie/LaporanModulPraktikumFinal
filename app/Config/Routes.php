<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================
// 🏠 HALAMAN UTAMA & STATIS
// ==========================================
$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/tos', 'Page::tos');

// ==========================================
// 📰 ARTIKEL PUBLIK
// ==========================================
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:segment)', 'Artikel::view/$1');

// ==========================================
// ⚡ MODUL 8: ROUTE UNTUK AJAX
// ==========================================
$routes->get('ajax', 'AjaxController::index');
$routes->get('ajax/getData', 'AjaxController::getData');
$routes->delete('artikel/delete/(:num)', 'AjaxController::delete/$1');

// ==========================================
// 🔥 ADMIN (PAKAI AUTH FILTER)
// ==========================================
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->get('artikel/add', 'Artikel::add');
    $routes->post('artikel/add', 'Artikel::add');
    $routes->get('artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->post('artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:num)', 'Artikel::delete/$1');
});

// ==========================================
// 👤 USER AUTHENTICATION & PROFILE
// ==========================================
$routes->get('/user/login', 'User::login');
$routes->post('/user/login', 'User::login');
$routes->get('/user', 'User::index');

// ==========================================
// 🚪 LOGOUT
// ==========================================
$routes->get('/user/logout', 'User::logout');

// ==========================================
// 🔥 REST API
// ==========================================
$routes->resource('post');