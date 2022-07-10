<x-app-layout>

<div class="hero-static d-flex align-items-center">
    <div class="w-100">
        <div class="bg-white">
            <div class="content content-full">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                        <div class="text-center mb-5">
                            <p class="mb-2">
                                <i class="fa fa-2x fa-money-check text-primary"></i>
                            </p>
                            <h1 class="h3 mb-1 font-w600 text-uppercase">
                                Payroll For Driver
                            </h1>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Payroll List
                            </a>
                            @can('manage-payroll-setting')
                            <a class="btn btn-lg btn-alt-success" href="/payroll/fixed-rates">
                                <i class="fa fa-cogs"></i> <span class="d-none d-sm-inline-block ml-1">Fixed Rates</span>
                            </a>
                            <a class="btn btn-lg btn-alt-success" href="/payroll/rate/{{ $payroll->id }}">
                                <i class="fa fa-cog"></i> <span class="d-none d-sm-inline-block ml-1">Price Per Mile</span>
                            </a>
                            @endcan
                        </div>
                        @can('manage-payroll-setting')
                        <hr>
                        <div class="alert alert-info" role="alert">
                            <p class="mb-0"><strong><i class="fa fa-info-circle"></i></strong> You can set the fixed rates and price per mile for driver.</p>
                        </div>
                        @endcan
                        <ul class="list-group push">
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                YEAR:
                                <span class="font-w600"><strong>{{ $year_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                WEEK:
                                <span class="font-w600"><strong>{{ $week_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver ID:
                                <span class="badge badge-success">{{ $payroll->driver_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver Name:
                                <span class="font-w600"><strong>{{ $payroll->driver_name }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Work Status:
                                @if ($payroll->work_status == 1)
                                <span class="badge badge-pill badge-success">{{ 'Working Now' }}</span>
                                @else
                                <span class="badge badge-pill badge-danger">{{ 'No longer working' }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Miles For Fixed Rate:
                                <span class="font-w600">{{ number_format($payroll->fr_miles, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Price For Fixed Rate:
                                <span class="font-w600 text-warning"><i class="fa fa-dollar-sign"></i> <em>{{ number_format($payroll->fr_price, 2) }}</em></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                Price Per Mile:
                                <span class="badge badge-pill badge-primary"><i class="fa fa-dollar-sign"></i> {{ number_format($payroll->price_per_mile, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Miles For Price Per Mile:
                                <span class="font-w600">{{ number_format($payroll->other_miles, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Price For Price Per Mile:
                                <span class="font-w600 text-warning"><i class="fa fa-dollar-sign"></i> <em>{{ number_format($payroll->other_price, 2) }}</em></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                Total Miles:
                                <span class="font-w600 text-danger">{{ number_format($payroll->total_miles, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                <strong>Total Payroll:</strong>
                                <span class="font-w600 text-primary"><strong><i class="fa fa-dollar-sign"></i> {{ number_format($payroll->total_price, 2) }}</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
