<?php

namespace App\Repositories;

use App\Interfaces\ServiceInterface;
use App\Models\Service;

class ServiceRepository implements ServiceInterface
{
    public function getAll()
    {
        return Service::orderBy('updated_at', 'desc')->get(['id', 'name', 'description']);
    }

    public function getTotal()
    {
        return Service::count();
    }

    public function getById($id)
    {
        return Service::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description', 'image']);
    }

    public function store($data)
    {
        return Service::create($data);
    }

    public function edit($id, $data)
    {
        return Service::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Service::destroy($id);
    }
    public function getAllFo()
    {
        return Service::orderBy('updated_at', 'desc')->get(['id', 'name', 'description', 'image']);
    }
}
