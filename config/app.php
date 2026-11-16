<?php
return [
   //Класс аутентификации
   'auth' => \Src\Auth\Auth::class,
   //Клас пользователя
   'identity' => \Model\User::class,
   //Классы для middleware
   'routeMiddleware' => [
       'auth' => \Middlewares\AuthMiddleware::class,
       'admin' => \Middlewares\AdminMiddleware::class,
       'super_admin' => \Middlewares\SuperAdminMiddleware::class,
       'csrf' => \Middlewares\CSRFMiddleware::class,
   ]
];