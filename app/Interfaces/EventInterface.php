<?php

namespace App\Interfaces;

interface EventInterface
{
    public function getAll();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function sluggable($title);
    public function getAllFo();
    public function getBySlugFo($slug);
}
