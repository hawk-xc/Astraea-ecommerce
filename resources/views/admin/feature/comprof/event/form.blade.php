@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
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
                            <h6 class="text-bold">{{ Str::upper($ref['title'])}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Name</label>
                                    <input id="title" name="title" class="form-control bg-white" type="text"
                                        placeholder="Input Event Name"
                                        value="{{ old('title', isset($data) ? $data['title'] : '') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Date</label>
                                    <input id="tanggal_acara" name="tanggal_acara" class="form-control bg-white tanggal" type="text"
                                        placeholder="Input Event Date"
                                        value="{{ old('tanggal_acara', isset($data) ? $data['tanggal_acara'] : '') }}">
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
                                    <input type="file" class="form-control" name="cover_image" accept="image/*">
                                </div>
                                <div class="upload__img-wrap">
                                    @if (isset($data['cover_image']))
                                        <div class='upload__img-box'>
                                            <img src="{{ asset('storage/' . $data['cover_image']) }}" class='img-bg br-5' style="max-width: 100%; max-height: 300px;">
                                            <div class='upload__img-close-circle'><span>X</span></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ Str::upper($ref['title']) }} DESCRIPTION</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="description" name="description"
                                    class="form-control bg-white disable-resize summernote" type="text"
                                    placeholder="Masukkan service description"
                                    rows="15">{{ old('description', isset($data) ? $data['description'] : '') }}</textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.summernote').summernote({
            tabsize: 2,
            height: 300,
            toolbar: [
                ['pagebreak', ['pagebreak']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['insert', ['picture', 'link', 'video', 'table']],
                ['fontsize', ['fontsize']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['misc', ['undo', 'redo', 'codeview']]
            ]
        });

        $(document).ready(function() {
            $('input[name="cover_image"]').on('change', function() {
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
                    var input = imgWrap.closest('.upload__box').find('input[name="cover_image"]');
                    input.val('');
                });
            }
        });

        flatpickr(".tanggal", {
            dateFormat: "Y-m-d",
            enableTime: false,
            minDate: "today",
          });
    </script>
@endpush
