<?php

namespace App\Http\Controllers\Fo\discount;

use App\Repositories\OrderHampersRepository;
use App\Repositories\DiscountCostumerRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class DiscountEventApplyHController extends Controller
{
    private OrderHampersRepository $orderRepository;
    private DiscountCostumerRepository $d_customer_repository;
    protected $data = array();

    public function __construct(OrderHampersRepository $orderRepository, DiscountCostumerRepository $d_customer_repository)
    {
        $this->d_customer_repository = $d_customer_repository;
        $this->orderRepository = $orderRepository;
        $this->data['title'] = 'Discount Apply';
        $this->data['view_directory'] = "";
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
        try 
        {
            $id_order = Crypt::decryptString($id);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $id_order = null;
        }

        $reccord = $request->validate([
            "coupon" => ['required', 'string', 'max:20'],
        ], [], [
            "coupon" => "Code coupon",
        ]);

        $reccord['coupon'] = strtoupper($reccord['coupon']);
        $reccord['id_cutomer'] = Auth()->guard('customer')->user()->id;

        $coupon = $this->d_customer_repository->getByCodePromo($reccord['id_cutomer'], $reccord['coupon']);

        $startDateTime = isset($coupon['discountData']['start_time']) ? Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['start_date'] . ' ' . $coupon['discountData']['start_time']) : '';
        $endDateTime = isset($coupon['discountData']['end_time']) ?  Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['end_date'] . ' ' . $coupon['discountData']['end_time']) : '';
        $currentDateTime = Carbon::now();

        try {
            if((isset($coupon['discountData']['is_active'])) && ($coupon['discountData']['is_active'] == '1'))
            {
                if((($startDateTime <= $currentDateTime) && ($endDateTime >= $currentDateTime)) || ($coupon['discountData']['id'] == 'DIS-20240000000000000001'))
                {
                    $this->orderRepository->edit($id_order, [
                        'code_discount' => $reccord['coupon'],
                        'discount_amount' => $coupon['discountData']['discount_amount']
                    ]);
                    
                    return redirect()->back()->with('toast_success', 'Coupon discount diterapkan');
                }
            }

            $this->orderRepository->edit($id_order, [
                'code_discount' => '',
                'discount_amount' => 0
            ]);
            return redirect()->back()->with('toast_warning', 'Coupon discount tidak tersedia');
        } catch (Exception $e) {
            // Log error jika terjadi
            Log::error($e->getMessage());

            // Redirect atau response gagal
            return redirect()->back()->with('toast_warning', 'Coupon discount tidak tersedia');
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
