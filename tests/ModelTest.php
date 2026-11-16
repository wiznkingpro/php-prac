<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Model\Department;
use Model\Room;
use Model\Subscriber;
use Model\Phone;
use Model\Device;

class ModelTest extends TestCase
{
    public function testDepartmentCreationAndRoomsRelation(): void
    {
        $dept = Department::create([
            'name' => 'Отдел разработки',
            'type' => 'Департамент'
        ]);

        $this->assertNotNull($dept->departments_id);
        $this->assertEquals('Отдел разработки', $dept->name);

        $room = Room::create([
            'room_name' => 'Кабинет 301',
            'room_type' => 'Рабочее помещение',
            'departments_id' => $dept->departments_id
        ]);

        $this->assertCount(1, $dept->rooms);
        $this->assertEquals($dept->departments_id, $room->department->departments_id);
    }

    public function testSubscriberCreationAndPhoneRelation(): void
    {
        $dept = Department::create([
            'name' => 'Бухгалтерия',
            'type' => 'Служба'
        ]);

        $room = Room::create([
            'room_name' => 'Кабинет 105',
            'room_type' => 'Офис',
            'departments_id' => $dept->departments_id
        ]);

        $subscriber = Subscriber::create([
            'last_name' => 'Иванов',
            'first_name' => 'Иван',
            'middle_name' => 'Иванович',
            'birth_date' => '1990-05-15',
            'rooms_id' => $room->rooms_id,
            'status' => 'active'
        ]);

        $this->assertEquals('Иванов', $subscriber->last_name);
        $this->assertEquals($room->rooms_id, $subscriber->room->rooms_id);

        $device = Device::create([
            'model_name' => 'Cisco 7940',
            'mac_address' => 'AA:BB:CC:DD:EE:01',
            'inventory_number' => 'INV-001',
            'device_type' => 'IP-Phone'
        ]);

        $phone1 = Phone::create([
            'phone_number' => '101',
            'subscribers_id' => $subscriber->subscribers_id,
            'device_id' => $device->device_id,
            'status' => 'active'
        ]);

        $phone2 = Phone::create([
            'phone_number' => '102',
            'subscribers_id' => $subscriber->subscribers_id,
            'device_id' => $device->device_id,
            'status' => 'active'
        ]);

        $subscriber->refresh();
        $this->assertCount(2, $subscriber->phones);
        $this->assertEquals($subscriber->subscribers_id, $phone1->subscriber->subscribers_id);
    }

    public function testPhoneAttachSubscriber(): void
    {
        $phone = Phone::create([
            'phone_number' => '555',
            'subscribers_id' => null,
            'device_id' => 1,
            'status' => 'active'
        ]);

        $this->assertNull($phone->subscribers_id);

        $subscriber = Subscriber::create([
            'last_name' => 'Петров',
            'first_name' => 'Петр',
            'middle_name' => 'Сергеевич',
            'birth_date' => '1995-10-20',
            'rooms_id' => 1,
            'status' => 'active'
        ]);

        $phone->subscribers_id = $subscriber->subscribers_id;
        $phone->save();

        $phone->refresh();
        $this->assertEquals($subscriber->subscribers_id, $phone->subscribers_id);
        $this->assertEquals('Петров', $phone->subscriber->last_name);
    }
}
