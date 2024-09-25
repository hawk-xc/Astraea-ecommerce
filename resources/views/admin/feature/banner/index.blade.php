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
            @method('PUT')
            @csrf

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h6 class="text-bold">{{ strtoupper('Foto Banner') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="main-img-preview">
                                                @if (isset($banner))
                                                    <img src="{{ asset($banner->images) }}"
                                                        alt="{{ $banner->images . ' images' }}" id="front"
                                                        class="thumbnail w-100">
                                                @else
                                                    <img id="front" class="thumbnail w-100"
                                                        src="{{ isset($data_gambar['image'][0]['image']) ? asset('storage/' . $data_gambar['front'][0]['name']) : asset('admin/img/placeholder.png') }}"
                                                        title="Not showing up">
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
                                                <button id="remove-front" type="button" class="btn btn-outline-danger m-0"
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
                                <button type="submit" id="update-button" class="btn bg-gradient-info"
                                    hidden>Update</button>
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
            function readURL(input, target) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#' + target).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                    // Show the Update button when a file is uploaded
                    document.getElementById('update-button').removeAttribute('hidden');
                }
            }

            $("#input-front").change(function() {
                readURL(this, 'front');
            });
        });
    </script>
@endpush