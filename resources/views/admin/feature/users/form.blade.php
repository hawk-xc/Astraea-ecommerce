@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])


@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @include('admin.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <form method="POST" action="{{ $ref['url'] }}">
            @if (isset($data))
                @method('PUT')
            @endif
            @csrf
        <div class="row">
            <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ strtoupper("data diri pengguna") }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input id="name" name="name" class="form-control bg-white" type="text"
                                        placeholder="Nama Lengkap"
                                        value="{{ old('name', isset($data) ? $data['name'] : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input id="email" name="email" class="form-control bg-white" type="email"
                                        placeholder="Email" value="{{ old('email', isset($data) ? $data['email'] : '') }}">
                                </div>

                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Akses Pengguna</label>
                                    <div class="input-group">
                                        <select name="role_id" id="role_id" class="form-control select2">
                                            <option disabled selected value="">Silahkan Pilih Akses Pengguna</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ isset($data["role_id"]) ? ($role->id == $data["role_id"] ? 'selected' : '') : '' }}>
                                                    {{ Str::ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea rows="7" id="address" name="address" class="form-control" type="text"
                                        placeholder="Silahkan masukkan alamat pengguna">{{ old('address', isset($data) ? $data['address'] : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">{{ strtoupper("kredensial pengguna") }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Pengguna</label>
                            <input id="username" name="username" class="form-control bg-white" type="text"
                                placeholder="Nama Pengguna"
                                value="{{ old('username', isset($data) ? $data['username'] : '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kata kunci</label>
                            <input id="password" name="password" class="form-control bg-white" type="password"
                                placeholder="Kata Kunci" value="{{ old('password', isset($data) ? $data['password'] : '') }}">
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center pb-3">
                        <h6 class="text-bold">APA ITU {{ strtoupper($ref['title']) }}</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-justify text-sm">
                            Akun pengguna adalah akun yang di gunakan untuk masuk ke dalam halaman admin,
                            pengguna memiliki beberapa role akses seperti admin, operator, dan editor
                            <li class="text-justify text-sm">Admin dapat mengakses penuh pada halaman admin ini</li>
                            <li class="text-justify text-sm">Operator dapat mengakses produk</li>
                            <li class="text-justify text-sm">Editor dapat mengakses halaman pengolahan profil halaman utama
                            </li>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="float-end">
            <a href="{{ route('user.index') }}" type="submit"
                class="me-1 btn bg-gradient-danger">Batal</a>
            <button type="submit" class="btn bg-gradient-info">Simpan</button>
        </div>
        </form>
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
