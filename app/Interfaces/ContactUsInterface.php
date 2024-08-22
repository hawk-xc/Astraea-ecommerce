<?php

namespace App\Interfaces;

interface ContactUsInterface
{
    public function getById($id);
    public function edit($id, $data);
}
