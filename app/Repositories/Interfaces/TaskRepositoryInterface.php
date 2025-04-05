<?php


namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
public function all(): Collection;
public function create(array $data): Task;
public function find(int $id): Task;
public function update(int $id, array $data): Task;
public function delete(int $id): bool|int;
public function reschedule(int $id, string $newDate): Task;
public function duplicate(int $id): Task;
}
