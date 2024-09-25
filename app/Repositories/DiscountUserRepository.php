<?php

namespace App\Repositories;

use App\Interfaces\DiscountUserInterface;
use App\Models\DiscountUser;

class DiscountUserRepository implements DiscountUserInterface
{
    public function getAll()
    {
        return DiscountUser::orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return DiscountUser::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function store($data)
    {
        return DiscountUser::create($data);
    }

    public function edit($id, $data)
    {
        return DiscountUser::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return DiscountUser::where('discount_id', $id)->delete();
    }
}
