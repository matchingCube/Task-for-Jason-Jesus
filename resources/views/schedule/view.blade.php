<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
    .badge-violet {
        color: #fff;
        background-color: #7F00FF;
    }
</style>
<div class="hero-static d-flex align-items-center">
    <div class="w-100">
        <div class="bg-white">
            <div class="content content-full">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                        <div class="text-center mb-5">
                            <p class="mb-2">
                                <i class="fa fa-2x fa-calendar text-primary"></i>
                            </p>
                            <h1 class="h3 mb-1 font-w600 text-uppercase">
                                Weekly Schedule For Driver
                            </h1>
                        </div>
                        @if (empty($schedule))
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-0"><strong>Sorry, we can't find the schedule data you required.</strong></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Schedule List
                            </a>
                        </div>
                        @else
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Schedule List
                            </a>
                        </div>
                        <ul class="list-group push">
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Year:
                                <span class="font-w600"><strong>{{ $schedule->year_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Week:
                                <span class="font-w600"><strong>{{ $schedule->week_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Date:
                                <span class="font-w600"><strong>{{ $schedule->from_date }} - {{ $schedule->to_date }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver Name:
                                <span class="font-w600"><strong>{{ $schedule->driver_name }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver ID:
                                <span class="badge badge-success">{{ $schedule->driver_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver Phone #:
                                <span class="badge badge-pill badge-violet">{{ '+' . $schedule->driver_phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                TCheck #:
                                <span class="badge badge-info">{{ $schedule->tcheck }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Spare Unit (if needed):
                                <span class="font-w600">{{ $schedule->spare_unit }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                <span class="col-4 px-0 text-primary text-left">Day</span>
                                <span class="col-4 px-0 text-primary text-center">Tractor ID</span>
                                <span class="col-4 px-0 text-primary text-right">Start Time</span>
                            </li>
                            @foreach ($weekly as $w)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="col-4 px-0 text-left">{{ $w->day }}</span>
                                <span class="col-4 px-0 text-center">{{ $w->tractor_id }}</span>
                                @if ($w->start_time == 'OFF')
                                    <span class="badge badge-dark">{{ $w->start_time }}</span>
                                @else
                                    <span class="col-4 px-0 text-right font-w600">{{ $w->start_time }}</span>
                                @endif
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Road Side Service:
                                <span class="font-w600 text-primary">{{ $schedule->fleet_net }}</span>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
