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
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.product_question_answer') }}</h5>

        <div class="d-flex align-items-center" style="gap: 10px;">
        <!-- Bulk Delete Button -->
            @if (@helper::checkaddons('bulk_delete'))
                <button id="bulkDeleteBtn"
                    @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/question_answer/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                    <i class="fa-regular fa-trash"></i>
                </button>
            @endif
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">

                            <thead>

                                <tr class="text-capitalize fw-500 fs-15">
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($product_ques_ans->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif

                                    <td>{{ trans('labels.srno') }}</td>

                                    <td>{{ trans('labels.name') }}</td>

                                    <td>{{ trans('labels.question') }}</td>

                                    <td>{{ trans('labels.answer') }}</td>

                                    <td>{{ trans('labels.created_date') }}</td>

                                    <td>{{ trans('labels.updated_date') }}</td>

                                    <td>{{ trans('labels.action') }}</td>

                                </tr>

                            </thead>

                            <tbody>

                                @php

                                    $i = 1;

                                @endphp

                                @foreach ($product_ques_ans as $item)
                                    @if ($item->service_id == null)
                                        <tr class="fs-7 align-middle">

                                            @if (@helper::checkaddons('bulk_delete'))
                                                <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $item->id }}"></td>
                                            @endif
                                            <td>@php

                                                echo $i++;

                                            @endphp</td>



                                            <td>{{ @$item->product->item_name }}</td>
                                            <td>{{ $item->question }}</td>

                                            <td>{{ $item->answer }}</td>

                                            <td>{{ helper::date_format($item->created_at, $vendor_id) }}<br>

                                                {{ helper::time_format($item->created_at, $vendor_id) }}

                                            </td>

                                            <td>{{ helper::date_format($item->updated_at, $vendor_id) }}<br>

                                                {{ helper::time_format($item->updated_at, $vendor_id) }}

                                            </td>



                                            <td>
                                                <div class="d-flex flex-wrap gap-2">

                                                 
                                                    <button tooltip="{{ trans('labels.edit') }}"
                                                        onclick="answer('{{ $item->id }}','{{ $item->question }}')"
                                                        class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_question_answer', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </button>

                                                    <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/question_answer/delete-' . $item->id) }}')" @endif
                                                        class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_question_answer', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fa-regular fa-trash"></i>
                                                    </a>


                                                </div>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- answer Modal --}}
    <div class="modal fade" id="questions_answer" tabindex="-1" aria-labelledby="questions_answerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h1 class="modal-title fs-5 fw-600 m-0 color-changer" id="questions_answer">
                        {{ trans('labels.answer') }}
                    </h1>
                    <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <form action="{{ URL::to('/admin/product_answer') }}" method="post" class=" mt-3 pt-2">
                    @csrf
                    <input type="hidden" id="id" name="id" value="">
                    <div class="modal-body">

                        <div class="d-flex align-items-center border-bottom pb-2">
                            <div class="col-2">
                                <label for="exampleFormControlTextarea1" class="form-label d-flex ">
                                    {{ trans('labels.question') }}
                                    <div aria-hidden="true" class="text-danger">*</div>
                                </label>
                            </div>
                            <div class="col-10">
                                <label for="question" class="form-label d-flex " id="question"></label>
                            </div>
                        </div>

                        <div class="d-flex align-items-center pt-2">
                            <div class="col-2">
                                <label for="exampleFormControlTextarea1" class="form-label d-flex ">
                                    {{ trans('labels.answer') }}
                                    <div aria-hidden="true" class="text-danger">*</div>
                                </label>
                            </div>
                            <div class="col-10  ">
                                <textarea class="form-control " id="answer" name="answer" placeholder="{{ trans('labels.your_answer') }}"
                                    rows="3" required=""></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger fs-7  fw-500"
                            data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
                        <button type="submit" class="btn btn-primary fs-7 fw-500">{{ trans('labels.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function answer(id, question) {

            $('#question').html(question);
            $('#id').val(id);
            $("#questions_answer").modal('show');
        }
    </script>
@endsection
