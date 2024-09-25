<?php

namespace App\Repositories;

use App\Interfaces\ProfilesInterface;
use App\Models\User;

class ProfilesRepository implements ProfilesInterface
{
    public function getById($id)
    {
        return User::whereId($id)->with("role")->first();
    }

    public function edit($id, $data)
    {
        return User::whereId($id)->update($data);
    }
}
