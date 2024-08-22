@extends('admin.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('header_script')
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
                            <h6 class="text-bold">{{ Str::upper($ref['title']) }} IMAGE</h6>
                        </div>
                        <div class="card-body">
                            <div class="upload__box m-0 p-0 pb-3">
                                <div class="upload__btn-box mb-3">
                                    <label class="form-label">Please select image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                                <div class="upload__img-wrap">
                                    @if (isset($data['image']))
                                        <div class='upload__img-box'>
                                            <img src="{{ asset('storage/' . $data['image']) }}" class='img-bg br-5' style="max-width: 100%; max-height: 300px;">
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
                            <h6 class="text-bold">{{ Str::upper($ref['title'])}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Title</label>
                                    <input id="title" name="title" class="form-control bg-white" type="text"
                                        placeholder="Input title name"
                                        value="{{ old('title', isset($data) ? $data['title'] : '') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea id="description" name="description"
                                        class="form-control bg-white disable-resize" type="text"
                                        placeholder="Masukkan certificate description"
                                        rows="15">{{ old('description', isset($data) ? $data['description'] : '') }}</textarea>
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
    <script>
        $(document).ready(function() {
            $('input[name="image"]').on('change', function() {
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
                    var input = imgWrap.closest('.upload__box').find('input[name="image"]');
                    input.val('');
                });
            }
        });
    </script>
@endpush
