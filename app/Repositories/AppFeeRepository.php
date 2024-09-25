<?php

namespace App\Repositories;

use App\Interfaces\AppFeeInterface;
use App\Models\AppFee;

class AppFeeRepository implements AppFeeInterface
{
    
    public function getById($id)
    {
        return AppFee::where('id', $id)->firstOrFail();
    }

    public function edit($id, $data)
    {
        return AppFee::whereId($id)->update($data);
    }
}
