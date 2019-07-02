<?php

use Framework\Http\Router;

// Home
Router::get('/', 'HomeController@index');

// Auth
Router::get('/login', 'AuthController@login');
Router::post('/auth/login', 'AuthController@loginAction');
Router::get('/register', 'AuthController@register');
Router::post('/auth/register', 'AuthController@registerAction');
Router::post('/auth/logout', 'AuthController@logout');

// Account
Router::get('/account', 'AccountController@manage');
Router::post('/account/update', 'AccountController@update');
Router::post('/account/delete', 'AccountController@confirmDelete');
Router::post('/account/delete/confirm', 'AccountController@delete');


// Pages
Router::get('/shops', 'PagesController@shops');
Router::get('/about', 'PagesController@about');

// Basket
Router::get('/basket/get', 'BasketController@get');
Router::post('/basket/add', 'BasketController@add');
Router::post('/basket/remove', 'BasketController@remove');
Router::get('/basket/clear', 'BasketController@clear');

// Films
Router::get('/films', 'FilmsController@index');
Router::get('/films/{filmid([0-9]+)}', 'FilmsController@view');

// Checkout
Router::get('/checkout', 'CheckoutController@overview');
Router::get('/checkout/complete', 'CheckoutController@complete');
Router::get('/checkout/success', 'CheckoutController@success');
Router::post('/checkout/submit', 'CheckoutController@submit');

// Search
Router::get('/search', 'SearchController@search');

// Debug toggle
Router::get('/debug', 'DebugController@toggle');

// Api
Router::get('/api/{model}/get', 'ApiController@get');
Router::get('/api/{model}/first/{id([0-9]+)}', 'ApiController@find');
Router::get('/api/{api(.*)}', 'ApiController@dump');