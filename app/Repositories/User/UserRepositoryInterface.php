<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getUserLogin($id);

    public function getAllUser($params);
}
