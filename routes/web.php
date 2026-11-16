<?php
use Src\Route;
use Controller\Site;


Route::add('GET', '/login', [Controller\Site::class, 'login']);
Route::add('POST', '/login', [Controller\Site::class, 'login'])->middleware('csrf');
Route::add('GET', '/signup', [Controller\Site::class, 'signup']);
Route::add('POST', '/signup', [Controller\Site::class, 'signup'])->middleware('csrf');
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
Route::add('GET', '/profile', [Site::class, 'profile'])->middleware('auth');

Route::add('GET', '/', [Controller\Site::class, 'allSubscribers']); 
Route::add('GET', '/phones', [Controller\Site::class, 'index']); 

Route::add('GET', '/admin', [Controller\Site::class, 'adminPanel'])->middleware('admin');
Route::add('GET', '/subscriber/create', [Controller\Site::class, 'createSubscriber'])->middleware('admin');
Route::add('POST', '/subscriber/create', [Controller\Site::class, 'createSubscriber'])->middleware('admin', 'csrf');

Route::add('POST', '/admin/user/create', [Controller\Site::class, 'adminCreateUser'])->middleware('admin', 'csrf');
Route::add('POST', '/admin/user/update', [Controller\Site::class, 'updateUser'])->middleware('admin', 'csrf');

Route::add('GET', '/subscriber/edit', [Controller\Site::class, 'editSubscriber'])->middleware('admin');
Route::add('POST', '/subscriber/update', [Controller\Site::class, 'updateSubscriber'])->middleware('admin', 'csrf');
Route::add('POST', '/subscriber/delete', [Controller\Site::class, 'deleteSubscriber'])->middleware('admin', 'csrf');

Route::add('GET', '/super-admin', [Controller\Site::class, 'superAdminPanel'])->middleware('super_admin');
Route::add('POST', '/set-role', [Controller\Site::class, 'setRole'])->middleware('super_admin', 'csrf');