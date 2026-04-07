@extends('admin.layout.default')

@section('content')
    <div class="d-flex justify-content-between align-items-center">

        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.add_new') }}</h5>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb m-0">

                <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/how_it_works') }}"
                        class="color-changer">{{ trans('labels.how_it_works') }}</a></li>

                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>

            </ol>

        </nav>

    </div>

    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 box-shadow">

                <div class="card-body">

                    <form action="{{ URL::to('/admin/how_it_works/save') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.title') }}<span class="text-danger"> *

                                    </span></label>
                                <div class="d-flex align-items-center gap-2">

                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ old('title') }}" placeholder="{{ trans('labels.title') }}" required>
                                    @if (@helper::checkaddons('ai_settings'))
                                        {{-- AI Button: flex-shrink-0 zaroori hai taaki button chhota na ho --}}
                                        <button type="button" id="ai_btn"
                                            class="btn btn-sm btn-primary d-none flex-shrink-0" style="border-radius: 5px;"
                                            onclick="ai_generate()" tooltip="{{ trans('labels.ai_generate') }}">
                                            <i class="fa-solid fa-robot me-1"></i> {{ trans('labels.ai') }}
                                        </button>
                                    @endif
                                </div>


                            </div>

                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.image') }}<span class="text-danger"> *

                                    </span></label>

                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="form-group">

                                <label class="form-label">{{ trans('labels.description') }}<span class="text-danger"> *

                                    </span></label>

                                <textarea name="description" id="description" class="form-control" rows="5"
                                    placeholder="{{ trans('labels.description') }}" required></textarea>



                            </div>



                        </div>

                        <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">

                            <a href="{{ URL::to('admin/how_it_works') }}"
                                class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>

                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_how_it_works', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            function toggleAiButton() {
                var titleVal = $('#title').val();

                if (titleVal && titleVal.trim() !== '') {
                    $('#ai_btn').removeClass('d-none');
                } else {
                    $('#ai_btn').addClass('d-none');
                }
            }

            toggleAiButton();

            $(document).on('input keyup change', '#title', function() {
                toggleAiButton();
            });

        });

        function ai_generate() {
            let btn = $('#ai_btn');
            let originalHtml = btn.html();
            let title = $('#title').val();

            // 1. Loader Start: Disable button and show spinner
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ url('admin/how_it_works/ai_how_it_works_generate') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title
                },
                success: function(response) {
                    if (response.success) {
                        $('#description').val(response.how_it_works_description);

                        toastr.success("{{ trans('labels.how_it_works_content_generated_successfully') }}");
                    } else {
                        // Show specific error from backend (Invalid Key, 503, etc.)
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    // Network or Request errors
                    toastr.error(
                        "{{ trans('labels.request_failed_please_check_your_internet_connection_or_API_setup') }}"
                    );
                },
                complete: function() {
                    // 2. Loader Stop: Reset button state whether success or error
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        }
    </script>
@endsection
