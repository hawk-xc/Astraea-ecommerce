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
            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="row">
                        <div class="col">
                            <h4>Profile</h4>
                            <p>Nama :<br> {{ auth()->guard('customer')->user()->name }}</p>
                            <p>Username :<br>  {{ auth()->guard('customer')->user()->username }}</p>
                            <p>
                                <div class="user-info">
                                    <span>Email :<br> {{ auth()->guard('customer')->user()->email }}</span>
                                    @if(auth()->guard('customer')->user()->verification_token != 'verify')
                                        <div class="badge bg-warning">unverified</div>
                                    @endif
                                </div>
                            </p>
                            <p>Alamat :<br> 
                                {{ $data['district']->province }}, {{ $data['district']->type }} {{ $data['district']->name }}, {{ auth()->guard('customer')->user()->address }}</p>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- Tambahkan orderan terkini di sini (dummy data) -->
                            <h4>Order</h4>
                            <div class="row">
                            @if(count($data['orders']) < 1)
                                <div class="col my-3">                        
                                    <div class="card p-5">
                                        <h5>Data Tidak ditemukan</h5>
                                    </div>
                                </div>
                            @endif
                            @foreach($data['orders'] as $order)
                            <div class="col-md-6 my-3">                        
                                <div class="card">
                                    <div class="card-header">
                                        {{ $order->jenis }} {{ $order->order_date }}
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex">                                   
                                            @php
                                                $badgeColor = [
                                                    'PENDING' => 'bg-secondary',
                                                    'UNPAID' => 'bg-info',
                                                    'PAID' => 'bg-success',
                                                ][$order->status] ?? 'bg-info';
                                                $badgeColor2 = '';
                                                if ($order->status === 'PAID') {
                                                    $badgeColor2 = [
                                                        'PENDING' => 'bg-secondary',
                                                        'DELIVERY' => 'bg-info',
                                                        'DELIVERED' => 'bg-success',
                                                    ][$order->shipping_status] ?? 'bg-info';
                                                }
                                            @endphp
                                                Payment <span class="badge {{ $badgeColor }} text-white m-2">{{ $order->status }}</span>

                                            @if ($order->status === 'PAID')
                                                Shipping <span class="badge {{ $badgeColor2 }} text-white m-2">{{ $order->shipping_status }}</span>
                                            @endif
                                        </div>
                                        @if ($order->jenis === 'Product')
                                            <a href="{{ route('historyp.customer', $order->id) }}" class="btn btn-primary mt-2 px-3">Detail</a>
                                        @else
                                            <a href="{{ route('historyh.customer', $order->id) }}" class="btn btn-primary mt-2 px-3">Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center">
                                            {{ $data['orderspa'] }}
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <h4>Promo</h4>
                        <a href="{{ route('coupon.index') }}"><p>Coupon</p></a>
                        <hr>
                        <br>
                    <h4>Setting</h4>
                    <form action="{{ route('logout.customer') }}" method="post">
                        @csrf
                        <a href="{{ route('customer.profiile.edit') }}"> <p>Edit Profile</p> </a>
                        <hr>
                        <p><button type="button" data-bs-toggle="modal" data-bs-target="#feedback" class="dashboard">Feedback</button></p>
                        <hr>
                        <p><button type="submit" class="dashboard">Logout</button></p>
                        <hr>
                    </form>



                    <div class="modal fade" id="feedback" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title fs-5" id="exampleModalLabel">Feedback</h5>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">x</button>
                          </div>
                          <div class="modal-body">
                            <div class="image-modal-top">
                                <div class="container my-3">
                                    <form action="{{ isset($data['testimoni']) ? route('fo.testimoni.update', $data['s']) : route('fo.testimoni.store') }}" method="post" class="p-4 border rounded bg-light">
                                        @if (isset($data['testimoni']))
                                            @method('PUT')
                                        @endif
                                        @csrf
                                        <div class="form-group">
                                            <label for="rating" class="font-weight-bold">Rating:</label>
                                            <div class="star-rating">
                                                <?php
                                                    $rating = isset($data['testimoni']) ? $data['testimoni']['rating'] : 5;
                                                    for ($count = 5; $count >= 1; $count--) {
                                                        $checked = ($count == $rating) ? 'checked' : '';
                                                        echo '<input type="radio" id="star'.$count.'" name="rating" value="'.$count.'" '.$checked.'>';
                                                        echo '<label for="star'.$count.'" title="'.$count.' bintang">&#9733;</label>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="testimonial" class="font-weight-bold">Deskripsi:</label>
                                            <textarea class="form-control" id="testimonial" name="testimonial" rows="4" placeholder="">{{ old('testimonial', isset($data['testimoni']) ? $data['testimoni']['testimonial'] : '') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Kirim</button>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
	