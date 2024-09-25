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
                            <h6 class="text-bold">TAMBAH {{ strtoupper($ref['title']) }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Kode SKU</label>
                                    <input id="code" name="code" class="form-control bg-white" type="text"
                                        placeholder="Kode SKU"
                                        value="{{ old('code', isset($data) ? $data['code'] : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama SKU</label>
                                    <input id="name" name="name" class="form-control bg-white" type="text"
                                        placeholder="Nama SKU"
                                        value="{{ old('name', isset($data) ? $data['name'] : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('sku.index') }}" type="submit"
                                    class="me-1 btn bg-gradient-danger">Batal</a>
                                <button type="submit" class="btn bg-gradient-info">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
