<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Products;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductInterface
{

    public function getAll()
    {
        return Products::with('categories')
            ->with('colors')
            ->with('images')
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getAllFo()
    {
        // return DB::table('products as p')
        //     ->join('product_images as pi', 'pi.product_id', '=', 'p.id')
        //     ->select('p.name', 'p.price', 'p.weight', 'p.b_layanan', DB::raw('MIN(pi.name) as image_path'))
        //     ->groupBy('p.name', 'p.price', 'p.weight', 'p.b_layanan')
        //     ->get();
        return Products::with('categories', 'images', 'sku')
            ->select('id', 'name', 'slug', 'price', 'sku_id', 'stock', 'weight', 'b_layanan', 'description', DB::raw('MIN(name) as image_path'))
            ->groupBy('id', 'name', 'slug', 'price', 'weight', 'b_layanan', 'description')
            // ->orderBy('created_at', 'DESC')
            ->orderBy('stock', 'DESC')
            ->paginate(15);
    }

    public function getSerachProducts($name)
    {
        return Products::with('categories', 'images', 'sku')
            ->select('id', 'name', 'slug', 'price', 'sku_id', 'stock', 'weight', 'b_layanan', 'description', DB::raw('MIN(name) as image_path'))
            ->groupBy('id', 'name', 'slug', 'price', 'weight', 'b_layanan', 'description')
            ->where('name', 'LIKE', '%' . $name . '%')
            ->orderBy('stock', 'DESC')
            ->paginate(15);
    }

    public function get_by_color($name, $color_id)
    {
        return Products::with('categories', 'images')
            ->select('id', 'name', 'price', 'weight', 'b_layanan', 'description', DB::raw('MIN(name) as image_path'))
            ->groupBy('id', 'name', 'price', 'weight', 'b_layanan', 'description')
            ->where(['name' => $name])
            ->where(['color' => $color_id])
            ->orderBy('created_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getBeranda()
    {
        return Products::with('categories')
            ->with('images')
            ->orderBy('stock', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->limit(3)->get();
    }

    public function GetTotal()
    {
        return Products::count();
    }

    public function getAllCatgoryFo($idCategory)
    {
        return Products::with('categories', 'images', 'subCategories', 'sku')
            ->select('id', 'name', 'price', 'weight', 'stock', 'subcategory_id', 'sku_id', 'category_id', 'b_layanan', 'description', 'slug', DB::raw('MIN(name) as image_path'))
            ->groupBy('id', 'name', 'price', 'weight', 'category_id', 'subcategory_id', 'b_layanan', 'description', 'slug')
            ->where('category_id', $idCategory)
            ->orderBy('stock', 'DESC')
            ->paginate(9);
    }

    public function getSearch($s)
    {
        return Products::where('name', 'like', '%' . $s . '%')->get();
    }

    public function getById($id)
    {
        return Products::with('categories')
            ->with('images', 'colors')
            ->where('id', $id)
            ->firstOrFail();
    }

    public function store($data)
    {
        return Products::create($data);
    }

    public function edit($id, $data)
    {
        return Products::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Products::destroy($id);
    }
    public function sluggable($name)
    {
        return SlugService::createSlug(Products::class, 'slug', $name);
    }

    public function getBySlugFo($name)
    {

        // return Products::with(['categories', 'subCategories', 'images', 'colorFo'])
        //     ->where('name', 'like', '%' . decrypt($name) . '%')
        //     ->orderBy('name', 'ASC')
        //     ->firstOrFail();

        $product_name = Products::with(['categories', 'subCategories', 'images', 'colors'])
            ->where('slug',  $name)
            ->orderBy('name', 'ASC')
            ->firstOrFail();
        // Color
        $all_product_color = Products::with(['colorFo'])
            ->where('name', 'like', '%' . $name . '%')
            ->orderBy('name', 'ASC')
            ->select('color')
            ->get();
        $product_name['cdt'] = $all_product_color;

        return $product_name;
    }

    public function getRelatedProductFo($idCategory, $slug)
    {
        return Products::with('categories')
            ->with('images', 'sku')
            ->where('category_id', $idCategory)
            ->where('slug', '!=', $slug)
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }
}
