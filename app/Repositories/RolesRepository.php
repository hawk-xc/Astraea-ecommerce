<?php

namespace App\Repositories;

use App\Interfaces\RolesInterface;
use App\Models\Role;

class RolesRepository implements RolesInterface
{
    public function getAll()
    {
        return Role::get(['id', 'name']);
    }

    public function getById($id)
    {
        return Role::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description']);
    }

    public function store($data)
    {
        return Role::create($data);
    }

    public function edit($id, $data)
    {
        return Role::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Role::destroy($id);
    }
}
