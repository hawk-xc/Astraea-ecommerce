@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

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
                                    <label class="form-label">Nama Kategori</label>
                                    <input id="name" name="name" class="form-control bg-white" type="text"
                                        placeholder="Nama Kategori"
                                        value="{{ old('name', isset($data) ? $data['name'] : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi kategori</label>
                                    <textarea rows="8" id="description" name="description" class="form-control" type="text" placeholder="Deskripsikan kategori kamu">{{ old('description', isset($data) ? $data['description'] : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('category.index') }}" type="submit"
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
                            kategori di gunakan untuk data referensi kategori dari produk kamu, jadi ketika kamu menambahkan
                            produk jangan lupa untuk pilih kategori nya ya.
                            dan yang perlu di ingat, ketika kategori mu sudah di pakai di dalam produk mu, maka kategori ini
                            tidak boleh di hapus, dan hanya boleh di edit nama nya saja.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
