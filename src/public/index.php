<?php
// public/index.php — единая точка входа (front controller).
// Веб-сервер отправляет сюда ВСЕ запросы, а дальше работает наш маршрутизатор.

declare(strict_types=1);

// TODO 1.1 (выполнено). Пути к каталогам проекта: нужны, чтобы подключать файлы независимо от того,
// откуда запущен сервер.
define('BASE_PATH', dirname(__DIR__));       // корень проекта (на уровень выше public/)
define('APP_PATH', BASE_PATH . '/app');      // каталог app/

require APP_PATH . '/autoload.php';          // автозагрузка классов App\...
require APP_PATH . '/helpers.php';           // функции e(), view(), redirect(), notFound(), config()

// TODO 1.2 (выполнено). Создаём маршрутизатор, регистрируем маршруты и запускаем обработку запроса.
$router = new App\Core\Router();
require BASE_PATH . '/routes/web.php';       // в этом файле маршруты добавляются в $router

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code(500);
    view('errors/500', ['message' => $e->getMessage()]);
}
