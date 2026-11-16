<?php

namespace Controller;

use Model\Phone; 
use Model\Subscriber; 
use Model\User;
use Model\Device;
use Model\Department;
use Model\Room;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function allSubscribers(Request $request): string
    {
        $query = Subscriber::with(['phones', 'room.department']);

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
        $rooms = Room::with('department')->get();
        return (new View())->render('site.create_subscriber', ['rooms' => $rooms]);
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

    public function departments(Request $request): string
    {
        $departments = Department::all();
        return (new View())->render('site.departments', ['departments' => $departments]);
    }

    public function createDepartment(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $name = trim($request->get('name') ?? '');
            $type = trim($request->get('type') ?? '');
            if (!empty($name) && !empty($type)) {
                Department::create([
                    'name' => $name,
                    'type' => $type
                ]);
            }
        }
        app()->route->redirect('/departments');
    }

    public function rooms(Request $request): string
    {
        $rooms = Room::with('department')->get();
        $departments = Department::all();
        return (new View())->render('site.rooms', ['rooms' => $rooms, 'departments' => $departments]);
    }

    public function createRoom(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $name = trim($request->get('room_name') ?? '');
            $type = trim($request->get('room_type') ?? '');
            $deptId = (int)($request->get('departments_id') ?? 0);
            if (!empty($name) && !empty($type) && $deptId > 0) {
                Room::create([
                    'room_name' => $name,
                    'room_type' => $type,
                    'departments_id' => $deptId
                ]);
            }
        }
        app()->route->redirect('/rooms');
    }

    public function phones(Request $request): string
    {
        $phones = Phone::with(['subscriber.room.department'])->get();
        $subscribers = Subscriber::all();
        return (new View())->render('site.phones', ['phones' => $phones, 'subscribers' => $subscribers]);
    }

    public function createPhone(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $phoneNumber = trim($request->get('phone_number') ?? '');
            $subId = !empty($request->get('subscribers_id')) ? (int)$request->get('subscribers_id') : null;
            if (!empty($phoneNumber)) {
                Phone::create([
                    'phone_number' => $phoneNumber,
                    'subscribers_id' => $subId,
                    'device_id' => 1,
                    'status' => 'active'
                ]);
            }
        }
        app()->route->redirect('/phones');
    }

    public function attachPhone(Request $request): void
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            $phoneId = (int)($request->get('phones_id') ?? 0);
            $subId = (int)($request->get('subscribers_id') ?? 0);
            $phone = Phone::where('phones_id', $phoneId)->first();
            if ($phone && $subId > 0) {
                $phone->subscribers_id = $subId;
                $phone->save();
            }
        }
        app()->route->redirect('/phones');
    }

    public function subscriberPhones(Request $request): string
    {
        $subId = (int)($request->get('id') ?? $request->id);
        $subscriber = Subscriber::with(['phones', 'room.department'])->where('subscribers_id', $subId)->first();
        
        $phones = collect($subscriber ? $subscriber->phones->toArray() : [])->all();

        return (new View())->render('site.subscriber_phones', [
            'subscriber' => $subscriber,
            'phones' => $phones
        ]);
    }

    public function departmentPhones(Request $request): string
    {
        $deptId = (int)($request->get('id') ?? $request->id ?? 0);
        $departments = Department::all();
        $selectedDepartment = $deptId ? Department::where('departments_id', $deptId)->first() : null;

        $allPhones = Phone::with(['subscriber.room.department'])->get();
        $phonesCollection = collect($allPhones->toArray());

        if ($deptId > 0) {
            $phones = $phonesCollection->filter(function ($item) use ($deptId) {
                return (int)($item['subscriber']['room']['departments_id'] ?? 0) === $deptId;
            })->values()->all();
        } else {
            $phones = $phonesCollection->all();
        }

        return (new View())->render('site.department_phones', [
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartment,
            'selectedId' => $deptId,
            'phones' => $phones
        ]);
    }

    public function stats(Request $request): string
    {
        $subscribers = Subscriber::with(['room.department'])->get();
        $subCollection = collect($subscribers->toArray());

        $byDept = [];
        $byRm = [];

        foreach ($subCollection as $item) {
            $dName = $item['room']['department']['name'] ?? 'Без подразделения';
            $byDept[$dName] = ($byDept[$dName] ?? 0) + 1;

            $rName = $item['room']['room_name'] ?? 'Без помещения';
            $byRm[$rName] = ($byRm[$rName] ?? 0) + 1;
        }

        $byDepartment = collect($byDept)->all();
        $byRoom = collect($byRm)->all();

        return (new View())->render('site.stats', [
            'byDepartment' => $byDepartment,
            'byRoom' => $byRoom
        ]);
    }

    // --- JSON API (ПР6) ---

    public function json($data, int $status = 200): void
    {
        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        if (!defined('PHPUNIT_RUNNING')) {
            exit;
        }
    }

    public function apiSubscribers(Request $request): void
    {
        $subscribers = Subscriber::with(['phones', 'room.department'])->get();
        $this->json(['success' => true, 'data' => collect($subscribers->toArray())->all()]);
    }

    public function apiSubscriber(Request $request): void
    {
        $id = (int)($request->get('id') ?? $request->id);
        $subscriber = Subscriber::with(['phones', 'room.department'])->where('subscribers_id', $id)->first();
        if (!$subscriber) {
            $this->json(['success' => false, 'error' => 'Subscriber not found'], 404);
        }
        $this->json(['success' => true, 'data' => $subscriber->toArray()]);
    }

    public function apiCreateSubscriber(Request $request): void
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            $this->json(['success' => false, 'error' => 'Forbidden'], 403);
        }

        $data = $request->all();
        if (empty($data['last_name']) || empty($data['first_name'])) {
            $this->json(['success' => false, 'error' => 'last_name and first_name required'], 400);
        }

        $subscriber = Subscriber::create([
            'last_name' => trim($data['last_name']),
            'first_name' => trim($data['first_name']),
            'middle_name' => trim($data['middle_name'] ?? ''),
            'birth_date' => $data['birth_date'] ?? null,
            'rooms_id' => (int)($data['rooms_id'] ?? 1),
            'status' => 'active'
        ]);

        if (!empty($data['phone_number'])) {
            Phone::create([
                'phone_number' => trim($data['phone_number']),
                'subscribers_id' => $subscriber->subscribers_id,
                'device_id' => 1,
                'status' => 'active'
            ]);
        }

        $this->json(['success' => true, 'data' => $subscriber->load(['phones', 'room.department'])], 201);
    }

    public function apiSubscriberPhones(Request $request): void
    {
        $subId = (int)($request->get('id') ?? $request->id);
        $subscriber = Subscriber::with('phones')->where('subscribers_id', $subId)->first();
        if (!$subscriber) {
            $this->json(['success' => false, 'error' => 'Subscriber not found'], 404);
        }
        $phones = collect($subscriber->phones->toArray())->all();
        $this->json(['success' => true, 'subscriber' => $subscriber->last_name, 'phones' => $phones]);
    }

    public function apiDepartmentPhones(Request $request): void
    {
        $deptId = (int)($request->get('id') ?? $request->id ?? 0);
        $allPhones = Phone::with(['subscriber.room.department'])->get();
        $collection = collect($allPhones->toArray());

        if ($deptId > 0) {
            $phones = $collection->filter(function ($item) use ($deptId) {
                return (int)($item['subscriber']['room']['departments_id'] ?? 0) === $deptId;
            })->values()->all();
        } else {
            $phones = $collection->all();
        }

        $this->json(['success' => true, 'department_id' => $deptId, 'phones' => $phones]);
    }

    public function apiStatsCounts(Request $request): void
    {
        $subscribers = Subscriber::with(['room.department'])->get();
        $collection = collect($subscribers->toArray());

        $byDept = [];
        $byRm = [];

        foreach ($collection as $item) {
            $dName = $item['room']['department']['name'] ?? 'Без подразделения';
            $byDept[$dName] = ($byDept[$dName] ?? 0) + 1;

            $rName = $item['room']['room_name'] ?? 'Без помещения';
            $byRm[$rName] = ($byRm[$rName] ?? 0) + 1;
        }

        $this->json([
            'success' => true,
            'by_department' => collect($byDept)->all(),
            'by_room' => collect($byRm)->all()
        ]);
    }

    public function apiDepartments(Request $request): void
    {
        $departments = Department::with('rooms')->get();
        $this->json(['success' => true, 'data' => collect($departments->toArray())->all()]);
    }

    public function apiRooms(Request $request): void
    {
        $rooms = Room::with('department')->get();
        $this->json(['success' => true, 'data' => collect($rooms->toArray())->all()]);
    }

    public function apiPhones(Request $request): void
    {
        $phones = Phone::with(['subscriber.room.department'])->get();
        $this->json(['success' => true, 'data' => collect($phones->toArray())->all()]);
    }

    public function apiAttachPhone(Request $request): void
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')) {
            $this->json(['success' => false, 'error' => 'Forbidden'], 403);
        }

        $phoneId = (int)($request->get('phones_id') ?? 0);
        $subId = (int)($request->get('subscribers_id') ?? 0);
        $phone = Phone::where('phones_id', $phoneId)->first();
        if (!$phone || !$subId) {
            $this->json(['success' => false, 'error' => 'Phone or subscriber not found'], 404);
        }

        $phone->subscribers_id = $subId;
        $phone->save();

        $this->json(['success' => true, 'data' => $phone]);
    }
}