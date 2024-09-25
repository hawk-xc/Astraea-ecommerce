<?php

namespace App\Repositories;

use App\Interfaces\OrderDetailInterface;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\Auth;

class OrderDetailRepository implements OrderDetailInterface
{
    public function getDetail($idOrder)
    {
        return PesananDetail::where('order_id', $idOrder)
                ->orderBy('updated_at', 'asc')
                ->with('productData.colorAtr','color')
                ->get();
    }

    public function getAllnotPending()
    {
        return Pesanan::orderBy('updated_at', 'asc')
                ->where('status', '<>', 'PENDING')
                ->with('customerData')
                ->get();
    }

    public function getSumDetail($idOrder)
    {
        return PesananDetail::where('order_id', $idOrder)
                ->sum('sub_total_price');
    }

    public function store($data)
    {
        return PesananDetail::create($data);
    }

    public function getByIdProduct($id, $color, $order)
    {
        return PesananDetail::where('product_id', $id)->where('color', $color)->where('order_id', $order)
                ->first();
    }

    public function getCekBuy($id)
    {
        if(Auth::guard('customer')->check())
        {
            $customerId = auth()->guard('customer')->user()->id;
            return PesananDetail::where('product_id', $id)
                ->whereHas('orderData', function($query) use ($customerId) {
                    $query->where('status', 'PAID')->where('costumer_id', $customerId);
                })->count();
        }
        return 0;
    }

    public function getById($id)
    {
        return PesananDetail::where('id', $id)
                ->first();
    }

    public function updateDetailCart($id, $data)
    {
        return PesananDetail::whereId($id)->update($data);
    }

    public function subTotalOrder($idOrder)
    {
        return PesananDetail::where('order_id', $idOrder)->sum('sub_total_price');
    }

    public function orderList($idOrder)
    {
        return PesananDetail::where('order_id', $idOrder)
                ->with(['productData','color', 'productData.images'])
                ->get()->toArray();
    }
    
    public function destroy($id)
    {
        return PesananDetail::destroy($id);
    }

    public function sumWeight($idOrder)
    {
        return PesananDetail::with('productData')->where('order_id', $idOrder)->get();
    }

    public function orderDtll($idDtl)
    {
        return PesananDetail::where('id', $idDtl)
                ->with(['productData'])
                ->first()->toArray();
    }
}
