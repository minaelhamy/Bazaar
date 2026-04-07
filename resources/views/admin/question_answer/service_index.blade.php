@extends('admin.layout.default')

@section('content')
    @include('admin.breadcrumb.breadcrumb')

    @php

        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

    @endphp

    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 mb-3 box-shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">

                            <thead>

                                <tr class="text-capitalize fw-500 fs-15">

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

                                @foreach ($service_ques_ans as $item)
                                    @if ($item->product_id == null)
                                        <tr class="fs-7 align-middle">

                                            <td>@php

                                                echo $i++;

                                            @endphp</td>



                                            <td>{{ @$item->service->name }}</td>
                                            <td>{{ $item->question }}</td>

                                            <td>{{ $item->answer }}</td>

                                            <td>{{ helper::date_formate($item->created_at, $vendor_id) }}<br>

                                                {{ helper::time_format($item->created_at, $vendor_id) }}

                                            </td>

                                            <td>{{ helper::date_formate($item->updated_at, $vendor_id) }}<br>

                                                {{ helper::time_format($item->updated_at, $vendor_id) }}

                                            </td>


                                            <td>
                                                <div class="d-flex flex-wrap gap-2">

                                                    <a href="javascript:void(0)" class="btn btn-info hov btn-sm"
                                                        tooltip="{{ trans('labels.edit') }}"
                                                        onclick="answer('{{ $item->id }}','{{ $item->question }}','{{ @$item->answer }}')">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>

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
    <div class="modal fade" id="qustions_answer" tabindex="-1" aria-labelledby="qustions_answerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h1 class="modal-title fs-5 fw-600 m-0 color-changer" id="qustions_answer">{{ trans('labels.answer') }}
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
                                <textarea class="form-control " id="answer" name="answer" placeholder="Your Questions" rows="3" required=""></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger fs-7  fw-500" data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
                        <button type="submit" class="btn btn-primary fs-7 fw-500">{{ trans('labels.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function answer(id, question,answer) {

            $('#question').html(question);
            $('#id').val(id);
             $('#answer').val(answer);
            $("#qustions_answer").modal('show');

        }
    </script>
@endsection
