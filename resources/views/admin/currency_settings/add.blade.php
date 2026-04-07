@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.add_new') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a
                        href="{{ URL::to('admin/currency-settings') }}" class="color-changer">{{ trans('labels.currency-settings') }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('admin/currency-settings/store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="hidden" name="name" id="currency_name">
                                <label for="code" class="col-form-label">{{ trans('labels.code') }} <span
                                        class="text-danger"> *
                                    </span></label>
                                <select name="code" class="form-select code-dropdown" id="code" required>
                                    <option value="" selected>{{ trans('labels.select') }}</option>
                                    @foreach ($currencys as $currency)
                                        <option
                                            value="{{ $currency->code }}"{{ old('code') == $currency->code ? 'selected' : '' }}
                                            data-currency-name="{{ $currency->currency }}">{{ $currency->currency }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span> <br>
                                @enderror
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="currency_symbol" class="col-form-label">{{ trans('labels.currency_symbol') }}
                                    <span class="text-danger"> *
                                    </span></label>
                                <select name="currency_symbol" class="form-select currency_symbol-dropdown"
                                    id="currency_symbol" required>
                                    <option value="" selected>{{ trans('labels.select') }}</option>
                                    @foreach ($currencys as $currency)
                                        <option
                                            value=" {{ $currency->currency_symbol }}"{{ old('currency_symbol') == $currency->currency_symbol ? 'selected' : '' }}>
                                            {{ $currency->currency_symbol }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.exchange_rate') }}<span class="text-danger">
                                        *
                                    </span></label>
                                <input type="text" class="form-control" name="exchange_rate" value="{{ old('name') }}"
                                    placeholder="{{ trans('labels.exchange_rate') }}" required>

                            </div>
                            <div class="form-group col-sm-3">
                                <p class="form-label">{{ trans('labels.currency_position') }}
                                </p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="currency_position" id="radio" value="1" checked
                                        {{ @$getcurrency->currency_space == '1' ? 'checked' : '' }} />
                                    <label for="radio" class="form-check-label">{{ trans('labels.left') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="currency_position" id="radio1" value="2"
                                        {{ @$getcurrency->currency_space == '2' ? 'checked' : '' }} />
                                    <label for="radio1" class="form-check-label">{{ trans('labels.right') }}</label>
                                </div>

                            </div>
                            <div class="col-md-3 form-group">
                                <p class="form-label">
                                    {{ trans('labels.currency_space') }}
                                </p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="currency_space" id="currency_space" value="1"
                                        {{ @$getcurrency->currency_space == '1' ? 'checked' : '' }} />
                                    <label for="currency_space" class="form-check-label">{{ trans('labels.yes') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="currency_space" id="currency_space1" value="2" checked
                                        {{ @$getcurrency->currency_space == '2' ? 'checked' : '' }} />
                                    <label for="currency_space1" class="form-check-label">{{ trans('labels.no') }}</label>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.currency_formate') }}<span class="text-danger">
                                        * </span></label>
                                <input type="text" class="form-control" name="currency_formate" value=""
                                    placeholder="{{ trans('labels.currency_formate') }}" required>
                                @error('currency_formate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.decimal_separator') }}</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="decimal_separator" id="dot" value="1" checked
                                        {{ @$getcurrency->decimal_separator == '1' ? 'checked' : '' }} />
                                    <label for="dot" class="form-check-label">{{ trans('labels.dot') }}
                                        (.)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="decimal_separator" id="comma" value="2"
                                        {{ @$getcurrency->decimal_separator == '2' ? 'checked' : '' }} />
                                    <label for="comma" class="form-check-label">{{ trans('labels.comma') }}
                                        (,)</label>
                                </div>
                            </div>
                            <div class="form-group text-{{ session()->get('direction') == '2' ? 'start' : 'end' }} m-0">
                                <a href="{{ URL::to('admin/currency-settings') }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_currency-settings', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
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
            "user strict";
            $(".code-dropdown").change(function() {
                $('#currency_name').val($(this).find(':selected').attr('data-currency-name'));
            }).change();
        });
    </script>
@endsection
