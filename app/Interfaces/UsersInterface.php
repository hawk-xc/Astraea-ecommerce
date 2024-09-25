<?php

namespace App\Interfaces;

interface UsersInterface
{
    public function getAll();
    public function getSearch($s);
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
