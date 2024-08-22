<?php

namespace App\Repositories;

use App\Interfaces\CertificateInterface;
use App\Models\Certificate;

class CertificateRepository implements CertificateInterface
{
    public function getAll()
    {
        return Certificate::orderBy('updated_at', 'desc')->get(['id', 'name', 'description']);
    }

    public function getTotal()
    {
        return Certificate::count();
    }

    public function getById($id)
    {
        return Certificate::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description', 'image']);
    }

    public function store($data)
    {
        return Certificate::create($data);
    }

    public function edit($id, $data)
    {
        return Certificate::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Certificate::destroy($id);
    }
    public function getImage()
    {
        return Certificate::orderBy('updated_at', 'desc')->get(['id', 'image']);
    }
}
