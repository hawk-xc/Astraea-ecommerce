@extends('guest.layouts.app')

@section('content')
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<h1>{{ $ref['title'] }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="contact-from-section mt-150 mb-150">
        <div class="container">
        @if($data['coupon']['count'] > 0)
            <div class="row">
                <div class="col">
                    <!-- Tambahkan orderan terkini di sini (dummy data) -->
                    <h4>Coupon List</h4>
                    <div class="row">
                    @foreach($data['coupon']['datas'] as $coupon)
                        <div class="col-md-4 my-3">
                            <div class="card">
                                <div class="image-top-coupon">
                                    <img src="{{ asset('storage/' .  $coupon['discountData']['image_banner']) }}" class="coupon-img" alt="">
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            @if($coupon['discountData']['id'] !='DIS-20240000000000000001')
                                            <p class="mb-0 coupon-header">Valid Until <i class="bi bi-calendar3"></i> {{ $coupon['discountData']['end_date'] }} <i class="bi bi-clock"></i> {{ $coupon['discountData']['end_time'] }}</p>
                                            @else
                                            <p class="mb-0 coupon-header">Diskon Pelanggan Baru</p>
                                            @endif
                                            <div class="badge bg-success text-white p-2">
                                                {{ $coupon['discountData']['discount_amount'] }}<i class="bi bi-percent"></i> OFF
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Button trigger modal -->
                                    <div class="row">
                                        <div class="col d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary m-4" data-bs-toggle="modal" data-bs-target="#modal_{{$loop->index}}">
                                                Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="modal_{{$loop->index}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title fs-5" id="exampleModalLabel">{{ $coupon['discountData']['title'] }}</h5>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">x</button>
                              </div>
                              <div class="modal-body">
                                <div class="image-modal-top">
                                    <img src="{{ asset('storage/' .  $coupon['discountData']['image_banner']) }}" class="coupon-img-modal" alt="">
                                    <div class="container my-3">
                                        @if($coupon['discountData']['id'] !='DIS-20240000000000000001')
                                        <p><strong>Valid Until: </strong>{{ $coupon['discountData']['end_date'] }} {{ $coupon['discountData']['end_time'] }}</p>
                                        @endif
                                        <p><strong>Discount Amount: </strong>{{ $coupon['discountData']['discount_amount'] }}%</p>
                                        <p><strong>Discount Code: </strong>{{ $coupon['discountData']['code_discount'] }}</p>
                                        <p><strong>Description: </strong>{{ $coupon['discountData']['description_discount'] }}</p>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    @endforeach
                    </div>

                    <div class="row">
                        <div class="col text-center">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    {{ $data['coupon']['datas']->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        @endif
        </div>
    </div>
@endsection
	