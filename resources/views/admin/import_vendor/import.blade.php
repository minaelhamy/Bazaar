@extends('admin.layout.default')
@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card border-0 box-shadow mb-3">
                <div class="card-header p-3 bg-white">
                    <h6 class="text-dark fs-600">{{ trans('labels.step_1') }} :</h6>
                </div>
                <div class="card-body">
                    <ul class="fs-7 d-flex text-dark flex-column gap-2 fw-500">
                        <li class="d-flex gap-1">
                            <b>1.</b> {{ trans('labels.vendor_import_download_file') }}
                        </li>
                        <li class="d-flex gap-1"><b>2.</b>
                            {{ trans('labels.vendor_import_download_example_file_to_understand') }}</li>
                        <li class="d-flex gap-1"><b>3.</b> {{ trans('labels.vendor_import_upload_submit') }}</li>
                    </ul>
                </div>
            </div>
            <a href="{{ url(env('ASSETPATHURL') . 'admin-assets/sample_demo.xlsx') }}"
                class="btn btn-primary px-sm-4 fs-15 fw-500 mb-3">{{ trans('labels.download_CSV') }}</a>
            <div class="card border-0 box-shadow mb-3">
                <div class="card-header p-3 bg-white">
                    <h6 class="text-dark fs-600">{{ trans('labels.step_2') }} :</h6>
                </div>
                <div class="card-body">
                    <ul class="fs-7 d-flex text-dark flex-column gap-2 fw-500">
                        <li class="d-flex gap-1"><b>1.</b> {{ trans('labels.vendor_import_download_file') }}</li>
                        <li class="d-flex gap-1"><b>2.</b>
                            {{ trans('labels.vendor_import_download_example_file_to_understand') }}</li>
                        <li class="d-flex gap-1"><b>3.</b> {{ trans('labels.vendor_import_upload_submit') }}</li>
                    </ul>
                </div>
            </div>
            <a href="{{ URL::to('admin/users/generate_city_pdf') }}"
                class="btn btn-primary px-sm-4 fs-15 fw-500 mb-3">{{ trans('labels.download_city') }}</a>
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('admin/users/import_vendor') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 form-group">
                            <label class="form-label">{{ trans('labels.vendor_upload') }}<span class="text-danger"> *
                                </span></label>
                            <input type="file" class="form-control" name="importfile" id="importfile" multiple=""
                                required>
                        </div>
                        <button
                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                            class="btn btn-primary px-sm-4 fs-15 fw-500 {{ Auth::user()->type == 4 ? (helper::check_access('role_tax', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.import') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
