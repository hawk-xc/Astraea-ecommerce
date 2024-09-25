<?php

namespace App\Repositories;

use App\Interfaces\AboutUsInterface;
use App\Models\AboutUs;

class AboutUsRepository implements AboutUsInterface
{

    public function getById($id)
    {
        return AboutUs::where('id', $id)->orderBy('updated_at', 'desc')->first();
    }

    public function edit($id, $data)
    {
        return AboutUs::whereId($id)->update($data);
    }

}
