<?php
use Src\Route;
use Controller\Site;


Route::add('GET', '/login', [Controller\Site::class, 'login']);
Route::add('POST', '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/signup', [Controller\Site::class, 'signup']);
Route::add('POST', '/signup', [Controller\Site::class, 'signup']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
Route::add('GET', '/profile', [Site::class, 'profile']);

Route::add('GET', '/', [Controller\Site::class, 'allSubscribers']); 


Route::add('GET', '/phones', [Controller\Site::class, 'index']); 

Route::add('GET', '/subscriber/create', [Controller\Site::class, 'createSubscriber']);
Route::add('POST', '/subscriber/create', [Controller\Site::class, 'createSubscriber']);

Route::add('POST', '/admin/user/create', [Controller\Site::class, 'adminCreateUser']);
Route::add('POST', '/admin/user/update', [Controller\Site::class, 'updateUser']);
Route::add('GET|POST', '/subscriber/edit', [Controller\Site::class, 'editSubscriber']);


Route::add('GET', '/subscriber/edit', [Controller\Site::class, 'editSubscriber']);
Route::add('POST', '/subscriber/update', [Controller\Site::class, 'updateSubscriber']);
Route::add('POST', '/subscriber/delete', [Controller\Site::class, 'deleteSubscriber']);

Route::add('GET', '/super-admin', [Controller\Site::class, 'superAdminPanel']);
Route::add('POST', '/set-role', [Controller\Site::class, 'setRole']);