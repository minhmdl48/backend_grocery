<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    public $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes = [])
    {
        $item = $this->find($id);
        if ($item) {
            $item->update($attributes);
            return $item;
        }
        return null;
    }

    public function delete($id)
    {
        $item = $this->find($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }

    public function a() {}
}
