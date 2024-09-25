<?php

namespace App\Repositories;

use App\Interfaces\UlasanInterface;
use App\Models\Ulasan;

class UlasanRepository implements UlasanInterface
{
    public function getAll()
    {
        return Ulasan::orderBy('updated_at', 'desc')->get();
    }

    public function getAllFo($id)
    {
        return Ulasan::orderBy('updated_at', 'desc')->where('product_id', $id)->with('customerData')->paginate(20);
    }
    public function getAvgRat($id)
    {
        return Ulasan::where('product_id', $id)->get()->avg('rating');
    }

    public function getTotal()
    {
        return Ulasan::count();
    }

    public function getById($id, $idcus)
    {
        return Ulasan::where('product_id', $id)->where('customer_id', $idcus)->first();
    }

    public function store($data)
    {
        return Ulasan::create($data);
    }

    public function edit($id, $idcus, $data)
    {
        return Ulasan::where('product_id', $id)->where('customer_id', $idcus)->update($data);
    }

    public function destroy($id)
    {
        return Ulasan::destroy($id);
    }
}
