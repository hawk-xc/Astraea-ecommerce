@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])


@push('header_script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            @if (isset($data))
                @method('PUT')
            @endif
            @csrf
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ strtoupper('Informasi Discount') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Nama Discount</label>
                                    <input id="title" name="title" class="form-control bg-white" type="text"
                                        placeholder="Nama Discount"
                                        value="{{ old('title', isset($data) ? $data['title'] : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Besaran Diskon</label>
                                    <div class="input-group">
                                    <input id="discount_amount" name="discount_amount" class="percentage-input form-control bg-white" type="text"
                                        placeholder="Besaran Diskon" value="{{ old('discount_amount', isset($data) ? $data['discount_amount'] : '') }}">
                                    <span class="input-group-text ">%</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kode Diskon</label>
                                    <input id="code_discount" name="code_discount" class="form-control bg-white" type="text"
                                        placeholder="Kode Diskon" value="{{ old('code_discount', isset($data) ? $data['code_discount'] : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Dimulai</label>
                                    <div class="input-group">
                                    <input id="start_date" name="start_date" class="tanggal-mulai form-control bg-white" type="text"
                                        placeholder="Tanggal Dimulai" value="{{ old('start_date', isset($data) ? $data['start_date'] : '') }}">
                                    <input id="start_time" name="start_time" class="waktu form-control bg-white" type="text"
                                        placeholder="Jam Dimulai" value="{{ old('start_time', isset($data) ? $data['start_time'] : '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Berakhir</label>
                                    <div class="input-group">
                                    <input id="end_date" name="end_date" class="tanggal-selesai form-control bg-white" type="text"
                                        placeholder="Tanggal Berakhir" value="{{ old('end_date', isset($data) ? $data['end_date'] : '') }}">
                                    <input id="end_time" name="end_time" class="waktu form-control bg-white" type="text"
                                        placeholder="Jam Berakhir" value="{{ old('end_time', isset($data) ? $data['end_time'] : '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Diskon</label>
                                    <textarea rows="7" id="description" name="description_discount" class="form-control" type="text"
                                        placeholder="Silahkan Deskripsi Diskon">{{ old('description_discount', isset($data) ? $data['description_discount'] : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ Str::upper($ref['title']) }} IMAGE</h6>
                        </div>
                        <div class="card-body">
                            <div class="upload__box m-0 p-0 pb-3">
                                <div class="upload__btn-box mb-3">
                                    <label class="form-label">Please select image</label>
                                    <input type="file" class="form-control" name="image_banner" accept="image/*">
                                </div>
                                <div class="upload__img-wrap">
                                    @if (isset($data['image_banner']))
                                        <div class='upload__img-box'>
                                            <img src="{{ asset('storage/' . $data['image_banner']) }}" class='img-bg br-5' style="max-width: 100%; max-height: 300px;">
                                            <div class='upload__img-close-circle'><span>X</span></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ strtoupper('Penerapan Diskon') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Diskon Pada Produk</label>
                                    <div class="input-group">
                                        <select name="product_id[]" id="product_id" class="form-control product-data" multiple="multiple">
                                        </select>
                                    </div>
                                </div>

                            <div class="col-md-12">
                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Diskon Pada Hampers</label>
                                    <div class="input-group">
                                        <select name="hampers_id[]" id="hampers_id" class="form-control hampers-data" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3-select" id="role">
                                    <label class="form-label">Pelanggan Penerima Diskon</label>
                                    <div class="input-group">
                                        <select name="costumer_id[]" id="custumer" class="form-control costumer-data" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('discount.index') }}" type="submit"
                                    class="me-1 btn bg-gradient-danger">Batal</a>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

            // Populate product select2
            var productSelector = '.product-data';
            var productUrl = "{{ route('products.sDatas')}}";
            var selectedProductIds = [
                @if (isset($data['d_products']))
                    @foreach($data['d_products'] as $productt)
                        '{{ $productt["product_id"] }}',
                    @endforeach
                @endif
            ];
            populateSelect2(productSelector, productUrl, selectedProductIds, "Pilih Produk yang akan di diskon");

            // Populate hampers select2
            var hampersSelector = '.hampers-data';
            var hampersUrl = "{{ route('hampers.sDatas')}}";
            var selectedHampersIds = [
                @if (isset($data['d_hampers']))
                    @foreach($data['d_hampers'] as $hampersp)
                        '{{ $hampersp["hampers_id"] }}',
                    @endforeach
                @endif
            ];
            populateSelect2(hampersSelector, hampersUrl, selectedHampersIds, "Pilih Produk Hampers yang akan di diskon");

            // Populate customer select2
            var userSelector = '.costumer-data';
            var userUrl = "{{ route('management_customer.sDatas')}}";
            var selectedUserIds = [
                @if (isset($data['d_customer']))
                    @foreach($data['d_customer'] as $customer)
                        {{ $customer["costumer_id"] }},
                    @endforeach
                @endif
            ];
            populateSelect2(userSelector, userUrl, selectedUserIds, "Pilih pelanggan yang akan menerima diskon");
        });



        //precent
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

        $(document).ready(function() {
            $('input[name="image_banner"]').on('change', function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var imgWrap = $(input).closest('.upload__box').find('.upload__img-wrap');
                        imgWrap.empty();
                        imgWrap.append('<div class="upload__img-box"><img src="' + e.target.result + '" class="img-bg br-5" style="max-width: 100%; max-height: 300px;"><div class="upload__img-close-circle"><span>X</span></div></div>');
                        bindImgCloseEvent();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            function bindImgCloseEvent() {
                $('.upload__img-close-circle').on('click', function() {
                    var imgWrap = $(this).closest('.upload__img-wrap');
                    imgWrap.empty();
                    var input = imgWrap.closest('.upload__box').find('input[name="image_banner"]');
                    input.val('');
                });
            }
        });

        flatpickr(".tanggal-mulai", {
            dateFormat: "Y-m-d",
            enableTime: false,
            minDate: "today",
          });

        var tanggalSelesai = flatpickr(".tanggal-selesai", {
            dateFormat: "Y-m-d",
            enableTime: false,
            minDate: new Date()
        });

        document.getElementById('start_date').addEventListener('change', function() {
            var startDateValue = this.value;
            if(startDateValue) {
                var startDate = new Date(startDateValue);
                tanggalSelesai.set('minDate', startDate);
            }
        });

        flatpickr(".waktu", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
          });

    </script>
@endpush
