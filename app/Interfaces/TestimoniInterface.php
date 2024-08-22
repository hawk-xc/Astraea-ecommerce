<?php

namespace App\Interfaces;

interface TestimoniInterface
{
    public function getAll();
    public function getAllFo();
    public function getTotal();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
