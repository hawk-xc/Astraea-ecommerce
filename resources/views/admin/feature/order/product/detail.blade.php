@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link rel="stylesheet" href="{{ asset('admin/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
@endpush

@section('content')
    @include('admin.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">DETAIL {{ strtoupper($ref['title']) }} NO.{{ $data['no_nota'] }}</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-stripe">
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Pelanggan</td>
                                <td>{{ $data['customer_data']['name'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Username</td>
                                <td>{{ $data['customer_data']['username'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Email</td>
                                <td>{{ $data['customer_data']['email'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Alamat Pengiriman</td>
                                <td>{{ $data['address'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Tanggal Pesanan</td>
                                <td>{{ $data['order_date'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Status Pembayaran</td>
                                <td>{{ $data['status'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Status Pengiriman</td>
                                <td class="d-flex justify-content-between">
                                    <span class="py-2">{{ $data['shipping_status'] }}</span>
                                    <div>
                                        <a href="{{ route('order_product.edit',$data['id']) }}" class="btn bg-gradient-info btn-tooltip">
                                            <i class="bi bi-pencil-square"></i></a>
                                        <a href="{{ route('resi_product.print',$data['id']) }}" target="__blank" class="btn bg-gradient-primary btn-tooltip">
                                           <i class="bi bi-printer"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="container">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="pesanan-tab" data-bs-toggle="tab" data-bs-target="#pesanan" type="button" role="tab" aria-controls="pesanan" aria-selected="true">Pesanan</button>
                                            </li>
                                            
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pengiriman-tab" data-bs-toggle="tab" data-bs-target="#pengiriman" type="button" role="tab" aria-controls="pengiriman" aria-selected="false">Pengiriman</button>
                                            </li>
                                            @if(isset($data['payment_data']))
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pembayran-tab" data-bs-toggle="tab" data-bs-target="#pembayran" type="button" role="tab" aria-controls="pembayran" aria-selected="false">Pembayran</button>
                                            </li>
                                            @endif
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active py-3" id="pesanan" role="tabpanel" aria-labelledby="pesanan-tab">                                                
                                                <table class="table table-stripe order_detail_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Harga Barang</th>
                                                            <th>Jumlah</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>

                                            </div>
                                            @if(isset($data['payment_data']))
                                            <div class="tab-pane fade py-3" id="pembayran" role="tabpanel" aria-labelledby="pembayran-tab">
                                                <table class="table table-stripe">
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Metode Pembayaran</td>
                                                        <td>{{ Str::upper($data['payment_data']['payment_channel']) }}</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Jumlah Pembayaran</td>
                                                        <td>{{ 'Rp. ' . number_format($data['payment_data']['amount'], 0, ",", ".") }}</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Tanggal Pembayaran</td>
                                                        <td>{{ (new DateTime($data['payment_data']['paid_at']))->format('d-m-Y H:i:s') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            @endif
                                            <div class="tab-pane fade py-3" id="pengiriman" role="tabpanel" aria-labelledby="pengiriman-tab">
                                                <table class="table table-stripe">
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Ekpedisi</td>
                                                        <td>{{ Str::upper($data['shipping_data']['name']) }}</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Service</td>
                                                        <td>{{ $data['shipping_data']['service'] }}</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Berat</td>
                                                        <td>{{ $data['shipping_data']['weight'] }} Gram</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Alamat</td>
                                                        <td>
                                                        {{ $data['shipping_data']['district_data']['province'] }}, {{ $data['shipping_data']['district_data']['type'] }} {{ $data['shipping_data']['district_data']['name'] }}, <br>
                                                        {{ $data['address'] }}</td>
                                                    </tr>
                                                    <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                                        <td>Harga Pengiriman</td>
                                                        <td>{{ $data['shipping'] }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Sub Total</td>
                                <td>{{ $data['sub_total_price'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Biaya Pengiriman</td>
                                <td>{{ $data['shipping'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Biaya Admin</td>
                                <td>{{ $data['app_admin'] }}</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>Diskon</td>
                                <td>{{ $data['discount_amount'] }} %</td>
                            </tr>
                            <tr class="ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal">
                                <td>total bayar</td>
                                <td>{{ $data['total_price'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_script')
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/dataTables.bootstrap5.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('.order_detail_table').DataTable({
                language: {
                    paginate: {
                        next: "›",
                        previous: "‹"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('order_product.detail', $data['id']) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'product_data.name',
                        name: 'product_data.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'sub_total_price',
                        name: 'sub_total_price'
                    },
                ],
                columnDefs: [{
                        "targets": 0,
                        "className": "text-center align-middle text-sm font-weight-normal",
                        "width": "4%"
                    },
                    {
                        "targets": 1,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 2,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 3,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    },
                    {
                        "targets": 4,
                        "className": "ps-3 pt-0 pb-0 align-middle text-sm font-weight-normal",
                    }
                ]
            });

            $(document).on('click', '#deleteRow', function(event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                event.preventDefault();
                $.confirm({
                    icon: 'fa fa-warning',
                    title: 'Yakin Hapus Data',
                    content: 'Data ' + $(this).data('message') + ' Akan di hapus secara permanen',
                    type: 'orange',
                    typeAnimated: true,
                    animationSpeed: 500,
                    closeAnimation: 'zoom',
                    closeIcon: true,
                    closeIconClass: 'fa fa-close',
                    draggable: true,
                    backgroundDismiss: false,
                    backgroundDismissAnimation: 'glow',
                    buttons: {
                        delete: {
                            text: 'Hapus',
                            btnClass: 'btn-red',
                            action: function() {
                                form.submit();
                            }
                        },
                        batal: function() {}
                    }
                });
            });
        });
    </script>
@endpush
