<?php

namespace Controller;

use Model\Phone; 
use Model\Subscriber; 
use Model\User;
use Model\Department;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    // Страница 1: Все абоненты и их телефоны
    public function allSubscribers(): string
    {
        $subscribers = Subscriber::with('phones')->get();
        return (new View())->render('site.subscribers', ['subscribers' => $subscribers]);
    }

    public function index(Request $request): string
    {
        $phones = Phone::all();
        return (new View())->render('site.post', ['phones' => $phones]);
    }

    public function hello(): string
    {
        return (new View())->render('site.hello', ['message' => 'Система управления абонентами']);
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {
            if (User::create($request->all())) {
                app()->route->redirect('/login');
            }
        }
    
        $departments = Department::all();
        
        return (new View())->render('site.signup', ['departments' => $departments]);
    }

    public function login(Request $request): string
{
    if ($request->method === 'GET') {
        return (new View())->render('site.login');
    }

 
    if (Auth::attempt($request->all())) {
        app()->route->redirect('/hello');
    }

    return (new View())->render('site.login', ['message' => 'Неправильные фамилия или пароль']);
}
    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    // Создание абонента (только для админа)
    public function createSubscriber(Request $request): string
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return (new View())->render('site.hello', ['message' => 'Доступ только для администратора']);
        }

        if ($request->method === 'POST') {
            if (Subscriber::create($request->all())) {
                app()->route->redirect('/');
            }
        }
        return (new View())->render('site.create_subscriber');
    }

}