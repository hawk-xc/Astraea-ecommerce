<?php

namespace App\Repositories;

use App\Interfaces\CustomerInterface;
use App\Models\Customer;

class CustomerRepository implements CustomerInterface
{
    public function getAll()
    {
        return Customer::orderBy('updated_at', 'desc')->get(['id','name', 'username', 'email', 'is_active', 'tgl_lahir','jenis_kelamin']);
    }

    public function getById($id)
    {
        return Customer::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function getSearch($s)
    {
        return Customer::where('name', 'like', '%'.$s.'%')->orWhere('username', 'like', '%'.$s.'%')->get(['id', 'name', 'username']);
    }

    public function getChecklogin($s)
    {
        return Customer::where('email', $s)
            ->Where('is_active', 1)
            ->Where('verification_token', 'verify')
            ->whereNotNull('email_verified_at')
            ->first();
    }

    public function store($data)
    {
        return Customer::create($data);
    }

    public function edit($id, $data)
    {
        return Customer::whereId($id)->update($data);
    }

    public function token_reset_pass($email, $token)
    {
        return Customer::where('email', $email)->update(['reset_password_token' => $token]);
    }

    public function search_reset_pass($token)
    {
        return Customer::where('reset_password_token', $token)->firstOrFail();
    }

    public function verifyMail($token)
    {
        if($token == 'verify')
        {
            return abort(404);
        }
        return Customer::where('verification_token', $token)
        ->firstOrFail()
        ->update([
            'email_verified_at' => now(),
            'verification_token' => 'verify'
        ]);
    }


    public function destroy($id)
    {
        return Customer::destroy($id);
    }
}
