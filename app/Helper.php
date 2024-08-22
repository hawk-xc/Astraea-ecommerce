<?php

use Carbon\Carbon;

class Helper
{
    static function table_id()
    {
        {
        $date = Carbon::now();
        // return $date->getPreciseTimestamp(2);
        return $date->format('YmdHisu');
        // return Str::uuid();
    }
    }

    static function response_builder($status_code, $status_string, $data)
    {
        $message = array(
            "status" => $status_code,
            "message" => $status_string,
            "data" => $data
        );
        return json_encode($message);
    }

    static function to_rupiah($args, $with_currency = true)
    {
        if ($with_currency) {
            return 'Rp. ' . number_format($args, 0, ",", ".");
        } else {
            return number_format($args, 0, ",", ".");
        }

    }

    static $role_id = [
        'RL-167267347114',
        'RL-167267347119',
        'RL-167267347125'
    ];

    static $role_name = [
        'admin',
        'operator',
        'editor'
    ];
}
