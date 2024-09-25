<?php

namespace App\Repositories;

use App\Interfaces\DiscountHampersInterface;
use App\Models\DiscountHampers;

class DiscountHampersRepository implements DiscountHampersInterface
{
    public function getAll()
    {
        return DiscountHampers::orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return DiscountHampers::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function store($data)
    {
        return DiscountHampers::create($data);
    }

    public function edit($id, $data)
    {
        return DiscountHampers::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return DiscountHampers::where('discount_id', $id)->delete();
    }
}
