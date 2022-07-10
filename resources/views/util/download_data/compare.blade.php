<table class="table table-bordered table-striped table-vcenter table-dark mb-0">
    <thead>
        <tr>
            @foreach ($headers as $key => $header)
                @if ($key == "week_name" || in_array($key, $compare_list))
                    <th class="text-center"><strong>{{ $header }}</strong></th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $value)
        <tr>
            <td class="text-center">
                {{ $value->week_name }}
            </td>
            @if (in_array("revenue", $compare_list))
            <td class="text-center">
                $ {{ number_format($value->revenue, 2) }}
            </td>
            @endif
            @if (in_array("miles-total", $compare_list))
            <td class="text-center">
                {{ number_format($value->miles, 2) }} mi.
            </td>
            @endif
            @if (in_array("fuelcost-total", $compare_list))
            <td class="text-center">
                $ {{ number_format($value->fuel_cost, 2) }}
            </td>
            @endif
        </tr>
        @endforeach
        @if (count($values) == 0)
            <tr>
                <td class="text-left">No historical data!</td>
            </tr>
        @endif
    </tbody>
</table>