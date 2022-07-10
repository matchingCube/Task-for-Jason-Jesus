<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Payroll</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Company Payroll</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Search Form</h3>
        </div>
        <div class="block-content">
            <form action="/payroll" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label for="year-num">Year <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="year-num" name="year-num" required>
                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                    <option value="{{ $i }}" @if ($i == $year_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label for="week-num">Week <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="week-num" name="week-num" required>
                                @for ($i = 1; $i <= 52; $i++)
                                    <option value="{{ $i }}" @if ($i == $week_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label for="work-status">Work Status <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="work-status" name="work-status" required>
                                <option value="1" @if ($work_status == 1) selected @endif>Working now</option>
                                <option value="0" @if ($work_status == 0) selected @endif>Not longer working</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="view-payroll">&nbsp;</label>
                            <button class="form-control btn btn-dark ml-auto mr-3" id="view-payroll" name="view-payroll">View Payroll</button>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">
                            <strong><i class="far fa-user"></i></strong>
                        </th>
                        <th class="text-center"><strong>Driver Name</strong></th>
                        <th class="d-none d-md-table-cell text-center" style="width: 10%;"><strong>Mi. For FR</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Price For FR</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Price Per Mile</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Mi. excl. FR</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Price excl. FR</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Total Miles</strong></th>
                        <th class="text-center"><strong>Total Price</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($payrolls) && count($payrolls) > 0)
                    @foreach ($payrolls as $idx => $p)
                    <tr>
                        <td class="text-center">
                            {{ $idx + 1 }}
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            <a href="/payroll/get/{{ $p->id }}/{{ $year_num }}/{{ $week_num }}">{{ $p->driver_name }}</a>
                            @if ($p->work_status == 0)
                                <span class="badge badge-pill badge-danger">No longer working</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            {{ number_format($p->fr_miles, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            $ {{ number_format($p->fr_price, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            <span class="badge badge-primary">&nbsp;$ {{ $p->price_per_mile }}&nbsp;</strong>
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            {{ number_format($p->other_miles, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            $ {{ number_format($p->other_price, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell text-primary text-right">
                            {{ number_format($p->total_miles, 2) }}
                        </td>
                        <td class="text-right text-warning">
                            <strong>$ {{ number_format($p->total_price, 2) }}</strong>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" class="font-w600 font-size-sm text-center">No Drivers</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <p class="text-right">
                <strong class="text-success">Total Payroll Amount: <span class="text-danger">$ {{ number_format($total_price, 2) }}</span></strong>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        moment.updateLocale("en", { week: {
            dow: 6, // First day of week is Saturday
            doy: 12 // First week of year must contain 1 January (7 + 6 - 1)
        }});
        var dateformat = "YYYY/MM/DD";
        function getWeekDaysByWeekNumber(year, weeknumber)
        {
            var date = moment().year(year).isoWeek(weeknumber||1).startOf("week"), weeklength=7, result=[];
            while(weeklength--)
            {
                result.push(date.format(dateformat));
                date.add(1,"day")
            }
            return result;
        }

        console.log(getWeekDaysByWeekNumber(2020, 1))
        console.log(getWeekDaysByWeekNumber(2021, 1))
        console.log(getWeekDaysByWeekNumber(2022, 1))
    });
});
</script>
</x-app-layout>
