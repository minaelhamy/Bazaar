@extends('admin.layout.default')

@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
@section('content')
    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
        <div class="row">
            <div class="alert alert-warning" role="alert">
                <p>Don't use double quote (") and back slash (\) in the language fields.</p>
            </div>
        </div>
    @endif

    <div class="row settings">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-capitalize fw-600 color-changer text-dark fs-4">{{ trans('labels.language-settings') }}</h5>
            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                @if (@helper::checkaddons('language'))
                    <div class="d-inline-flex">
                        <a href="{{ URL::to('/admin/language-settings/add') }}"
                            class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                            <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}</a>
                    </div>
                @endif
            @endif

        </div>
        @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
            <div class="col-xl-3 mb-3">
                <div class="card card-sticky-top h-auto border-0">
                    <ul class="list-group list-options">
                        @foreach ($getlanguages as $data)
                            <a href="{{ URL::to('admin/language-settings/' . $data->code) }}"
                                class="list-group-item basicinfo p-3 list-item-primary @if ($currantLang->code == $data->code) active @endif"
                                aria-current="true">
                                <div class="d-flex justify-content-between align-item-center">
                                    {{ $data->name }}
                                    <div class="d-flex align-item-center">
                                        <i class="fa-regular fa-angle-right ps-2"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="dropdown">
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-info hov {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                tooltip="{{ trans('labels.edit') }}"
                                href="{{ URL::to('/admin/language-settings/language/edit-' . $currantLang->id) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            @if (Strtolower($currantLang->name) != 'english')
                                <a class="btn btn-danger hov {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}"
                                    tooltip="{{ trans('labels.delete') }}"
                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/layout/delete-' . $currantLang->id . '/1') }}')" @endif>
                                    <i class="fa-regular fa-trash"></i>
                                </a>
                            @endif
                            @if ($currantLang->is_available == '1')
                                @if (helper::available_language('')->count() > 1)
                                    <a tooltip="{{ trans('labels.active') }}"
                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/status-' . $currantLang->id . '/2') }}')" @endif
                                        class="btn btn-success hov {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endif
                            @else
                                <a tooltip="{{ trans('labels.inactive') }}"
                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/status-' . $currantLang->id . '/1') }}')" @endif
                                    class="btn btn-danger hov {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                    <i class="fas fa-close"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (helper::available_language('')->count() > 1)
                        <div class="d-flex gap-2 align-items-center">
                            <label for="language_default"
                                class="form-label col-auto m-0">{{ trans('labels.default_language') }} :</label>
                            <select name="language_default" class="form-select" id="language_default"
                                @if (env('Environment') == 'sendbox') onclick="myFunction()" @else
                                onchange="location =  $('option:selected',this).data('value');" @endif>
                                <option value="" data-value="{{ URL::to('admin/language-settings?lang=') }}">
                                    {{ trans('labels.select') }}</option>
                                @foreach (helper::available_language('') as $item)
                                    <option value="item"
                                        {{ $item->code == helper::appdata('')->default_language ? 'selected' : '' }}
                                        @if (Request()->code != null && Request()->code != '') data-value="{{ URL::to('admin/language-settings/' . Request()->code . '?lang=' . $item->code) }}"> @else data-value="{{ URL::to('admin/language-settings/?lang=' . $item->code) }}"> @endif
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="labels-tab" data-bs-toggle="tab" data-bs-target="#labels"
                            type="button" role="tab" aria-controls="labels" aria-selected="true">Labels</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="message-tab" data-bs-toggle="tab" data-bs-target="#message"
                            type="button" role="tab" aria-controls="message" aria-selected="false">Messages</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="landing-tab" data-bs-toggle="tab" data-bs-target="#landing"
                            type="button" role="tab" aria-controls="landing" aria-selected="false">Landing</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="labels" role="tabpanel" aria-labelledby="labels-tab">
                        <div class="card border-0 box-shadow">
                            <div class="card-body">
                                <form method="post" action="{{ URL::to('admin/language-settings/update') }}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="currantLang"
                                        value="{{ $currantLang->code }}">
                                    <input type="hidden" class="form-control" name="file" value="label">
                                    <div class="row">
                                        @foreach ($arrLabel as $label => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label line-1 w-100 " for="example3cols1Input">
                                                        {{ $label }}
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        name="label[{{ $label }}]" id="label{{ $label }}"
                                                        onkeyup="validation($(this).val(),this.getAttribute('id'))"
                                                        value="{{ $value }}">
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-12">
                                            <div
                                                class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <div class="d-flex justify-content-end">
                                                    @if (env('Environment') == 'sendbox')
                                                        <button type="button" class="btn btn-raised btn-primary px-sm-4"
                                                            onclick="myFunction()"><i class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }} </button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn-raised btn-primary px-sm-4"><i
                                                                class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }}
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="message" role="tabpanel" aria-labelledby="message-tab">
                        <div class="card border-0 box-shadow">
                            <div class="card-body">
                                <form method="post" action="{{ URL::to('admin/language-settings/update') }}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="currantLang"
                                        value="{{ $currantLang->code }}">
                                    <input type="hidden" class="form-control" name="file" value="message">
                                    <div class="row">
                                        @foreach ($arrMessage as $label => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="example3cols1Input">{{ $label }}
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        name="message[{{ $label }}]"
                                                        id="message{{ $label }}"
                                                        onkeyup="validation($(this).val(),this.getAttribute('id'))"
                                                        value="{{ $value }}">
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-12">
                                            <div
                                                class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <div class="d-flex justify-content-end">
                                                    @if (env('Environment') == 'sendbox')
                                                        <button type="button" class="btn btn-raised btn-primary px-sm-4"
                                                            onclick="myFunction()"><i class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }} </button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn-raised btn-primary px-sm-4"><i
                                                                class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }}
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="landing" role="tabpanel" aria-labelledby="landing-tab">
                        <div class="card border-0 box-shadow">
                            <div class="card-body">
                                <form method="post" action="{{ URL::to('admin/language-settings/update') }}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="currantLang"
                                        value="{{ $currantLang->code }}">
                                    <input type="hidden" class="form-control" name="file" value="landing">
                                    <div class="row">
                                        @foreach ($arrLanding as $label => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="example3cols1Input">{{ $label }}
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        name="landing[{{ $label }}]"
                                                        id="landing{{ $label }}"
                                                        onkeyup="validation($(this).val(),this.getAttribute('id'))"
                                                        value="{{ $value }}">
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-12">
                                            <div
                                                class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <div class="d-flex justify-content-end">
                                                    @if (env('Environment') == 'sendbox')
                                                        <button type="button" class="btn btn-raised btn-primary px-sm-4"
                                                            onclick="myFunction()"><i class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }} </button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn-raised btn-primary px-sm-4"><i
                                                                class="fa fa-check-square-o"></i>
                                                            {{ trans('labels.save') }}
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
            <div class="col-12">
                <div class="card border-0 my-3 box-shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                                <thead>
                                    <tr class="text-capitalize fw-500 fs-15">
                                        <td>{{ trans('labels.srno') }}</td>
                                        <td>{{ trans('labels.language') }}</td>
                                        <td>{{ trans('labels.status') }}</td>
                                        <td>{{ trans('labels.is_default') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach (helper::listoflanguage() as $language)
                                        <tr class="fs-7" data-id="{{ $language->id }}">
                                            <td>@php echo $i++ @endphp</td>
                                            <td>{{ $language->name }}</td>
                                            <td>
                                                @if (in_array($language->code, explode('|', helper::appdata($vendor_id)->languages)))
                                                    <a tooltip="{{ trans('labels.active') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/languagestatus-' . $language->code . '/2') }}')" @endif
                                                        class="btn btn-sm btn-outline-success {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @else
                                                    <a tooltip="{{ trans('labels.inactive') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/languagestatus-' . $language->code . '/1') }}')" @endif
                                                        class="btn btn-sm btn-outline-danger {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-close mx-1"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if (helper::appdata($vendor_id)->default_language == $language->code)
                                                    <a tooltip="{{ trans('labels.active') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/setdefault-' . $language->code . '/2') }}')" @endif
                                                        class="btn btn-sm btn-outline-success {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @else
                                                    <a tooltip="{{ trans('labels.inactive') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/language-settings/setdefault-' . $language->code . '/1') }}')" @endif
                                                        class="btn btn-sm btn-outline-danger {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-close mx-1"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function validation(value, id) {
            if (value.includes('"')) {
                newval = value.replaceAll('"', '');
                $('#' + id).val(newval);
            }
            if (value.includes('\\')) {
                newval = value.replaceAll('\\', '');
                $('#' + id).val(newval);
            }
        }
    </script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/settings.js') }}"></script>
@endsection
