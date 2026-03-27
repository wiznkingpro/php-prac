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
            if (User::create($request->all())) {
                app()->route->redirect('/login');
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
        if (!Auth::user() || Auth::user()->role !== 'admin') app()->route->redirect('/');
        
        $users = User::all();
        $departments = Department::all();
        return (new View())->render('site.admin', ['users' => $users, 'departments' => $departments]);
    }

    public function adminCreateUser(Request $request): void
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            User::create($request->all());
        }
        app()->route->redirect('/admin');
    }


    public function createSubscriber(Request $request): string
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') app()->route->redirect('/');

        if ($request->method === 'POST') {
            $data = $request->all();
            $device = Device::create([
                'model_name' => 'Auto-Gen ' . rand(1, 99),
                'mac_address' => implode(':', str_split(substr(md5(mt_rand()), 0, 12), 2)),
                'inventory_number' => 'INV-' . mt_rand(1000, 9999),
                'device_type' => 'IP-Phone'
            ]);

            $subscriber = Subscriber::create([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'birth_date' => $data['birth_date'],
                'rooms_id' => $data['rooms_id'],
                'status' => 'active'
            ]);

            if ($subscriber && $device && !empty($data['phone_number'])) {
                Phone::updateOrCreate(['phone_number' => $data['phone_number']], [
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
        if (!Auth::user() || Auth::user()->role !== 'admin') app()->route->redirect('/');
        
        $subscriber = Subscriber::with('phones')->where('subscribers_id', $request->id)->first();
        if (!$subscriber) app()->route->redirect('/');

        return (new View())->render('site.edit_subscriber', ['subscriber' => $subscriber]);
    }

    public function updateSubscriber(Request $request): void
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            $data = $request->all();
            $sub = Subscriber::where('subscribers_id', $data['subscribers_id'])->first();
            if ($sub) {
                $sub->update($data);
                if (!empty($data['phone_number'])) {
                    Phone::where('subscribers_id', $sub->subscribers_id)->update([
                        'phone_number' => $data['phone_number']
                    ]);
                }
            }
        }
        app()->route->redirect('/');
    }

    public function deleteSubscriber(Request $request): void
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            Subscriber::where('subscribers_id', $request->id)->delete();
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
        $userId = $request->get('user_id');
        $newRole = $request->get('role');
        $user = User::where('users_id', $userId)->first();
        if ($user && (int)$user->users_id !== (int)Auth::user()->users_id) {
            $user->role = $newRole;
            $user->save();
        }
    }
    app()->route->redirect('/super-admin');
}
}