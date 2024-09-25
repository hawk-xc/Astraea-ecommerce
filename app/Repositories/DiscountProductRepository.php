<?php

namespace App\Repositories;

use App\Interfaces\DiscountProductInterface;
use App\Models\DiscountProduct;

class DiscountProductRepository implements DiscountProductInterface
{
    public function getAll()
    {
        return DiscountProduct::orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return DiscountProduct::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function store($data)
    {
        return DiscountProduct::create($data);
    }

    public function edit($id, $data)
    {
        return DiscountProduct::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return DiscountProduct::where('discount_id', $id)->delete();
    }
}
