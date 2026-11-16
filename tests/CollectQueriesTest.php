<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Model\Department;
use Model\Room;
use Model\Subscriber;
use Model\Phone;

class CollectQueriesTest extends TestCase
{
    public function testCollectHelper(): void
    {
        $c = collect([1, 2, 3])->map(function ($x) {
            return $x * 10;
        });

        $this->assertEquals([10, 20, 30], $c->all());
        $this->assertEquals(3, $c->count());
    }

    public function testSelectAllSubscriberPhones(): void
    {
        $subscriber = Subscriber::create([
            'last_name' => 'Смирнов',
            'first_name' => 'Алексей',
            'birth_date' => '1988-01-01',
            'rooms_id' => 1,
            'status' => 'active'
        ]);

        Phone::create(['phone_number' => '201', 'subscribers_id' => $subscriber->subscribers_id, 'device_id' => 1]);
        Phone::create(['phone_number' => '202', 'subscribers_id' => $subscriber->subscribers_id, 'device_id' => 1]);
        Phone::create(['phone_number' => '203', 'subscribers_id' => $subscriber->subscribers_id, 'device_id' => 1]);

        $sub = Subscriber::with('phones')->where('subscribers_id', $subscriber->subscribers_id)->first();
        $phones = collect($sub->phones->toArray());

        $this->assertEquals(3, $phones->count());
        $numbers = $phones->pluck('phone_number')->all();
        $this->assertContains('201', $numbers);
        $this->assertContains('202', $numbers);
        $this->assertContains('203', $numbers);
    }

    public function testSelectPhonesByDepartment(): void
    {
        $deptA = Department::create(['name' => 'Отдел продаж', 'type' => 'Департамент']);
        $deptB = Department::create(['name' => 'Юридический отдел', 'type' => 'Служба']);

        $roomA = Room::create(['room_name' => 'Каб. 10', 'room_type' => 'Офис', 'departments_id' => $deptA->departments_id]);
        $roomB = Room::create(['room_name' => 'Каб. 20', 'room_type' => 'Офис', 'departments_id' => $deptB->departments_id]);

        $subA = Subscriber::create(['last_name' => 'Сидоров', 'first_name' => 'Олег', 'rooms_id' => $roomA->rooms_id]);
        $subB = Subscriber::create(['last_name' => 'Козлов', 'first_name' => 'Денис', 'rooms_id' => $roomB->rooms_id]);

        Phone::create(['phone_number' => '301', 'subscribers_id' => $subA->subscribers_id]);
        Phone::create(['phone_number' => '302', 'subscribers_id' => $subB->subscribers_id]);

        $allPhones = Phone::with('subscriber.room.department')->get();
        $filtered = collect($allPhones->toArray())->filter(function ($item) use ($deptA) {
            return (int)($item['subscriber']['room']['departments_id'] ?? 0) === (int)$deptA->departments_id;
        })->values();

        $this->assertGreaterThanOrEqual(1, $filtered->count());
        $phoneNumbers = $filtered->pluck('phone_number')->all();
        $this->assertContains('301', $phoneNumbers);
        $this->assertNotContains('302', $phoneNumbers);
    }

    public function testCountSubscribersByDepartmentAndRoom(): void
    {
        $dept = Department::create(['name' => 'Техподдержка', 'type' => 'Отдел']);
        $room1 = Room::create(['room_name' => 'Комната 401', 'room_type' => 'Серверная', 'departments_id' => $dept->departments_id]);
        $room2 = Room::create(['room_name' => 'Комната 402', 'room_type' => 'Рабочая', 'departments_id' => $dept->departments_id]);

        Subscriber::create(['last_name' => 'Савельев', 'first_name' => 'Игорь', 'rooms_id' => $room1->rooms_id]);
        Subscriber::create(['last_name' => 'Кузнецов', 'first_name' => 'Роман', 'rooms_id' => $room2->rooms_id]);
        Subscriber::create(['last_name' => 'Попов', 'first_name' => 'Максим', 'rooms_id' => $room2->rooms_id]);

        $allSubs = Subscriber::with('room.department')->get();
        $collection = collect($allSubs->toArray());

        $byDept = [];
        $byRoom = [];
        foreach ($collection as $item) {
            $d = $item['room']['department']['name'] ?? 'Без отдела';
            $r = $item['room']['room_name'] ?? 'Без комнаты';
            $byDept[$d] = ($byDept[$d] ?? 0) + 1;
            $byRoom[$r] = ($byRoom[$r] ?? 0) + 1;
        }

        $deptCounts = collect($byDept);
        $roomCounts = collect($byRoom);

        $this->assertEquals(3, $deptCounts->get('Техподдержка'));
        $this->assertEquals(1, $roomCounts->get('Комната 401'));
        $this->assertEquals(2, $roomCounts->get('Комната 402'));
    }
}
