@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])


@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
@endpush

@section('content')
    @include('admin.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <form method="POST" action="{{ $ref['url'] }}" enctype="multipart/form-data">
            @if (isset($slider))
                @method('PUT')
            @endif
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center pb-3">
                                    <h6 class="text-bold">{{ strtoupper('Informasi Slider') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Slider</label>
                                            <input id="name" name="slider_title" class="form-control bg-white"
                                                type="text" placeholder="Nama Slider"
                                                value="{{ old('slider_title', isset($slider) ? $slider->title : '') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">View Slider</label>
                                            <div class="input-group">
                                                <select class="form-control" name="view">
                                                    <option value="Desktop"
                                                        {{ old('view', isset($slider) ? ($slider->view == 'desktop' ? 'selected' : '') : '') }}>
                                                        Desktop</option>
                                                    <option value="SmartPhone"
                                                        {{ old('view', isset($slider) ? ($slider->view == 'smartphone' ? 'selected' : '') : '') }}>
                                                        SmartPhone</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <input type="checkbox" name="enable_button" id="enable_button">
                                            <label class="form-label" for="enable_button">Tambahkan tombol</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-none" id="button_config">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center pb-3">
                                    <h6 class="text-bold">{{ strtoupper('Ubah Pengaturan tombol') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Title Tombol</label>
                                            <input id="button_title" name="button_title" class="form-control bg-white"
                                                type="text" placeholder="Title Tombol"
                                                value="{{ old('button_title', isset($slider) ? $slider->button_title : '') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Link Tombol</label>
                                            <input id="button_link" name="button_link" class="form-control bg-white"
                                                type="text" placeholder="Link Tombol. ex: https://google.com"
                                                value="{{ old('button_link', isset($slider) ? $slider->button_link : '') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Warna Latar Belakang Tombol</label>
                                            <input id="button_background" name="button_background" class="form-control"
                                                type="color" style="width: 150px; heigth: 70px"
                                                value="{{ old('button_background', isset($slider) ? $slider->button_background : '#ffffff') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Warna Teks Tombol</label>
                                            <input id="button_text_color" name="button_text_color" class="form-control"
                                                type="color" style="width: 150px; heigth: 70px"
                                                value="{{ old('button_text_color', isset($slider) ? $slider->button_text_color : '#000000') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tata Letak Sumbu Horizontal</label>
                                            <div class="input-group">
                                                <select class="form-control" name="horizontal">
                                                    <option value="left"
                                                        {{ old('horizontal', isset($slider) ? ($slider->button_horizontal_layout == 'left' ? 'selected' : '') : '') }}>
                                                        Kiri</option>
                                                    <option value="center"
                                                        {{ old('horizontal', isset($slider) ? ($slider->button_horizontal_layout == 'center' ? 'selected' : '') : '') }}>
                                                        Tengah</option>
                                                    <option value="right"
                                                        {{ old('horizontal', isset($slider) ? ($slider->button_horizontal_layout == 'right' ? 'selected' : '') : 'selected') }}>
                                                        Kanan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ strtoupper('Foto Slider') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="main-img-preview">
                                                @if (isset($slider))
                                                    <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}"
                                                        id="front" class="thumbnail w-100">
                                                @else
                                                    <img id="front" class="thumbnail w-100" {{-- src="{{ asset('admin/img/placeholder.png') }}" --}}
                                                        src="{{ isset($data_gambar['image'][0]['image']) ? asset('storage/' . $data_gambar['front'][0]['name']) : asset('admin/img/placeholder.png') }}"
                                                        title="Foto Tampak Depan">
                                                @endif
                                            </div>
                                            <div class="frn float-end">
                                                <input id="pos-front" name="image"
                                                    value="{{ isset($data_gambar['image'][0]['name']) ? 'old_true' : '' }}"
                                                    readonly hidden>
                                                <div
                                                    class="mb-3 mt-3 me-1 w-auto fileUpload btn btn-outline-primary col-md-6">
                                                    <span><i class="bi bi-upload"></i></span>
                                                    <input id="input-front" name="image" type="file"
                                                        class="btnUpload attachment_upload" value>
                                                </div>
                                                <button id="remove-front" type="button"
                                                    class="btn btn-outline-danger m-0"
                                                    onclick="remove_photo('remove-front','front','input-front', 'pos-front')"
                                                    {{ isset($data_gambar['front'][0]['name']) ? '' : 'hidden' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('product.index') }}" type="submit"
                                    class="me-1 btn bg-gradient-danger">Batal</a>

                                @if (isset($slider))
                                    <button type="submit" class="btn bg-gradient-info">Update</button>
                                @else
                                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                                @endif
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
            const checkButton = document.getElementById('enable_button');
            const button_config = document.getElementById('button_config');

            checkButton.addEventListener('change', function() {
                if (checkButton.checked) {
                    button_config.classList.remove('d-none');
                } else {
                    button_config.classList.add('d-none');
                }
            })

            function populateSelect2(selector, url, selectedIds, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        cache: true
                    },
                    processResults: data => {
                        return {
                            results: data.map((row) => {
                                return {
                                    text: row.text,
                                    id: row.id
                                };
                            }),

                        };
                    },

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
                            $(selector).append('<option value="' + selectedId +
                                    '" selected="selected">' + selectedText + '</option>')
                                .trigger('change');
                        });
                    }
                });


            }

            // Populate category select2
            var categorySelector = '.categories-data';
            var categoryUrl = "{{ route('categories.sDatas') }}";
            var selectedCategoryIds = ['{{ old('category_id', isset($data) ? $data['category_id'] : '') }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Kategori");

            // populate sku
            var categorySelector = '.sku';
            var categoryUrl = "{{ route('sku.sDatas') }}";
            var selectedCategoryIds = ['{{ old('sku_id', isset($data)) ? $data['sku_id'] : '' }}', ];
            populateSelect2(categorySelector, categoryUrl, selectedCategoryIds, "Pilih Seri");

            // colorjs



            $('.categories-data').on('change', function() {
                var categoryId = $(this).val();
                $('.subcategories-data').val(null).trigger('change');
                $('.subcategories-data').prop('disabled', !categoryId);

                // Populate category select2
                var subCategorySelector = '.subcategories-data';
                var subCategoryUrl = "{{ route('subcategories.data.categori') }}?category_id=" +
                    categoryId;
                var selectedSubCategoryIds = [
                    @if (isset($data['subcategory_id']))
                        '{{ $data['subcategory_id'] }}',
                    @endif
                ];
                populateSelect2(subCategorySelector, subCategoryUrl, selectedSubCategoryIds,
                    "Pilih Subkategori");
            });
        });

        $(document).ready(function() {
            function readURL(input, target, appendTarget) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#' + target).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                    console.log(document.getElementById("remove-" + target));
                    document.getElementById("remove-" + target).removeAttribute('hidden');
                    document.getElementById('pos-' + target).value = target;
                }
            }
            $("#input-front").change(function() {
                readURL(this, 'front', ".frn");
            });

            $("#input-back").change(function() {
                readURL(this, 'back', ".bck");
            });

            $("#input-right").change(function() {
                readURL(this, 'right', ".rgt");
            });

            $("#input-left").change(function() {
                readURL(this, 'left', ".lft");
            });

            $("#input-detail1").change(function() {
                readURL(this, 'detail1', ".dtl1");
            });

            $("#input-detail2").change(function() {
                readURL(this, 'detail2', ".dtl2");
            });

            $("#input-detail3").change(function() {
                readURL(this, 'detail3', ".dtl3");
            });

            $("#input-detail4").change(function() {
                readURL(this, 'detail4', ".dtl4");
            });
        });

        function remove_photo(btnId, imageId, inputId, position) {
            document.getElementById(position).value = '';
            document.getElementById(inputId).value = '';
            document.getElementById(btnId).value = 1;
            document.getElementById(imageId).src = "{{ asset('admin/img/placeholder.png') }}";
            document.getElementById(btnId).setAttribute('hidden', true);
        }

        //angka
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
        });

        class PercentageInput {
            constructor(selector) {
                this.input = document.querySelector(selector);
                this.input.addEventListener('input', this.handleInput.bind(this));
                this.input.addEventListener('keypress', this.handleKeyPress.bind(this));
            }

            static handleInput(event) {
                var value = this.value;
                var newValue = '';

                for (var i = 0; i < value.length; i++) {
                    if (!isNaN(parseInt(value[i])) && value[i] !== ' ') {
                        newValue += value[i];
                    }
                }

                if (newValue === '') {
                    this.value = '';
                } else {
                    var number = parseInt(newValue);
                    if (number > 100) {
                        this.value = '100';
                    } else if (number < 1) {
                        this.value = '1';
                    } else {
                        this.value = number.toString();
                    }
                }
            }

            static handleKeyPress(event) {
                var key = event.which || event.keyCode;

                if (key < 48 || key > 57) {
                    event.preventDefault();
                }
            }
        }

        document.querySelectorAll('.percentage-input').forEach(function(input) {
            input.addEventListener('input', PercentageInput.handleInput);
            input.addEventListener('keypress', PercentageInput.handleKeyPress);
        });

        //rekomendasi harga jual
        function hitungHargaJual() {
            var hpp = parseFloat(document.getElementById('hpp').value) || 0; // Mengambil nilai HPP atau 0 jika kosong
            var margin = parseFloat(document.getElementById('margin').value) ||
                0; // Mengambil nilai margin atau 0 jika kosong
            var biayaLayanan = parseFloat(document.getElementById('b_layanan').value) ||
                0; // Mengambil nilai biaya layanan atau 0 jika kosong

            if (hpp !== 0 && margin !== 0 && biayaLayanan !== 0) {
                var hargaJual = hpp / (1 - (margin / 100 + biayaLayanan / 100));
                document.getElementById('r_h_jual').innerText = "Rekomendasi Harga Jual Rp. " + hargaJual.toFixed(2) +
                    " atau di atasnya";
            } else {
                document.getElementById('r_h_jual').innerText = "";
            }
        }

        // Event listener untuk setiap kali nilai HPP, margin, atau biaya layanan berubah
        document.getElementById('hpp').addEventListener('input', hitungHargaJual);
        document.getElementById('margin').addEventListener('input', hitungHargaJual);
        document.getElementById('b_layanan').addEventListener('input', hitungHargaJual);

        // Memanggil fungsi hitungHargaJual untuk menginisialisasi harga jual awal
        hitungHargaJual();
    </script>
@endpush
