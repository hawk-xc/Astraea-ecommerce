<?php

namespace App\Repositories;

use App\Interfaces\TestimoniInterface;
use App\Models\Testimoni;

class TestimoniRepository implements TestimoniInterface
{
    public function getAll()
    {
        return Testimoni::where('is_delete', '0')->with('customerData')->orderBy('updated_at', 'desc')->get();
    }

    public function getAllFo()
    {
        return Testimoni::where('is_delete', '0')->orderBy('updated_at', 'desc')->with('customerData')->limit(20)->get();
    }

    public function getTotal()
    {
        return Testimoni::count();
    }

    public function getById($id)
    {
        return Testimoni::where('customer_id', $id)->first();
    }

    public function store($data)
    {
        return Testimoni::create($data);
    }

    public function edit($id, $data)
    {
        return Testimoni::where('customer_id', $id)->update($data);
    }

    public function destroy($id)
    {
        return Testimoni::destroy($id);
    }
}
