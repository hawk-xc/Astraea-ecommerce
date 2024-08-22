@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @include('admin.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">DETAIL PENGIRIMAN {{ strtoupper($ref['title']) }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">No Nota</label><br>
                                    <label class="form-label">{{ $data['no_nota'] }}</label>
                                    
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ekpedisi</label><br>
                                    <label class="form-label">{{ Str::upper($data['shipping_data']['name']) }}</label>     
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Service</label><br>
                                    <label class="form-label">{{ $data['shipping_data']['service'] }}</label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Berat</label><br>
                                    <label class="form-label">{{ $data['shipping_data']['weight'] }} Gram</label>
                                    
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label><br>
                                    <label class="form-label">{{ $data['shipping_data']['district_data']['province'] }}, {{ $data['shipping_data']['district_data']['type'] }} {{ $data['shipping_data']['district_data']['name'] }}, <br>
                                    {{ $data['address'] }}</label>
                                    
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga Pengiriman</label><br>
                                    <label class="form-label">{{ $data['shipping'] }}</label>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">STATUS {{ strtoupper($ref['title']) }}</h6>
                    </div>
                    <form method="POST" action="{{ $ref['url'] }}">
                    @if (isset($data))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="card-body">
                        <div class="mb-3-select" id="role">
                            <label class="form-label">Status Pengiriman</label>
                            <div class="input-group">
                                <select name="shipping_status" id="shipping_status" class="form-control select2">
                                    <option value="PENDING" {{ isset($data['shipping_status']) ? ('PENDING' == $data['shipping_status'] ? 'selected' : '') : '' }}>PENDING</option>
                                    <option value="DELIVERY" {{ isset($data['shipping_status']) ? ('DELIVERY' == $data['shipping_status'] ? 'selected' : '') : '' }}>DELIVERY</option>
                                    <option value="DELIVERED" {{ isset($data['shipping_status']) ? ('DELIVERED' == $data['shipping_status'] ? 'selected' : '') : '' }}>DELIVERED</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('order_product.show', $data['id']) }}" type="submit"
                                    class="me-1 btn bg-gradient-danger">Batal</a>
                                <button type="submit" class="btn bg-gradient-info">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
