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
Route::add('GET', '/phones', [Controller\Site::class, 'phones']); 
Route::add('POST', '/phone/create', [Controller\Site::class, 'createPhone'])->middleware('admin', 'csrf');
Route::add('POST', '/phone/attach', [Controller\Site::class, 'attachPhone'])->middleware('admin', 'csrf');

Route::add('GET', '/departments', [Controller\Site::class, 'departments']);
Route::add('POST', '/department/create', [Controller\Site::class, 'createDepartment'])->middleware('admin', 'csrf');

Route::add('GET', '/rooms', [Controller\Site::class, 'rooms']);
Route::add('POST', '/room/create', [Controller\Site::class, 'createRoom'])->middleware('admin', 'csrf');

Route::add('GET', '/subscriber/phones', [Controller\Site::class, 'subscriberPhones']);
Route::add('GET', '/department/phones', [Controller\Site::class, 'departmentPhones']);
Route::add('GET', '/stats', [Controller\Site::class, 'stats']);

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

// API (ПР6)
Route::add('GET', '/api/subscribers', [Controller\Site::class, 'apiSubscribers']);
Route::add('POST', '/api/subscribers', [Controller\Site::class, 'apiCreateSubscriber']);
Route::add('GET', '/api/subscriber', [Controller\Site::class, 'apiSubscriber']);
Route::add('GET', '/api/subscriber/phones', [Controller\Site::class, 'apiSubscriberPhones']);
Route::add('GET', '/api/department/phones', [Controller\Site::class, 'apiDepartmentPhones']);
Route::add('GET', '/api/stats/counts', [Controller\Site::class, 'apiStatsCounts']);
Route::add('GET', '/api/departments', [Controller\Site::class, 'apiDepartments']);
Route::add('GET', '/api/rooms', [Controller\Site::class, 'apiRooms']);
Route::add('GET', '/api/phones', [Controller\Site::class, 'apiPhones']);
Route::add('POST', '/api/phones/attach', [Controller\Site::class, 'apiAttachPhone']);