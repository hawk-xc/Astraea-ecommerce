<?php

namespace App\Repositories;

use App\Interfaces\PaymentDetailInterface;
use App\Models\PaymentDetail;

class PaymentDetailRepository implements PaymentDetailInterface
{
    public function getAll()
    {
        return PaymentDetail::where('category_PaymentDetail', 'EVN')->orderBy('updated_at', 'desc')->get(['id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'is_active']);
    }

    public function getById($id)
    {
        return PaymentDetail::where('id', $id)
                ->orderBy('updated_at', 'desc')
                ->with('dHampers')
                ->with('dProducts')
                ->with('dCustomer')->first();
    }

    public function store($data)
    {
        return PaymentDetail::create($data);
    }

    public function edit($id, $data)
    {
        return PaymentDetail::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return PaymentDetail::destroy($id);
    }

    public function getDiscNewCostumer()
    {
        return PaymentDetail::where('category_PaymentDetail', 'NEW')->first();
    }
}
