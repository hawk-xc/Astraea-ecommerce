<?php

namespace App\Repositories;

use App\Interfaces\HampersProductInterface;
use App\Models\HampersProduct;
use Cviebrock\EloquentSluggable\Services\SlugService;

class HampersProductRepository implements HampersProductInterface
{
    public function getAll()
    {
        return HampersProduct::with('categories')
                ->with('color')
                ->with('images')
                ->orderBy('name', 'ASC')
                ->get();
    }

    public function getAllFo()
    {
        return HampersProduct::with('categories')
                ->with('images')
                ->orderBy('name', 'ASC')
                ->paginate(15);
    }

    public function getAllCatgoryFo($idCategory)
    {
        return HampersProduct::with('categories')
                ->with('images')
                ->where('category_id', $idCategory)
                ->orderBy('name', 'ASC')
                ->paginate(15);
    }

    public function getSearch($s)
    {
        return HampersProduct::where('name', 'like', '%'.$s.'%')->get();
    }

    public function getById($id)
    {
        return HampersProduct::with('categories')
                ->with('images')
                ->where('id', $id)
                ->firstOrFail();
    }

    public function store($data)
    {
        return HampersProduct::create($data);
    }

    public function edit($id, $data)
    {
        return HampersProduct::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return HampersProduct::destroy($id);
    }
    public function sluggable($name)
    {
        return SlugService::createSlug(HampersProduct::class, 'slug', $name);
    }

    public function getBySlugFo($slug)
    {
        return HampersProduct::with(['categories', 'subCategories', 'images'])
                ->where('slug', $slug)
                ->orderBy('name', 'ASC')
                ->firstOrFail();
    }

    public function getRelatedProductFo($idCategory, $slug)
    {
        return HampersProduct::with('categories')
                ->with('images')
                ->where('category_id', $idCategory)
                ->where('slug', '!=', $slug)
                ->orderBy('name', 'ASC')
                ->limit(3)
                ->get();
    }
}
