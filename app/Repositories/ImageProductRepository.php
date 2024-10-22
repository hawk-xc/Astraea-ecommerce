<?php

namespace App\Repositories;

use App\Interfaces\ImageProductInterface;
use App\Models\ProductImages;

class ImageProductRepository implements ImageProductInterface
{
    public function getAll()
    {
        return ProductImages::with('categories')->with('images')->orderBy('name', 'ASC')->get();
    }

    public function getByPosition($productId, $position)
    {
        return ProductImages::where('product_id', $productId)
            ->where('position', $position)
            ->first();
    }


    public function getById($id)
    {
        return ProductImages::with('categories')->with('images')->where('id', $id)->firstOrFail(['id', 'name', 'description']);
    }

    public function getByIdProduct($id)
    {
        return ProductImages::where('product_id', $id)->get()->groupBy('position');
    }

    public function getByIdProducts($id)
    {
        return ProductImages::where('product_id', $id)->get();
    }

    public function store($data)
    {
        return ProductImages::create($data);
    }


    public function edit($id, $data)
    {
        return ProductImages::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return ProductImages::destroy($id);
    }
}
