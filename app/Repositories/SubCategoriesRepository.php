<?php

namespace App\Repositories;

use App\Interfaces\SubCategoriesInterface;
use App\Models\SubCategories;

class SubCategoriesRepository implements SubCategoriesInterface
{
    public function getAll()
    {
        return SubCategories::orderBy('updated_at', 'desc')
            ->with('category')
            ->get();
    }

    public function getCate($categoryId)
    {
        return SubCategories::where('id_category', $categoryId)->get();
    }
    
    public function getById($id)
    {
        return SubCategories::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'name', 'description', 'id_category']);
    }

    public function store($data)
    {
        return SubCategories::create($data);
    }

    public function edit($id, $data)
    {
        return SubCategories::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return SubCategories::destroy($id);
    }
}
