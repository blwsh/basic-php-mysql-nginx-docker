<?php

use Framework\Router;

// Home
Router::get('/', 'HomeController@index');

// Pages
Router::get('/about', 'PagesController@about');

// Basket
Router::get('/basket/get', 'BasketController@get');

// Films
Router::get('/films', 'Filmscontroller@index');
Router::get('/films/create', 'FilmsController@create');
Router::get('/films/{filmid([0-9]+)}', 'FilmsController@view');
Router::post('/films/{filmid([0-9]+)}', 'FilmsController@view');
