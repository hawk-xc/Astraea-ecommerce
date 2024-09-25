@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                                    <label class="form-label">Nama Subkategori</label>
                                    <input id="name" name="name" class="form-control bg-white" type="text"
                                        placeholder="Nama Subkategori"
                                        value="{{ old('name', isset($data) ? $data['name'] : '') }}">
                                </div>
                                <div class="mb-4">
                                     <label class="form-label">Kategori</label>
                                    <select class="categories-data form-control bg-white p-1" name="id_category">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Subkategori</label>
                                    <textarea rows="8" id="description" name="description" class="form-control" type="text" placeholder="Deskripsikan subkategori kamu">{{ old('description', isset($data) ? $data['description'] : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <div class="float-end">
                                <a href="{{ route('subcategory.index') }}" type="submit"
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
    <script type="text/javascript">
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

            // Populate product select2
            var categorySelector = '.categories-data';
            var categoryUrl = "{{ route('categories.sDatas')}}";
            var selectedCategoryIds = [ '{{ old('id_category', isset($data) ? $data['id_category'] : '') }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kategori");
        });
    </script>
@endpush
