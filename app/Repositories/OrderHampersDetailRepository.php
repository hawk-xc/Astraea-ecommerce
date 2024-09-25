<?php

namespace App\Repositories;

use App\Interfaces\OrderHampersDetailInterface;
use App\Models\PesananHampersDetail;
use Illuminate\Support\Facades\Auth;

class OrderHampersDetailRepository implements OrderHampersDetailInterface
{
    public function getDetail($idOrder)
    {
        return PesananHampersDetail::where('order_id', $idOrder)
                ->orderBy('updated_at', 'asc')
                ->with('hampersData.colorAtr')
                ->get();
    }

    public function getSumDetail($idOrder)
    {
        return PesananHampersDetail::where('order_id', $idOrder)
                ->sum('sub_total_price');
    }

    public function store($data)
    {
        return PesananHampersDetail::create($data);
    }

    public function getByIdProduct($id, $order)
    {
        return PesananHampersDetail::where('hampers_id', $id)->where('order_id', $order)
                ->first();
    }

    public function getById($id)
    {
        return PesananHampersDetail::where('id', $id)
                ->first();
    }

    public function getCekBuy($id)
    {
        if(Auth::guard('customer')->check())
        {
            $customerId = auth()->guard('customer')->user()->id;
            return PesananHampersDetail::where('hampers_id', $id)
                    ->whereHas('orderData', function($query)  use ($customerId) {
                        $query->where('status', 'PAID')->where('costumer_id', $customerId);
                    })->count();
         }
        return 0;
    }

    public function updateDetailCart($id, $data)
    {
        return PesananHampersDetail::whereId($id)->update($data);
    }

    public function subTotalOrder($idOrder)
    {
        return PesananHampersDetail::where('order_id', $idOrder)->sum('sub_total_price');
    }

    public function orderList($idOrder)
    {
        return PesananHampersDetail::where('order_id', $idOrder)
                ->with(['hampersData', 'hampersData.images'])
                ->get()->toArray();
    }
    
    public function destroy($id)
    {
        return PesananHampersDetail::destroy($id);
    }

    public function sumWeight($idOrder)
    {
        return PesananHampersDetail::with('hampersData')->where('order_id', $idOrder)->get();
    }

    public function orderDtll($idDtl)
    {
        return PesananHampersDetail::where('id', $idDtl)
                ->with(['hampersData'])
                ->first()->toArray();
    }
}
