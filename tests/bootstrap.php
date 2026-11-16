<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Schema\Blueprint;

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => ':memory:',
    'prefix' => '',
]);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$schema = Capsule::schema();

$schema->create('departments', function (Blueprint $table) {
    $table->increments('departments_id');
    $table->string('name');
    $table->string('type');
});

$schema->create('rooms', function (Blueprint $table) {
    $table->increments('rooms_id');
    $table->string('room_name');
    $table->string('room_type');
    $table->integer('departments_id');
});

$schema->create('users', function (Blueprint $table) {
    $table->increments('users_id');
    $table->string('login');
    $table->string('password');
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('middle_name')->nullable();
    $table->string('role')->default('user');
    $table->string('status')->default('active');
    $table->integer('departments_id')->nullable();
});

$schema->create('subscribers', function (Blueprint $table) {
    $table->increments('subscribers_id');
    $table->string('last_name');
    $table->string('first_name');
    $table->string('middle_name')->nullable();
    $table->string('birth_date')->nullable();
    $table->string('status')->default('active');
    $table->integer('rooms_id')->nullable();
    $table->integer('users_id')->nullable();
});

$schema->create('device', function (Blueprint $table) {
    $table->increments('device_id');
    $table->string('model_name');
    $table->string('mac_address');
    $table->string('inventory_number');
    $table->string('device_type');
});

$schema->create('phones', function (Blueprint $table) {
    $table->increments('phones_id');
    $table->string('phone_number');
    $table->integer('subscribers_id')->nullable();
    $table->integer('device_id')->nullable();
    $table->string('status')->default('active');
});
