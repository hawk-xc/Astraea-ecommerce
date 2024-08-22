<?php

namespace App\Http\Controllers\Fo\customer;

use App\Http\Controllers\Controller;
use App\Repositories\UlasanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class UlasanController extends Controller
{
    private UlasanRepository $ulasanRepository;

    public function __construct(UlasanRepository $ulasanRepository)
    {
        $this->ulasanRepository = $ulasanRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('iki ndene');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);

        $record = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|max:500',
        ]);

        $record['product_id'] = $id;
        $record['customer_id'] = auth()->guard('customer')->user()['id'];

        try {
            $data = $this->ulasanRepository->getById($record['product_id'], $record['customer_id']);
            // Menyimpan data ke database
            if($data == null)
            {
                $this->ulasanRepository->store($record);
                return redirect()->back()->with('toast_success', 'Ulasan berhasil disimpan!');
            }
            else
            {
                $this->ulasanRepository->edit($record['product_id'], $record['customer_id'], $record);
                return redirect()->back()->with('toast_success', 'Ulasan berhasil disimpan!');
            }
        } catch (\Exception $e) {
            // Menyimpan error ke log file
            Log::error('Gagal menyimpan testimoni: ' . $e->getMessage());

            return redirect()->back()->with('toast_warning', 'Terjadi kesalahan saat menyimpan testimoni. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
