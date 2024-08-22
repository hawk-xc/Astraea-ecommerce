<?php

namespace App\Repositories;

use App\Interfaces\DistrictInterface;
use Illuminate\Support\Facades\DB;

class DistrictRepository implements DistrictInterface
{
    public function getAll($s)
    {
        return DB::table('districts')
                ->where(function($query) use ($s) {
                    $query->where('name', 'like', '%' . $s . '%')
                          ->orWhere('type', 'like', '%' . $s . '%')
                          ->orWhere('province', 'like', '%' . $s . '%');
                })
                ->select('id', 'name', 'type', 'province')
                ->get();
    }

    public function getById($id)
    {
        return DB::table('districts')
            ->where('id', $id)
            ->select('name', 'type', 'province')
            ->first();
    }
}
