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
                            <h6 class="text-bold">{{ strtoupper($ref['title']) }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Biaya Aplikasi</label>
                                    <input id="fee_amount" name="fee_amount" class="form-control bg-white input-angka" type="text"
                                        placeholder="Jumlah Biaya Aplikasi"
                                        value="{{ old('fee_amount', isset($data) ? $data['fee_amount'] : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('appfee.index') }}" type="submit"
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

@push('footer_script')
    <script>
        document.querySelectorAll('.input-angka').forEach(function(input) {
            input.addEventListener('input', function(event) {
                var value = this.value;
                var newValue = '';

                // Hapus karakter yang bukan angka dari input
                for (var i = 0; i < value.length; i++) {
                    if (!isNaN(parseInt(value[i])) && value[i] !== ' ') {
                        newValue += value[i];
                    }
                }

                // Ubah nilai input menjadi hanya angka
                this.value = newValue;
            });

            input.addEventListener('keypress', function(event) {
                // Mendapatkan kode tombol dari event
                var key = event.which || event.keyCode;

                // Mengizinkan input hanya jika tombol yang ditekan adalah angka atau tombol kontrol
                if (key < 48 || key > 57) {
                    event.preventDefault();
                }
            });
})
    </script>
@endpush