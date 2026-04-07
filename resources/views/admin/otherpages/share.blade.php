@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.share') }}</h5>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <div class="card-block text-center">
                        <img src="https://qrcode.tec-it.com/API/QRCode?data={{ @$url }}&choe=UTF-8" width="300px" />
                        <div class="card-block mt-2">
                            <button class="btn btn-secondary px-sm-4 mb-4"
                                onclick="myFunction()">{{ trans('labels.share') }}
                                <i class="fa-sharp fa-solid fa-share-nodes ms-2"></i>
                            </button>
                            <a href="https://qrcode.tec-it.com/API/QRCode?data={{ @$url }}&choe=UTF-8"
                                target="_blank" class="btn btn-secondary px-sm-4 mb-4">{{ trans('labels.download') }}
                                <i class="fa-solid fa-arrow-down-to-line ms-2"></i>
                            </a>
                            <div id="share-icons" class="d-none">
                                {!! $shareComponent !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function myFunction() {
            $('#share-icons').removeClass('d-none');
        }
    </script>
@endsection
