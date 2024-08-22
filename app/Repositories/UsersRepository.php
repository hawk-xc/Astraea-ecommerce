<?php

namespace App\Repositories;

use App\Interfaces\UsersInterface;
use App\Models\User;

class UsersRepository implements UsersInterface
{
    public function getAll()
    {
        return User::with("role")->get(['id', 'name', 'role_id', 'username', 'email', 'address']);
    }

    public function getSearch($s)
    {
        return User::where('name', 'like', '%'.$s.'%')->orWhere('username', 'like', '%'.$s.'%')->get(['id', 'name', 'username']);
    }

    public function getById($id)
    {
        return User::where('id', $id)->with("role")->firstOrFail(['id', 'name', 'role_id', 'username', 'email', 'address']);
    }

    public function store($data)
    {
        return User::create($data);
    }

    public function edit($id, $data)
    {
        return User::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}
