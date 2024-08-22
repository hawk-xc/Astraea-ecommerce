<?php

namespace App\Http\Controllers\Fo\customer;

use App\Http\Controllers\Controller;
use App\Repositories\TestimoniRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class TestimoniController extends Controller
{
    private TestimoniRepository $testimoniRepository;

    public function __construct(TestimoniRepository $testimoniRepository)
    {
        $this->testimoniRepository = $testimoniRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $record = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'testimonial' => 'required|string|max:500',
        ]);

        $record['is_delete'] = '0';
        $record['customer_id'] = auth()->guard('customer')->user()['id'];

        try {
            // Menyimpan data ke database
            $this->testimoniRepository->store($record);
            return redirect()->back()->with('toast_success', 'Testimoni berhasil disimpan!');
        } catch (\Exception $e) {
            // Menyimpan error ke log file
            Log::error('Gagal menyimpan testimoni: ' . $e->getMessage());

            return redirect()->back()->with('toast_warning', 'Terjadi kesalahan saat menyimpan testimoni. Silakan coba lagi.');
        }
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
            'testimonial' => 'required|string|max:500',
        ]);

        try {
            // Menyimpan data ke database
            $this->testimoniRepository->edit($id, $record);
            return redirect()->back()->with('toast_success', 'Testimoni berhasil disimpan!');
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
