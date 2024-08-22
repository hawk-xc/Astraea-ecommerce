<?php

namespace App\Interfaces;

interface VisitorMailInterface
{
    public function getAll();
    public function getAllFo();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
