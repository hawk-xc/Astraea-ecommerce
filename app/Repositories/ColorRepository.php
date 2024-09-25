<?php

namespace App\Repositories;

use App\Interfaces\ColorInterface;
use App\Models\Color;

class ColorRepository implements ColorInterface
{
    public function getAll()
    {
        return Color::orderBy('updated_at', 'desc')->get(['id', 'name', 'description']);
    }

    public function getAllFo()
    {
        return Color::orderBy('updated_at', 'desc')->get(['id', 'name']);
    }

    public function getById($id)
    {
        return Color::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description']);
    }

    public function store($data)
    {
        return Color::create($data);
    }

    public function edit($id, $data)
    {
        return Color::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Color::destroy($id);
    }
}
