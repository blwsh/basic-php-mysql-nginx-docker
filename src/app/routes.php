<?php

use Framework\Router;

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


// Pages
Router::get('/shops', 'PagesController@shops');
Router::get('/about', 'PagesController@about');

// Basket
Router::get('/basket/get', 'BasketController@get');
Router::post('/basket/add', 'BasketController@add');
Router::post('/basket/remove', 'BasketController@remove');
Router::get('/basket/clear', 'BasketController@clear');

// Films
Router::get('/films', 'Filmscontroller@index');
Router::get('/films/create', 'FilmsController@create');
Router::get('/films/{filmid([0-9]+)}', 'FilmsController@view');
Router::post('/films/{filmid([0-9]+)}', 'FilmsController@view');

// Checkout
Router::get('/checkout', 'CheckoutController@overview');
Router::get('/checkout/complete', 'CheckoutController@complete');
