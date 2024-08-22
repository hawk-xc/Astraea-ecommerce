<?php

namespace App\Repositories;

use App\Interfaces\DiscountInterface;
use App\Models\Discount;

class DiscountRepository implements DiscountInterface
{
    public function getAll()
    {
        return Discount::where('category_discount', 'EVN')->orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return Discount::where('id', $id)
                ->orderBy('updated_at', 'desc')
                ->with('dHampers')
                ->with('dProducts')
                ->with('dCustomer')->first();
    }

    public function store($data)
    {
        return Discount::create($data);
    }

    public function edit($id, $data)
    {
        return Discount::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Discount::destroy($id);
    }

    public function getDiscNewCostumer()
    {
        return Discount::where('category_discount', 'NEW')->first();
    }
}
