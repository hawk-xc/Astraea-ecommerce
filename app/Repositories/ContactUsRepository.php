<?php

namespace App\Repositories;

use App\Interfaces\ContactUsInterface;
use App\Models\ContactUs;

class ContactUsRepository implements ContactUsInterface
{

    public function getById($id)
    {
        return ContactUs::where('id', $id)->with('districtData')->orderBy('updated_at', 'desc')->first();
    }

    public function edit($id, $data)
    {
        return ContactUs::whereId($id)->update($data);
    }

}
