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
                <form method="POST" action="{{ $ref['url'] }}">
                    @if (isset($data))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">TAMBAH {{ strtoupper($ref['title']) }} KAMU</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input id="holder_name" name="holder_name" class="form-control bg-white" type="text"
                                        placeholder="Nama"
                                        value="{{ old('holder_name', isset($data) ? $data['holder_name'] : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bank</label>
                                    <input id="name_bank " name="name_bank" class="form-control bg-white" type="text"
                                        placeholder="Bank"
                                        value="{{ old('name_bank', isset($data) ? $data['name_bank'] : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Rekening</label>
                                    <input id="rekening_number" name="rekening_number" class="form-control bg-white" type="text"
                                        placeholder="No Rekening"
                                        value="{{ old('rekening_number', isset($data) ? $data['rekening_number'] : '') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Aktivasi akun</label>
                                    <div class="input-group">
                                        <select name="is_active" id="is_active" class="form-control select2">
                                            <option disabled selected value="">apakah akun aktif?</option>
                                                <option value="0"
                                                    {{ isset($data['is_active']) ? ('0' == $data['is_active'] ? 'selected' : '') : '' }}>Tidak Aktif</option>
                                                <option value="1"
                                                    {{ isset($data['is_active']) ? ('1' == $data['is_active'] ? 'selected' : '') : '' }}>Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('paymentmethod.index') }}" type="submit"
                                    class="me-1 btn bg-gradient-danger">Batal</a>
                                <button type="submit" class="btn bg-gradient-info">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">APA ITU {{ strtoupper($ref['title']) }}</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-justify">
                            Payment Method di gunakan untuk data referensi pembayaran dari produk kamu, jadi ketika kamu menambahkan

                            sorry mas isian e aku gaeruh
                        </p>
                    </div>
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
