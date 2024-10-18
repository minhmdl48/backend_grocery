<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function getAll();

    public function find(string $id);

    public function create(array $attributes = []);

    public function update($id, array $attributes = []);

    public function delete(string $id);
}
