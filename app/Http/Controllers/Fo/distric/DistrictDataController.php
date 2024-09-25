<?php

namespace App\Http\Controllers\Fo\distric;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\DistrictRepository;

class DistrictDataController extends Controller
{
    private DistrictRepository $repository;
    protected $data = array();

    public function __construct(DistrictRepository $repository)
    {
        $this->repository = $repository;
    }

    public function datas(Request $request)
    {
        $s = $request->input('q');
        $filteredProducts = $this->repository->getAll($s);
        $formattedData = $filteredProducts->map(function($distric) {
            return [
                'id' => $distric->id,
                'text' => $distric->type .' '. $distric->name .' - '.$distric->province
            ];
        });
        return response()->json(['results' => $formattedData]);

    }
}
