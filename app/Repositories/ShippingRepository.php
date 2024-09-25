<?php

namespace App\Repositories;

use App\Interfaces\ShippingInterface;
use App\Models\DetailShipingOrder;

class ShippingRepository implements ShippingInterface
{
    public function getById($idorder)
    {
        return DetailShipingOrder::where('id_order', $idorder)->with('districtData')->first();
    }

    public function store($data)
    {
        return DetailShipingOrder::create($data);
    }

    public function edit($id, $data)
    {
        return DetailShipingOrder::where('id_order', $id)->update($data);
    }
    public function destroy($idorder)
    {
        return DetailShipingOrder::where('id_order', $idorder)->delete();
    }
}
