@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link rel="stylesheet" href="{{ asset('admin/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
@endpush

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp
    @include('admin.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">LIST DARI SEMUA {{ strtoupper($ref['title']) }} YANG KAMU MILIKI</h6>
                        <a href="{{ route('service.create') }}" class="btn bg-gradient-primary float-end">Tambah Data</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-stripe services_table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>name</th>
                                    <th>desctiption</th>
                                    <th class="text-center w-1">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ref['services'] as $index => $service)
                                    <tr>
                                        <td style="padding: 20px;">{{ $index + 1 }}</td>
                                        <td style="padding: 20px;">{{ $service->name }}</td>
                                        <td>
                                            {{ Str::limit($service['description'], 70, '...') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('service.edit', $service->slug) }}"
                                                class="btn bg-gradient-info btn-tooltip">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Form Delete -->
                                            <form action="{{ route('service.destroy', $service->slug) }}" method="POST"
                                                class="d-inline deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn bg-gradient-danger btn-tooltip show-alert-delete-box"
                                                    data-toggle="tooltip" title="Delete" data-name="">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
        $(document).on('click', '.show-alert-delete-box', function(event) {
            event.preventDefault(); // Prevent form submission
            var form = $(this).closest("form");
            var name = $(this).data("name");

            $.confirm({
                icon: 'fa fa-warning',
                title: 'Yakin Hapus Data?',
                content: 'Data dengan ID ' + name + ' akan dihapus secara permanen.',
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'Hapus',
                        btnClass: 'btn-red',
                        action: function() {
                            form.submit(); // Submit form setelah konfirmasi
                        }
                    },
                    cancel: function() {
                        // Tidak melakukan apa-apa jika klik "Batal"
                    }
                }
            });
        });
    </script>
@endpush
