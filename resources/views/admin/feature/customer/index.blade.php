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
                        <h6 class="text-bold">LIST DARI SEMUA {{ strtoupper($ref['title']) }} YANG KAMU MILIKI</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-stripe customer_table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th class="text-center w-1">action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
            var table = $('.customer_table').DataTable({
                language: {
                    paginate: {
                        next: "›",
                        previous: "‹"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('management_customer.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'tgl_lahir',
                        name: 'tgl_lahir'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                    },
                    {
                        "targets": 5,
                        "className": "align-middle text-sm font-weight-normal",
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
