<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUserLogin($id)
    {
        return $this->model->find($id);
    }

    public function getAllUser($params)
    {
        $per_page = $params['per_page'] ?? 10;

        $users = $this->model;

        if (isset($params['keyword'])) {
            $users = $users->where('name', 'like', '%' . $params['keyword'] . '%');
        }

        return $users->paginate($per_page);
    }
}
