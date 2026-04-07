<table class="table">
    <thead>
        <tr class="text-capitalize fw-500 fs-15">
            <td>{{ trans('labels.requested_domain') }}</td>
            <td>{{ trans('labels.current_domain') }}</td>
            <td>{{ trans('labels.status') }}</td>
        </tr>
    </thead>
    <tbody>
        <tr class="border">
            <td>{{ empty(@$domain->requested_domain) ? '-' : @$domain->requested_domain }}</td>
            <td>{{ empty(@$domain->current_domain) ? '-' : @$domain->current_domain }}</td>
            <td class="{{ @$domain->status == 1 ? 'text-warning' : 'text-success' }}">
                @if (@$domain->status == 1)
                    {{ trans('labels.pending') }}
                @elseif(@$domain->status == 2)
                    {{ trans('labels.connected') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </tbody>
</table>
