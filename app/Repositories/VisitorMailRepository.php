<?php

namespace App\Repositories;

use App\Interfaces\VisitorMailInterface;
use App\Models\VisitorMail;

class VisitorMailRepository implements VisitorMailInterface
{
    public function getAll()
    {
        return VisitorMail::orderBy('updated_at', 'desc')->get();
    }

    public function getAllFo()
    {
        return VisitorMail::orderBy('updated_at', 'desc')->get(['id', 'name']);
    }

    public function getById($id)
    {
        return VisitorMail::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description']);
    }

    public function store($data)
    {
        return VisitorMail::create($data);
    }

    public function edit($id, $data)
    {
        return VisitorMail::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return VisitorMail::destroy($id);
    }
}
