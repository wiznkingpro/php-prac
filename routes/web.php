<?php
use Src\Route;

// Авторизация и регистрация
Route::add('GET', '/login', [Controller\Site::class, 'login']);
Route::add('POST', '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/signup', [Controller\Site::class, 'signup']);
Route::add('POST', '/signup', [Controller\Site::class, 'signup']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

// Главная страница — теперь выводит абонентов и их телефоны
Route::add('GET', '/', [Controller\Site::class, 'allSubscribers']); 

// Вспомогательные страницы
Route::add('GET', '/hello', [Controller\Site::class, 'hello']);
Route::add('GET', '/phones', [Controller\Site::class, 'index']); // Старый список телефонов

Route::add('GET', '/subscriber/create', [Controller\Site::class, 'createSubscriber']);
Route::add('POST', '/subscriber/create', [Controller\Site::class, 'createSubscriber']);
