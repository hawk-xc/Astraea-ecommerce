<?php

namespace App\Http\Controllers\Fo\customer;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\ShippingRepository;

use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;

use App\Repositories\OrderHampersRepository;
use App\Repositories\OrderHampersDetailRepository;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class OrderHistoryController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private OrderRepository $orderProductRepository;
    private OrderDetailRepository $detailorderProductRepository;
    private OrderHampersRepository $orderHampersRepository;
    private OrderHampersDetailRepository $orderHampersDetailRepository;

    private ShippingRepository $shippingRepository;

    private CustomerRepository $repository;
    protected $data = array();

    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, ShippingRepository $shippingRepository, OrderRepository $orderProductRepository, OrderDetailRepository $detailorderProductRepository, OrderHampersRepository $orderHampersRepository, OrderHampersDetailRepository $orderHampersDetailRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->shippingRepository = $shippingRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->detailorderProductRepository = $detailorderProductRepository;
        $this->orderHampersRepository = $orderHampersRepository;
        $this->orderHampersDetailRepository = $orderHampersDetailRepository;
        $this->data['title'] = 'Order';
        $this->data['view_directory'] = "guest.feature.historyCart";
    }

    public function product($id)
    {
        $cart = decrypt($id);
        $ref = $this->data;
        $ref['title'] = $ref['title'].' Product';
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['order'] = $this->orderProductRepository->getByIdH($cart);
        $data['orders'] = $this->detailorderProductRepository->orderList($cart);

        $data['order']['no_nota'] = Crypt::encryptString($data['order']['no_nota']);
        $data['order']['id'] = Crypt::encryptString($data['order']['id']);

        return view($this->data['view_directory'] . '.product.index', compact('ref', 'data'));
    }

    public function hampers($id)
    {
        $cart = decrypt($id);
        $ref = $this->data;
        $ref['title'] = $ref['title'].' Hampers';
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['order'] = $this->orderHampersRepository->getByIdH($cart);
        $data['orders'] = $this->orderHampersDetailRepository->orderList($cart);

        $data['order']['no_nota'] = Crypt::encryptString($data['order']['no_nota']);
        $data['order']['id'] = Crypt::encryptString($data['order']['id']);

        return view($this->data['view_directory'] . '.hampers.index', compact('ref', 'data'));
    }
}