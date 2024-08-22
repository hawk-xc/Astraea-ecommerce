<?php

namespace App\Interfaces;

interface DiscountCustomerInterface
{
    public function getAll();
    public function getById($id);
    public function getByCodePromo($id_customer, $code_promo);
    public function getUser($customerId);
    public function store($data);
    public function edit($id, $data);
    public function edit_new($id, $data);
    public function destroy($id);
}
