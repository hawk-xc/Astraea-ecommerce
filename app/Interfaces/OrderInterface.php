<?php

namespace App\Interfaces;

interface OrderInterface
{
    public function getAll();
    public function getAllnotPending();
    public function getById($id);
    public function getByIdH($id);
    public function getByIdDtlOrder($id);
    public function getSearch($s);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
    public function searchId($id);
    public function cartCheckLogin();
    public function getByNota($nota);
    public function editByNota($nota, $data);
    // public function sumWeight($idOrder);
}
