<?php

namespace App\Repositories;

use App\Interfaces\SkuInterface;
use App\Models\Sku;

class SkuRepository implements SkuInterface
{
    private $dataSource;

    public function __construct(Sku $dataSource) {
        $this->dataSource = $dataSource;
    }
    public function getAll()
    {
        return $this->dataSource->orderBy('updated_at', 'desc')->get(['id', 'code', 'name']);
    }

    public function getAllFo()
    {
        return $this->dataSource->orderBy('updated_at', 'desc')->get(['id', 'name']);
    }

    public function getById($id)
    {
        return $this->dataSource->where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'code', 'name']);
    }

    public function store($data)
    {
        return $this->dataSource->create($data);
    }

    public function edit($id, $data)
    {
        return $this->dataSource->whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return $this->dataSource->destroy($id);
    }
}
