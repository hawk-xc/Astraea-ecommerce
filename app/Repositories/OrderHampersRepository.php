<?php

namespace App\Repositories;

use App\Interfaces\OrderHampersInterface;
use App\Models\PesananHampers;
use Helper;

class OrderHampersRepository implements OrderHampersInterface
{
    public function getAll()
    {
        return PesananHampers::orderBy('updated_at', 'asc')
                ->with('customerData')
                ->get();
    }

    public function getAllnotPending()
    {
        return PesananHampers::orderBy('updated_at', 'asc')
                ->where('status', '<>', 'PENDING')
                ->with('customerData')
                ->get();
    }

    public function getById($id)
    {
        return PesananHampers::where('id', $id)->first();
    }

    public function getByIdH($id)
    {
        return PesananHampers::where('id', $id)
            ->with('shippingData.districtData')
            ->with('paymentData')
            ->first();
    }

    public function getByIdDtlOrder($id)
    {
        return PesananHampers::where('id', $id)
                ->with('customerData')
                ->with('shippingData.districtData')
                ->with('paymentData')
                ->first();
    }

    public function getBylogin($id)
    {
        return PesananHampers::where('costumer_id', $id)->where('status', 'pending')->first();
    }

    public function getSearch($s)
    {
        return PesananHampers::where('name', 'like', '%'.$s.'%')->orWhere('username', 'like', '%'.$s.'%')->get(['id', 'name', 'username']);
    }

    public function store($data)
    {
        return PesananHampers::create($data);
    }

    public function edit($id, $data)
    {
        return PesananHampers::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return PesananHampers::destroy($id);
    }

    public function searchId($id)
    {
       return PesananHampers::where('id', $id)->first();
    }

    public function cartCheckLogin()
    {
        if(isset(Auth()->guard('customer')->user()->id))
        {
            $cart = PesananHampers::where('costumer_id', Auth()->guard('customer')->user()->id)
                    ->where('status', 'pending')->first();
            if(!isset($cart))
            {
                $cart = 'CRH-' . Helper::table_id();
            }
            else
            {
                $cart = $cart['id'];
            }      
        }
        else
        {
            $cart = session('cart_hampers');
        }

        if(!isset($cart))
        {
            $cart = 'CRH-' . Helper::table_id();
            session(['cart_hampers' => $cart]);
        }

        return $cart;
    }

    public function getByNota($nota)
    {
        return PesananHampers::where('no_nota', $nota)->firstOrFail();
    }

    public function editByNota($nota, $data)
    {
        return PesananHampers::where('no_nota', $nota)->update($data);
    }
    
}
