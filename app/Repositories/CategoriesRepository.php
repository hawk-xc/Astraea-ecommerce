<?php

namespace App\Repositories;

use App\Interfaces\CategoriesInterface;
use App\Models\Categories;

class CategoriesRepository implements CategoriesInterface
{
    public function getAll()
    {
        return Categories::orderBy('updated_at', 'desc')->get(['id', 'name', 'description']);
    }

    public function getAllFo()
    {
        return Categories::orderBy('updated_at', 'desc')->get(['id', 'name']);
    }

    public function getById($id)
    {
        return Categories::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description']);
    }

    public function store($data)
    {
        return Categories::create($data);
    }

    public function edit($id, $data)
    {
        return Categories::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Categories::destroy($id);
    }
}
