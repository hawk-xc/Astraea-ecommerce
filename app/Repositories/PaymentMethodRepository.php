<?php

namespace App\Repositories;

use App\Interfaces\PaymentMethodInterface;
use App\Models\PaymentMethod;

class PaymentMethodRepository implements PaymentMethodInterface
{
    public function getAll()
    {
        return PaymentMethod::orderBy('updated_at', 'desc')->get(['id','holder_name', 'name_bank', 'rekening_number', 'is_active']);
    }

    public function getById($id)
    {
        return PaymentMethod::where('id', $id)->orderBy('updated_at', 'desc')->first(['id','holder_name', 'name_bank', 'rekening_number', 'is_active']);
    }

    public function store($data)
    {
        return PaymentMethod::create($data);
    }

    public function edit($id, $data)
    {
        return PaymentMethod::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return PaymentMethod::destroy($id);
    }
}
