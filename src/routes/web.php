<?php
// routes/web.php — таблица маршрутов приложения: «какой адрес — какой контроллер и метод».
// Переменная $router создаётся в public/index.php.

use App\Controllers\HomeController;
use App\Controllers\WorkspaceController;
use App\Controllers\BookingController;

// TODO 2.3 (выполнено). Семь маршрутов системы бронирования рабочих мест.
// ВАЖНО: /bookings/create зарегистрирован РАНЬШЕ /bookings/{id}, иначе слово
// «create» было бы принято за значение {id}.

$router->get('/', [HomeController::class, 'index']);

$router->get('/bookings', [BookingController::class, 'index']);
$router->get('/bookings/create', [BookingController::class, 'create']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->get('/bookings/{id}', [BookingController::class, 'show']);

$router->get('/workspaces', [WorkspaceController::class, 'index']);
$router->get('/workspaces/{id}', [WorkspaceController::class, 'show']);
