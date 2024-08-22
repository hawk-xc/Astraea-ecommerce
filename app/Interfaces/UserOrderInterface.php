<?php

namespace App\Interfaces;

interface UserOrderInterface
{
    public function getAll($S);
    public function getById($id);
}