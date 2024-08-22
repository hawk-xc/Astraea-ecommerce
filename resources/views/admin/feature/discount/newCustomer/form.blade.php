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
                        <div class="card-footer">
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
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
