<?php

namespace App\Interfaces;

interface OrderDetailInterface
{
    public function getDetail($idOrder);
    public function getSumDetail($idOrder);
    public function store($data);
    public function getByIdProduct($id, $color, $order);
    public function getCekBuy($id);
    public function getById($id);
    public function updateDetailCart($id, $data);
    public function subTotalOrder($idOrder);
    public function orderList($idOrder);
    public function destroy($id);
    public function sumWeight($idOrder);
    public function orderDtll($idDtl);
}