<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Models\Pesanan;
use Helper;

class OrderRepository implements OrderInterface
{
    public function getAll()
    {
        return Pesanan::orderBy('updated_at', 'asc')
                ->with('customerData')
                ->get();
    }

    public function getAllnotPending()
    {
        return Pesanan::orderBy('updated_at', 'asc')
                ->where('status', '<>', 'PENDING')
                ->with('customerData')
                ->get();
    }

    public function getById($id)
    {
        return Pesanan::where('id', $id)->first();
    }

    public function getByIdH($id)
    {
        return Pesanan::where('id', $id)
            ->with('shippingData.districtData')
            ->with('paymentData')
            ->first();
    }

    public function getByIdDtlOrder($id)
    {
        return Pesanan::where('id', $id)
                ->with('customerData')
                ->with('shippingData.districtData')
                ->with('paymentData')
                ->first();
    }

    public function getBylogin($id)
    {
        return Pesanan::where('costumer_id', $id)->where('status', 'pending')->first();
    }

    public function getSearch($s)
    {
        return Pesanan::where('name', 'like', '%'.$s.'%')->orWhere('username', 'like', '%'.$s.'%')->get(['id', 'name', 'username']);
    }

    public function store($data)
    {
        return Pesanan::create($data);
    }

    public function edit($id, $data)
    {
        return Pesanan::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Pesanan::destroy($id);
    }

    public function searchId($id)
    {
       return Pesanan::where('id', $id)->first();
    }

    public function cartCheckLogin()
    {
        if(isset(Auth()->guard('customer')->user()->id))
        {
            $cart = Pesanan::where('costumer_id', Auth()->guard('customer')->user()->id)
                    ->where('status', 'pending')->first();
            if(!isset($cart))
            {
                $cart = 'CRP-' . Helper::table_id();
            }
            else
            {
                $cart = $cart['id'];
            }      
        }
        else
        {
            $cart = session('cart_product');
        }

        if(!isset($cart))
        {
            $cart = 'CRP-' . Helper::table_id();
            session(['cart_product' => $cart]);
        }

        return $cart;
    }

    public function getByNota($nota)
    {
        return Pesanan::where('no_nota', $nota)->firstOrFail();
    }

    public function editByNota($nota, $data)
    {
        return Pesanan::where('no_nota', $nota)->update($data);
    }
    
}
