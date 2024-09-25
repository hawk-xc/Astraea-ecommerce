<?php

namespace App\Interfaces;

interface CustomerInterface
{
    public function getAll();
    public function getById($id);
    public function getSearch($s);
    public function getChecklogin($s);
    public function store($data);
    public function edit($id, $data);
    public function token_reset_pass($email, $token);
    public function search_reset_pass($token);
    public function verifyMail($token);
    public function destroy($id);
}
