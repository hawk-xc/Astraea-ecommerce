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
                        <h6 class="text-bold">LIST DARI SEMUA {{ strtoupper($ref['title']) }}</h6>
                        <a href="{{ route('slider.create') }}" class="btn bg-gradient-primary float-end">Tambah Data</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-stripe hampers_table">
                            <thead>
                                <tr>
                                    <th class="text-center font-weight-bold text-primary w-5">Slider</th>
                                    <th class="font-weight-bold text-primary">Nama Slider</th>
                                    <th class="font-weight-bold text-primary">Tampilan</th>
                                    <th class="font-weight-bold text-primary">Tombol</th>
                                    <th class="font-weight-bold text-primary">Referensi</th>
                                    <th class="text-center w-1 font-weight-bold text-primary">action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sliders as $key => $slider)
                                    <tr>
                                        <td class="fw-bold"> <img src="{{ asset($slider->image) }}" class="product-img"
                                                style="width: 250px" alt=""></td>
                                        <td>{{ $slider->title }}</td>
                                        <td>{{ $slider->view }}</td>
                                        <td>{{ isset($slider->button_title) ? 'Ya' : 'Tidak' }}</td>
                                        <td>{{ isset($slider->button_link) ? $slider->button_link : '-' }}</td>
                                        <td>
                                            <a href="{{ route('slider.edit', $slider->id) }}"
                                                class="btn bg-gradient-info btn-tooltip">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Form Delete -->
                                            <form action="{{ route('slider.destroy', $slider->id) }}" method="POST"
                                                class="d-inline deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn bg-gradient-danger btn-tooltip show-alert-delete-box"
                                                    data-toggle="tooltip" title="Delete" data-name="{{ $slider->id }}">
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
