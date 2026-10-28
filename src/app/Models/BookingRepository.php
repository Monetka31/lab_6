<?php
// app/Models/BookingRepository.php — модель: единственное место, которое знает, где и как хранятся
// бронирования рабочих мест. Сейчас бронирования лежат в файле storage/bookings.json. В следующих
// работах файл заменит база данных, а контроллеры и шаблоны при этом менять не придётся: они работают
// только с методами этого класса.

declare(strict_types=1);

namespace App\Models;

final class BookingRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/bookings.json';
    }

    // Все бронирования (массив ассоциативных массивов)
    public function all(): array
    {
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    // Бронирование по номеру или null, если такого нет
    public function find(int $id): ?array
    {
        foreach ($this->all() as $booking) {
            if ($booking['id'] === $id) {
                return $booking;
            }
        }
        return null;
    }

    // Бронирования одного рабочего места (аналог TaskRepository::byProject())
    public function byWorkspace(int $workspaceId): array
    {
        return array_values(array_filter(
            $this->all(),
            fn(array $booking): bool => $booking['workspace_id'] === $workspaceId
        ));
    }

    // Добавляет бронирование, присваивает ему новый номер и сохраняет файл. Возвращает созданную запись.
    public function add(array $data): array
    {
        $bookings = $this->all();
        $ids = array_column($bookings, 'id');
        $booking = ['id' => ($ids ? max($ids) : 0) + 1] + $data;
        $bookings[] = $booking;

        file_put_contents(
            $this->file,
            json_encode($bookings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            LOCK_EX
        );
        return $booking;
    }
}
