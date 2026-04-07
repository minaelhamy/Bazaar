@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">
        {{ trans('labels.whatsapp_settings') }}</h5>
    <div class="row justify-content-center">
        <div class="col-12 my-3">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="business-api-tab" data-bs-toggle="tab" data-bs-target="#business-api"
                        type="button" role="tab" aria-controls="message"
                        aria-selected="true">{{ trans('labels.whatsapp_business_api') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="order-tab" data-bs-toggle="tab" data-bs-target="#order" type="button"
                        role="tab" aria-controls="labels"
                        aria-selected="false">{{ trans('labels.whatsapp_order_message') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="order-status-message-tab" data-bs-toggle="tab"
                        data-bs-target="#order-status-message" type="button" role="tab" aria-controls="message"
                        aria-selected="false">{{ trans('labels.order_status_update') }}</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="business-api" role="tabpanel" aria-labelledby="business-api-tab">
                    <div class="card border-0 box-shadow rounded-top-0">
                        <div class="card-body">
                            <div class="form-validation">
                                <form action="{{ URL::to('admin/settings/business_api') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.whatsapp_number') }}
                                                    <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control numbers_only"
                                                    name="whatsapp_number"
                                                    value="{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_number }}"
                                                    placeholder="{{ trans('labels.whatsapp_number') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.message_type') }}
                                                    <span class="text-danger"> * </span></label>
                                                <select class="form-select" name="message_type" required>
                                                    <option value="">{{ trans('labels.select') }}</option>
                                                    <option value="1"
                                                        {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->message_type == '1' ? 'selected' : '' }}>
                                                        {{ trans('labels.automatic_using_api') }}</option>
                                                    <option value="2"
                                                        {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->message_type == '2' ? 'selected' : '' }}>
                                                        {{ trans('labels.manually') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.whatsapp_phone_number_id') }}
                                                    <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="whatsapp_phone_number_id"
                                                    value="{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_phone_number_id }}"
                                                    placeholder="{{ trans('labels.whatsapp_phone_number_id') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.whatsapp_access_token') }}
                                                    <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="whatsapp_access_token"
                                                    value="{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_access_token }}"
                                                    placeholder="{{ trans('labels.whatsapp_access_token') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div
                                            class="mt-3 {{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                            <button
                                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_whatsapp_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="about_update" value="1" @endif>{{ trans('labels.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
                    <div class="card border-0 box-shadow rounded-top-0">
                        <div class="card-body">
                            <div class="form-validation">
                                <form action="{{ URL::to('admin/settings/order_message_update') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.order_variable') }}
                                                </label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.order_no') }} :
                                                                <code>{order_no}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.payment_type') }} :
                                                                <code>{payment_type}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.payment_status') }} :
                                                                <code>{payment_status}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.tips') }} :
                                                                <code>{tips}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.sub_total') }} :
                                                                <code>{sub_total}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.total_tax') }} :
                                                                <code>{total_tax}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.delivery_charge') }} :
                                                                <code>{delivery_charge}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.discount') }} :
                                                                <code>{discount_amount}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.grand_total') }} :
                                                                <code>{grand_total}</code>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.customer_name') }}
                                                                : <code>{customer_name}</code></li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.customer_email') }} :
                                                                <code>{customer_email}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.customer_mobile') }} :
                                                                <code>{customer_mobile}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.address') }}
                                                                :
                                                                <code>{address}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.building') }} :
                                                                <code>{building}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.landmark') }} :
                                                                <code>{landmark}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.pincode') }}
                                                                :
                                                                <code>{postal_code}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.delivery_type') }}
                                                                : <code>{delivery_type}</code></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.notes') }} :
                                                                <code>{notes}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.item_variable') }}
                                                                : <code>{item_variable}</code></li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.time') }} :
                                                                <code>{time}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.date') }} :
                                                                <code>{date}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.store_name') }} :
                                                                <code>{store_name}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.store_url') }} :
                                                                <code>{store_url}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.track_order_url') }} :
                                                                <code>{track_order_url}</code>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.item_variable') }}
                                                </label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.item_name') }} :
                                                                <code>{item_name}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.qty') }} :
                                                                <code>{qty}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.variants') }} :
                                                                <code>{variantsdata}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.item_price') }} :
                                                                <code>{item_price}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.total') }} :
                                                                <code>{total}</code>
                                                            </li>
                                                            <li class="list-group-item px-0 color-changer">
                                                                <input type="text" name="item_message"
                                                                    class="form-control"
                                                                    placeholder="{{ trans('labels.item_message') }}"
                                                                    value="{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->item_message }}"
                                                                    required>
                                                                @error('item_message')
                                                                    <span class="text-danger"
                                                                        id="timezone_error">{{ $message }}</span>
                                                                @enderror
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.whatsapp_messages') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" required="required" name="order_whatsapp_message" cols="50" rows="10">{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->order_whatsapp_message }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-form-label"
                                                    for="">{{ trans('labels.order_created') }}
                                                </label>
                                                <input id="order_created-switch" type="checkbox" class="checkbox-switch"
                                                    name="order_created" value="1"
                                                    {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->order_created == 1 ? 'checked' : '' }}>
                                                <label for="order_created-switch" class="switch">
                                                    <span
                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                            class="switch__circle-inner"></span></span>
                                                    <span
                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                    <span
                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-form-label"
                                                    for="">{{ trans('labels.whatsapp_chat') }}
                                                </label>
                                                <input id="whatsapp_chat_on_off-switch" type="checkbox"
                                                    class="checkbox-switch" name="whatsapp_chat_on_off" value="1"
                                                    {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_chat_on_off == 1 ? 'checked' : '' }}>
                                                <label for="whatsapp_chat_on_off-switch" class="switch">
                                                    <span
                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                            class="switch__circle-inner"></span></span>
                                                    <span
                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                    <span
                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-form-label"
                                                    for="">{{ trans('labels.mobile_view_display') }}
                                                </label>
                                                <input id="whatsapp_mobile_view_on_off-switch" type="checkbox"
                                                    class="checkbox-switch" name="whatsapp_mobile_view_on_off"
                                                    value="1"
                                                    {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_mobile_view_on_off == 1 ? 'checked' : '' }}>
                                                <label for="whatsapp_mobile_view_on_off-switch" class="switch">
                                                    <span
                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                            class="switch__circle-inner"></span></span>
                                                    <span
                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                    <span
                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-form-label"
                                                    for="">{{ trans('labels.whatsapp_chat_position') }}
                                                </label>
                                                <div class="d-flex">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input me-0" type="radio"
                                                            name="whatsapp_chat_position" id="inlineRadio1"
                                                            value="1"
                                                            {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_chat_position == 1 ? 'checked' : '' }}
                                                            checked>
                                                        <label class="form-check-label"
                                                            for="inlineRadio1">{{ trans('labels.left') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input me-0" type="radio"
                                                            name="whatsapp_chat_position" id="inlineRadio2"
                                                            value="2"
                                                            {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->whatsapp_chat_position == 2 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="inlineRadio2">{{ trans('labels.right') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="mt-3 {{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                            <button
                                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_whatsapp_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="order-status-message" role="tabpanel"
                    aria-labelledby="order-status-message-tab">
                    <div class="card border-0 box-shadow rounded-top-0">
                        <div class="card-body">
                            <div class="form-validation">
                                <form action="{{ URL::to('admin/settings/status_message') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="alert alert-danger">
                                        <i class="fa-regular fa-circle-exclamation"></i> Order status message
                                        will only work if your message type settings are automatic using
                                        whatsapp business API
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.order_variable') }}
                                                </label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.order_no') }} :
                                                                <code>{order_no}</code>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.customer_name') }}
                                                                : <code>{customer_name}</code></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.track_order_url') }} :
                                                                <code>{track_order_url}</code>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item px-0 color-changer">
                                                                {{ trans('labels.status') }}
                                                                : <code>{status}</code></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.status_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" required="required" name="order_status_message" cols="50" rows="10">{{ @whatsapp_helper::whatsapp_message_config($vendor_id)->order_status_message }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-form-label"
                                                    for="">{{ trans('labels.status_change') }}
                                                </label>
                                                <input id="status_change-switch" type="checkbox" class="checkbox-switch"
                                                    name="status_change" value="1"
                                                    {{ @whatsapp_helper::whatsapp_message_config($vendor_id)->status_change == 1 ? 'checked' : '' }}>
                                                <label for="status_change-switch" class="switch">
                                                    <span
                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                            class="switch__circle-inner"></span></span>
                                                    <span
                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                    <span
                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-3 {{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                        <button
                                            class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_whatsapp_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
