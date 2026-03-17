<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;

class Site
{
   public function index(Request $request): string
{
    // Проверяем: если в запросе есть id, берем его, иначе ставим 1
    $id = $request->all()['id'] ?? 1; 
    
    $posts = Post::where('id', $id)->get();
    return (new View())->render('site.post', ['posts' => $posts]);
}

   public function hello(): string
   {
       return new View('site.hello', ['message' => 'hello working']);
   }
   public function signup(Request $request): string
   {
       if ($request->method==='POST' && User::create($request->all())){
           return new View('site.signup', ['message'=>'Вы успешно зарегистрированы']);
       }
       return new View('site.signup');
   }
}