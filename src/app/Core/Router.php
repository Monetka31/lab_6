<?php
// app/Core/Router.php — маршрутизатор: по методу и адресу запроса выбирает контроллер.

declare(strict_types=1);

namespace App\Core;

final class Router
{
    // Таблица маршрутов. Каждый маршрут — массив из трёх значений:
    //   метод (GET/POST), шаблон адреса (/bookings/{id}) и обработчик ([Класс::class, 'метод'])
    private array $routes = [];

    // Регистрация маршрутов: $router->get('/bookings', [BookingController::class, 'index']);  (готово)
    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    // ---------- Шаг 2. TODO 2.1 (выполнено) ----------
    private function add(string $method, string $path, array $handler): void
    {
        // Запоминаем маршрут в таблице: регистрация маршрута (GET/POST, адрес и обработчик).
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    // Находит подходящий маршрут и передаёт управление контроллеру (ГОТОВО: изучите и разберитесь, как это работает)
    public function dispatch(string $method, string $uri): void
    {
        // Из /bookings/5?status=confirmed берём только путь /bookings/5; завершающий «/» отбрасываем
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/') ?: '/';

        // Маршруты проверяются по порядку: срабатывает первый подходящий
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;                                  // другой метод: пропускаем маршрут
            }
            $params = $this->match($route['path'], $path);
            if ($params === null) {
                continue;                                  // адрес не подходит: пробуем следующий маршрут
            }

            [$class, $action] = $route['handler'];         // например, BookingController::class и 'show'
            $controller = new $class();                    // создаём объект контроллера
            $controller->$action(...$params);              // вызываем его метод; значения {id} из адреса передаются как параметры
            return;                                        // маршрут найден и выполнен: дальше искать не нужно
        }

        // Сюда программа доходит, только если ни один маршрут не подошёл.
        // TODO 2.2 (выполнено). Показываем страницу 404:
        http_response_code(404);                         // код ответа «не найдено»
        view('errors/404', ['path' => $path]);           // готовый шаблон страницы
    }

    // Сравнивает шаблон маршрута с адресом (ГОТОВО, менять не нужно).
    //   match('/bookings/{id}', '/bookings/5')  вернёт ['id' => '5']
    //   match('/bookings', '/bookings')         вернёт []   (маршрут подошёл, параметров нет)
    //   match('/bookings', '/workspaces')       вернёт null (не подошёл)
    private function match(string $pattern, string $path): ?array
    {
        // {id} превращаем в кусочек регулярного выражения «любые символы, кроме /»
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
        if (!preg_match($regex, $path, $matches)) {
            return null;
        }
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);   // оставляем только именованные части
    }
}
