<?php

namespace App\Interfaces;

interface AboutUsInterface
{
    public function getById($id);
    public function edit($id, $data);
}
