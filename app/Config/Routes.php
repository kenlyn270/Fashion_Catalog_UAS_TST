<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->options('(:any)', function () {
    return service('response')->setStatusCode(204);
});
$routes->get('/', 'Home::index');
$routes->get('/products', 'ProductsAPI::index');
$routes->get('/products/(:num)', 'ProductsAPI::show/$1');

$routes->get('/products/search', 'ProductsAPI::search');
$routes->get('/products/recommendations', 'ProductsAPI::recommendations');
$routes->post('products/recommend', 'ProductsAPI::recommend');
$routes->get('/products/categories', 'ProductsAPI::categories');
$routes->get('/products/tags', 'ProductsAPI::tags');

$routes->post('/products', 'ProductsAPI::create');
