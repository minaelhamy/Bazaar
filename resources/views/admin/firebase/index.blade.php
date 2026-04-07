@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
    $user = App\Models\User::where('id', $vendor_id)->first();
@endphp
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark fs-4">{{ trans('labels.firebase_notification') }}</h5>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            @if (Auth::user()->type == 1 || Auth::user()->type == 2)
                <form action="{{ URL::to('admin/notification/savekey') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0 mb-3 p-3 box-shadow">
                        <div class="row">
                            @if (Auth::user()->type == 1)
                                @if (App\Models\SystemAddons::where('unique_identifier', 'vendor_app')->first() != null &&
                                        App\Models\SystemAddons::where('unique_identifier', 'vendor_app')->first()->activated == 1)
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.firebase_server_key') }}</label>
                                        @if (env('Environment') == 'sendbox')
                                            <span class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                        @endif
                                        <input type="text" class="form-control" name="firebase_server_key"
                                            value="{{ @$settingdata->firebase }}"
                                            placeholder="{{ trans('labels.firebase_server_key') }}" required>
                                    </div>
                                @endif
                            @endif
                            @if (Auth::user()->type == 2)
                                @if (App\Models\SystemAddons::where('unique_identifier', 'user_app')->first() != null &&
                                        App\Models\SystemAddons::where('unique_identifier', 'user_app')->first()->activated == 1)
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.firebase_server_key') }}</label>
                                        @if (env('Environment') == 'sendbox')
                                            <span class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                        @endif
                                        <input type="text" class="form-control" name="firebase_server_key"
                                            value="{{ @$settingdata->firebase }}"
                                            placeholder="{{ trans('labels.firebase_server_key') }}" required>
                                    </div>
                                @endif
                            @endif

                            <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <button
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                    class="btn btn-primary">{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
            <div class="card border-0 mb-3 box-shadow">
                <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                    <a href="{{ URL::to('admin/notification/add') }}"
                        class="btn btn-secondary mx-3 mt-3 {{ Auth::user()->type == 4 ? (helper::check_access('role_firebase_notification', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                        <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">
                            <thead>
                                <tr class="text-capitalize fs-15 fw-500">

                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.title') }}</td>
                                    <td>{{ trans('labels.sub_title') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($firebasecontent as $content)
                                    <tr class="fs-7 align-middle">

                                        <td>@php

                                            echo $i++;

                                        @endphp</td>

                                        <td>{{ $content->title }}</td>
                                        <td>{{ $content->sub_title }}</td>
                                        <td>{{ helper::date_format($content->created_at, $content->vendor_id) }}<br>
                                            {{ helper::time_format($content->created_at, $content->vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($content->updated_at, $content->vendor_id) }}<br>
                                            {{ helper::time_format($content->updated_at, $content->vendor_id) }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a onclick="statusupdate('{{ URL::to('/admin/notification/resend-' . $content->id) }}')"
                                                    tooltip="{{ trans('labels.resend') }}"
                                                    class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_firebase_notification', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                    <i class="fa-regular fa-reply-clock"></i>
                                                </a>
                                                <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('/admin/notification/delete-' . $content->id) }}')" @endif
                                                    class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_firebase_notification', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                    <i class="fa-regular fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
