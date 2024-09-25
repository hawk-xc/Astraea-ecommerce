<?php

namespace App\Interfaces;

interface UlasanInterface
{
    public function getAll();
    public function getAllFo($id);
    public function getAvgRat($id);
    public function getTotal();
    public function getById($id, $idcus);
    public function store($data);
    public function edit($id, $idcus, $data);
    public function destroy($id);
}
