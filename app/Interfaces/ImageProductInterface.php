<?php

namespace App\Interfaces;

interface ImageProductInterface
{
    public function getAll();
    public function getById($id);
    public function getByIdProduct($id);
    public function getByIdProducts($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
