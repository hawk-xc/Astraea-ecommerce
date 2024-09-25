<?php

namespace App\Interfaces;

interface HampersProductInterface
{
    public function getAll();
    public function getAllFo();
    public function getAllCatgoryFo($idCategory);
    public function getSearch($s);
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function sluggable($name);
    public function getBySlugFo($slug);
    public function getRelatedProductFo($idCategory, $slug);
}
