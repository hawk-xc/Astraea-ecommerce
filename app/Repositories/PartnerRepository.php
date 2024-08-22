<?php

namespace App\Repositories;

use App\Interfaces\PartnerInterface;
use App\Models\Partner;

class PartnerRepository implements PartnerInterface
{
    public function getAll()
    {
        return Partner::orderBy('updated_at', 'desc')->get(['id', 'name', 'description']);
    }

    public function getTotal()
    {
        return Partner::count();
    }

    public function getById($id)
    {
        return Partner::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description', 'image']);
    }

    public function store($data)
    {
        return Partner::create($data);
    }

    public function edit($id, $data)
    {
        return Partner::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Partner::destroy($id);
    }
    public function getImage()
    {
        return Partner::orderBy('updated_at', 'desc')->get(['id', 'image']);
    }
}
