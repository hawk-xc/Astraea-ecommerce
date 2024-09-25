<?php

namespace App\Repositories;

use App\Interfaces\DiscountCustomerInterface;
use App\Models\DiscountCostumer;
use Carbon\Carbon;

class DiscountCostumerRepository implements DiscountCustomerInterface
{
    public function getAll()
    {
        return DiscountCostumer::orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return DiscountCostumer::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function getByCodePromo($id_customer, $code_promo)
    {
        return DiscountCostumer::where('costumer_id', $id_customer)
                    ->where('code_discount', $code_promo)
                    ->where('is_used', '0')
                    ->with('discountData')
                    ->orderBy('updated_at', 'desc')
                    ->first();
    }

    public function getUser($customerId)
    {
        $currentDateTime = Carbon::now();
        $data = [
                    'datas' => DiscountCostumer::where('costumer_id', $customerId)
                                    ->where('is_used', '0')
                                    ->whereHas('discountData', function($query) use ($currentDateTime) {
                                        $query->where('is_active', 1)
                                                ->whereRaw("CONCAT(start_date, ' ', start_time) <= ?", [$currentDateTime])
                                                ->whereRaw("CONCAT(end_date, ' ', end_time) >= ?", [$currentDateTime]);
                                    })
                                    ->orWhere(function($query) use ($customerId) {
                                        $query->where('discount_id', 'DIS-20240000000000000001')
                                              ->where('costumer_id', $customerId)
                                              ->where('is_used', '0');
                                    })
                                    ->with('discountData')
                                    ->orderBy('updated_at', 'desc')
                                    ->paginate(15),
                    'count' => DiscountCostumer::where('costumer_id', $customerId)
                                    ->where('is_used', '0')
                                    ->whereHas('discountData', function($query) use ($currentDateTime) {
                                        $query->where('is_active', 1)
                                                ->whereRaw("CONCAT(start_date, ' ', start_time) <= ?", [$currentDateTime])
                                                ->whereRaw("CONCAT(end_date, ' ', end_time) >= ?", [$currentDateTime]);
                                    })
                                    ->orWhere(function($query) use ($customerId) {
                                        $query->where('discount_id', 'DIS-20240000000000000001')
                                              ->where('costumer_id', $customerId)                                       
                                              ->where('is_used', '0');
                                    })
                                    ->with('discountData')
                                    ->orderBy('updated_at', 'desc')
                                    ->count()
                ];
        return $data;
    }

    public function store($data)
    {
        return DiscountCostumer::create($data);
    }

    public function edit($id, $data)
    {
        return DiscountCostumer::whereId($id)->update($data);
    }

    public function edit_new($id, $data)
    {
        return DiscountCostumer::where('discount_id', $id)->update($data);
    }

    public function destroy($id)
    {
        return DiscountCostumer::where('discount_id', $id)->delete();
    }
}
