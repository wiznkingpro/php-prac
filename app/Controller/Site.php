<?php

namespace Controller;

use Model\Phone; 
use Model\Subscriber; 
use Model\User;
use Model\Device;
use Model\Department;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function allSubscribers(Request $request): string
    {
        $query = Subscriber::with('phones');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhereHas('phones', function($q) use ($search) {
                      $q->where('phone_number', 'like', "%$search%");
                  });
            });
        }

        $subscribers = $query->get();
        return (new View())->render('site.subscribers', ['subscribers' => $subscribers]);
    }

    public function hello(): string
    {
        return (new View())->render('site.hello', ['message' => 'Система управления абонентами']);
    }


    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {
            $data = $request->all();
            $login = trim($data['login'] ?? '');
            $password = trim($data['password'] ?? '');

            if (empty($login) || empty($password)) {
                $departments = Department::all();
                return (new View())->render('site.signup', [
                    'departments' => $departments,
                    'message' => 'Логин и пароль обязательны'
                ]);
            }

            $exists = User::where('login', $login)->first();
            if ($exists) {
                $departments = Department::all();
                return (new View())->render('site.signup', [
                    'departments' => $departments,
                    'message' => 'Этот логин уже занят'
                ]);
            }

            $user = User::create([
                'login' => $login,
                'password' => $password,
                'first_name' => trim($data['first_name'] ?? ''),
                'last_name' => trim($data['last_name'] ?? ''),
                'middle_name' => trim($data['middle_name'] ?? ''),
                'role' => 'user',
                'status' => 'active',
                'departments_id' => (int)($data['departments_id'] ?? 1)
            ]);

            if ($user) {
                return (new View())->render('site.signup', ['message' => 'Пользователь зарегистрирован']);
            }
        }
        
        $departments = Department::all();
        return (new View())->render('site.signup', ['departments' => $departments]);
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') return (new View())->render('site.login');
        
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/');
        }
        return (new View())->render('site.login', ['message' => 'Неправильные данные']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/');
    }


    public function adminPanel(Request $request): string
    {
        if (!Auth::user() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            app()->route->redirect('/');
        }
        
        $users = User::all();
        $departments = Department::all();
        return (new View())->render('site.admin', ['users' => $users, 'departments' => $departments]);
    }

    public function adminCreateUser(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $data = $request->all();
            $login = trim($data['login'] ?? '');
            $password = trim($data['password'] ?? '');
            if (!empty($login) && !empty($password)) {
                $role = in_array($data['role'] ?? '', ['user', 'admin'], true) ? $data['role'] : 'user';
                User::create([
                    'login' => $login,
                    'password' => $password,
                    'first_name' => trim($data['first_name'] ?? ''),
                    'last_name' => trim($data['last_name'] ?? ''),
                    'middle_name' => trim($data['middle_name'] ?? ''),
                    'role' => $role,
                    'status' => 'active',
                    'departments_id' => (int)($data['departments_id'] ?? 1)
                ]);
            }
        }
        app()->route->redirect('/admin');
    }


    public function createSubscriber(Request $request): string
    {
        if (!Auth::user() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            app()->route->redirect('/');
        }

        if ($request->method === 'POST') {
            $data = $request->all();
            $device = Device::create([
                'model_name' => 'Auto-Gen ' . rand(1, 99),
                'mac_address' => implode(':', str_split(substr(md5(mt_rand()), 0, 12), 2)),
                'inventory_number' => 'INV-' . mt_rand(1000, 9999),
                'device_type' => 'IP-Phone'
            ]);

            $subscriber = Subscriber::create([
                'last_name' => trim($data['last_name'] ?? ''),
                'first_name' => trim($data['first_name'] ?? ''),
                'middle_name' => trim($data['middle_name'] ?? ''),
                'birth_date' => $data['birth_date'] ?? null,
                'rooms_id' => (int)($data['rooms_id'] ?? 1),
                'status' => 'active'
            ]);

            if ($subscriber && $device && !empty($data['phone_number'])) {
                Phone::updateOrCreate(['phone_number' => trim($data['phone_number'])], [
                    'subscribers_id' => $subscriber->subscribers_id,
                    'device_id' => $device->device_id,
                    'status' => 'active'
                ]);
                app()->route->redirect('/');
            }
        }
        return (new View())->render('site.create_subscriber');
    }

    public function editSubscriber(Request $request): string
    {
        if (!Auth::user() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            app()->route->redirect('/');
        }
        
        $id = (int)($request->get('id') ?? $request->id);
        $subscriber = Subscriber::with('phones')->where('subscribers_id', $id)->first();
        if (!$subscriber) {
            app()->route->redirect('/');
        }

        return (new View())->render('site.edit_subscriber', ['subscriber' => $subscriber]);
    }

    public function updateSubscriber(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $data = $request->all();
            $id = (int)($data['subscribers_id'] ?? 0);
            $sub = Subscriber::where('subscribers_id', $id)->first();
            if ($sub) {
                $sub->update([
                    'last_name' => trim($data['last_name'] ?? $sub->last_name),
                    'first_name' => trim($data['first_name'] ?? $sub->first_name),
                    'middle_name' => trim($data['middle_name'] ?? $sub->middle_name),
                    'birth_date' => $data['birth_date'] ?? $sub->birth_date,
                    'rooms_id' => (int)($data['rooms_id'] ?? $sub->rooms_id)
                ]);
                if (!empty($data['phone_number'])) {
                    Phone::where('subscribers_id', $sub->subscribers_id)->update([
                        'phone_number' => trim($data['phone_number'])
                    ]);
                }
            }
        }
        app()->route->redirect('/');
    }

    public function deleteSubscriber(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $id = (int)($request->get('id') ?? $request->id);
            if ($id > 0) {
                Subscriber::where('subscribers_id', $id)->delete();
            }
        }
        app()->route->redirect('/');
    }

    public function profile(): string
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }

        $user = Auth::user();

        return (new View())->render('site.profile', ['user' => $user]);
    }

    public function superAdminPanel(Request $request): string
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            app()->route->redirect('/');
        }

        $admins = User::whereIn('role', ['admin', 'user', 'super_admin'])->get();
        $allPhones = Phone::with('subscriber')->get();

        return (new View())->render('site.super_admin', [
            'admins' => $admins,
            'phones' => $allPhones
        ]);
    }

    public function setRole(Request $request): void
    {
        if (Auth::user() && Auth::user()->role === 'super_admin') {
            $userId = (int)$request->get('user_id');
            $newRole = $request->get('role');
            if (in_array($newRole, ['user', 'admin', 'super_admin'], true)) {
                $user = User::where('users_id', $userId)->first();
                if ($user && (int)$user->users_id !== (int)Auth::user()->users_id) {
                    $user->role = $newRole;
                    $user->save();
                }
            }
        }
        app()->route->redirect('/super-admin');
    }
}