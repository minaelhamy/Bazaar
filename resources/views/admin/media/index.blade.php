@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.media') }} <span> ({{ $media->count() }} {{ trans('labels.images') }})
            </span></h5>
    </div>
    <div class="row">
        <form action=" {{ URL::to('admin/media/add_image') }}" method="post" enctype="multipart/form-data"
            class="d-flex gap-2">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="product_id" name="product_id">
                <input type="file" name="image[]" class="form-control" multiple>

            </div>
            <div class="modal-footer">
                <button @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif class="btn btn-primary px-sm-4">{{ trans('labels.save') }}</button>
            </div>
        </form>
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="row row-cols-xxl-6 row-cols-xl-5 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 popup-gallery">
                        @foreach ($media as $key => $data)
                        <div class="col">
                            <div class="card rounded overflow-hidden one-card h-100 w-100">
                                <a href="{{ @helper::image_path($data->image) }}">
                                    <img src="{{ @helper::image_path($data->image) }}" alt="pro img"
                                        class="w-100 card-img-top object" id="{{ @helper::image_path($data->image) }}">
                                </a>
                                <div class="card-body">
                                    {{-- <div class="one-img"> --}}
                                    {{-- </div> --}}
                                    <div class="dropdown">
                                        <button class="one-card-dropdown dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu overflow-hidden rounded p-0 {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                            <li><a class="dropdown-item p-2 {{ Auth::user()->type == 4 ? (helper::check_access('role_import_product', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/media/delete-' . $data->id) }}')" @endif>{{ trans('labels.delete') }}</a>
                                            </li>
                                            <li><a class="dropdown-item p-2"
                                                    href="{{ URL::to('admin/media/download-' . $data->id) }}">{{ trans('labels.download') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>{{ $data->image }}</p>
                                    <a class="text-dark cursor-pointer {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}"
                                        tooltip="{{ trans('labels.copy') }}"
                                        onclick="CopyLink('{{ helper::image_path($data->image) }}')"><i
                                            class="fa-regular fa-copy"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('.popup-gallery .one-img').magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                },
            });
        });

        function CopyLink(text) {
            var temp = document.createElement('INPUT');
            temp.style.position = 'fixed'; //hack to keep the input off-screen...
            temp.style.left = '-10000px'; //...but I'm not sure it's needed...
            document.body.appendChild(temp);
            temp.value = text;
            temp.select();
            document.execCommand("copy");
            //temp.remove(); //...as we remove it before reflow (??)
            document.body.removeChild(temp); //to accommodate IE


            toastr.success("{{ trans('labels.copied') }}");
        }
    </script>
@endsection
