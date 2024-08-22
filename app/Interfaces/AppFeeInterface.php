<?php

namespace App\Interfaces;

interface AppFeeInterface
{
    public function getById($id);
    public function edit($id, $data);
}
