<?php

namespace App\Interfaces;

interface ProfilesInterface
{
    public function getById($id);
    public function edit($id, $data);
}
