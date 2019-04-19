<?php

use Framework\Router;

Router::get('/', 'HomeController@index');
Router::get('/products', 'ProductsController@index');
Router::get('/products/{view}', 'ProductsController@view');
Router::get('/products/{category}/{view}', 'ProductsController@view');
Router::get('/products/create', 'ProductsController@create');
Router::get('/products/edit/{id([0-9])}', 'ProductsController@create');
Router::get('/products/{edit}/{id([0-9])}', 'ProductsController@create');
