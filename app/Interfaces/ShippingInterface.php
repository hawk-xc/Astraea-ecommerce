<?php

namespace App\Interfaces;

interface ShippingInterface
{
    public function getById($idorder);
    public function store($data);
    public function edit($id, $data);
    public function destroy($idorder);
}
