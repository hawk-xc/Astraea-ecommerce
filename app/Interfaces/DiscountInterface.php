<?php

namespace App\Interfaces;

interface DiscountInterface
{
    public function getAll();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function getDiscNewCostumer();
}
