@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                            <h6 class="text-bold">{{ strtoupper($ref['title']) }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input id="phone_number" name="phone_number" class="form-control bg-white" type="text" placeholder="Masukkan Nomor Telepon" value="{{ old('phone_number', isset($data) ? $data['phone_number'] : '') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Whatsapp</label>
                                    <input id="whatsapp" name="whatsapp" class="form-control bg-white" type="text" placeholder="Masukkan Nomor Whatsapp" value="{{ old('whatsapp', isset($data) ? $data['whatsapp'] : '') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Email</label>
                                    <input id="email" name="email" class="form-control bg-white" type="text" placeholder="Masukkan Email" value="{{ old('email', isset($data) ? $data['email'] : '') }}">
                                </div>
                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Kabupaten / Kota</label>
                                    <div class="input-group">
                                        <select name="id_distric" id="id_distric" class="form-control id_distric">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea id="address" name="address" class="form-control bg-white disable-resize" type="text" placeholder="Masukkan Alamat" rows="5">{{ old('address', isset($data) ? $data['address'] : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">MAPS</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Maps</label>
                                    <textarea id="maps" name="maps" class="form-control bg-white disable-resize" type="text" placeholder="Masukkan Letak Maps" rows="8">{{ old('maps', isset($data) ? $data['maps'] : '') }}</textarea>
                                </div>
                            </div>
                            <div class="float-end">
                                <button type="submit" class="btn bg-gradient-info">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('footer_script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function populateSelect2(selector, url, selectedIds, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    templateResult: function(data) {
                        return $('<span>' + data.text + '</span>');
                    }
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        selectedIds.forEach(function(selectedId) {
                            var selectedText = '';
                            response.results.forEach(function(item) {
                                if (item.id === selectedId) {
                                    selectedText = item.text;
                                }
                            });
                            $(selector).append('<option value="' + selectedId + '" selected="selected">' + selectedText + '</option>').trigger('change');
                        });
                    }
                });
            }

            // Populate category select2
            var categorySelector = '.id_distric';
            var categoryUrl = "{{ route('districs.data')}}";
            var selectedCategoryIds = [ '{{ old('id_distric', isset($data) ? $data['id_distric'] : '') }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kabupaten / Kota");

        });
    </script>
@endpush
