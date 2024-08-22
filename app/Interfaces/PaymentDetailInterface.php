<?php

namespace App\Interfaces;

interface PaymentDetailInterface
{
    public function getAll();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function getDiscNewCostumer();
}
