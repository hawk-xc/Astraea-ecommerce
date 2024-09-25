<?php

namespace App\Interfaces;

interface PartnerInterface
{
    public function getAll();
    public function getTotal();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function getImage();
}
