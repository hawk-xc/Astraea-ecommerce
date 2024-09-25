<?php

namespace App\Interfaces;

interface SubCategoriesInterface
{
    public function getAll();
    public function getCate($categoryId);
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
