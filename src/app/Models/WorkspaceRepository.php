<?php
// app/Models/WorkspaceRepository.php — модель рабочих мест.
// Единственное место, которое знает, где и как хранятся рабочие места (сейчас — storage/workspaces.json).

declare(strict_types=1);

namespace App\Models;

final class WorkspaceRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/workspaces.json';
    }

    // Все рабочие места
    public function all(): array
    {
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    // Рабочее место по номеру или null, если такого нет
    public function find(int $id): ?array
    {
        foreach ($this->all() as $workspace) {
            if ($workspace['id'] === $id) {
                return $workspace;
            }
        }
        return null;
    }
}
