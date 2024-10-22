<?php

namespace App\Repositories;

use App\Interfaces\HampersImageProductInterface;
use App\Models\HampersProductImages;

class HampersImageProductRepository implements HampersImageProductInterface
{
    public function getAll()
    {
        return HampersProductImages::with('categories')->with('images')->orderBy('name', 'ASC')->get();
    }

    public function getByPosition($productId, $position)
    {
        return HampersProductImages::where('product_id', $productId)
            ->where('position', $position)
            ->first();
    }

    public function getById($id)
    {
        return HampersProductImages::with('categories')->with('images')->where('id', $id)->firstOrFail(['id', 'name', 'description']);
    }

    public function getByIdProduct($id)
    {
        return HampersProductImages::where('product_id', $id)->get()->groupBy('position');
    }

    public function getByIdProducts($id)
    {
        return HampersProductImages::where('product_id', $id)->get();
    }

    public function store($data)
    {
        return HampersProductImages::create($data);
    }


    public function edit($id, $data)
    {
        return HampersProductImages::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return HampersProductImages::destroy($id);
    }
}
