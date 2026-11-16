<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\Site;
use Src\Request;
use Model\Department;
use Model\Room;
use Model\Subscriber;
use Model\Phone;

class ApiTest extends TestCase
{
    public function testApiSubscribersReturnsJson(): void
    {
        $site = new Site();
        $request = new Request();

        ob_start();
        $site->apiSubscribers($request);
        $output = ob_get_clean();

        $data = json_decode($output, true);
        $this->assertIsArray($data);
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('data', $data);
    }

    public function testApiStatsCountsReturnsGroupedCounts(): void
    {
        $dept = Department::create(['name' => 'Отдел маркетинга', 'type' => 'Служба']);
        $room = Room::create(['room_name' => 'Офис 500', 'room_type' => 'Офис', 'departments_id' => $dept->departments_id]);

        Subscriber::create([
            'last_name' => 'Волков',
            'first_name' => 'Дмитрий',
            'rooms_id' => $room->rooms_id
        ]);

        $site = new Site();
        $request = new Request();

        ob_start();
        $site->apiStatsCounts($request);
        $output = ob_get_clean();

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('by_department', $data);
        $this->assertArrayHasKey('by_room', $data);
        $this->assertGreaterThanOrEqual(1, $data['by_department']['Отдел маркетинга']);
    }

    public function testApiDepartmentsAndRooms(): void
    {
        $site = new Site();
        $request = new Request();

        ob_start();
        $site->apiDepartments($request);
        $output = ob_get_clean();
        $deptData = json_decode($output, true);
        $this->assertTrue($deptData['success']);

        ob_start();
        $site->apiRooms($request);
        $output = ob_get_clean();
        $roomData = json_decode($output, true);
        $this->assertTrue($roomData['success']);
    }
}
