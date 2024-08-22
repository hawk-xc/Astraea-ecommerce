<?php

namespace App\Repositories;

use App\Interfaces\UserOrderInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class UserOrderRepository implements UserOrderInterface
{
    public function getAll($s)
    {
        $customerId = $s;
        $pesanans = DB::table('pesanans')
            ->select(
                'id',
                'costumer_id', 
                DB::raw('DATE_FORMAT(order_date, "%d %M %Y") as order_date'), 
                'no_nota',
                'shipping_status', 
                'status', 
                DB::raw('"Product" as jenis'), 
                'created_at'
            )
            ->where('costumer_id', $customerId);

        // Query untuk tabel pesanan_hampers dengan field custom 'jenis' yang berisi 'hampers'
        $pesananHampers = DB::table('pesanan_hampers')
            ->select(
                'id',
                'costumer_id', 
                DB::raw('DATE_FORMAT(order_date, "%d %M %Y") as order_date'), 
                'no_nota',
                'shipping_status',
                'status', 
                DB::raw('"Hampers" as jenis'), 
                'created_at'
            )
            ->where('costumer_id', $customerId);

        return $pesanans->union($pesananHampers)->orderBy('created_at', 'desc')
                ->paginate(6);
    }

    public function getById($id)
    {
        return DB::table('districts')
            ->where('id', $id)
            ->select('name', 'type', 'province')
            ->first();
    }
}
