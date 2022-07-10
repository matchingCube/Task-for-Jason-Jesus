<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-list text-primary"></i>
                </span>
                <span class="">Historical Data</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title">Historical Data By Key Metrics</h3>
                </div>
                <div class="block-content">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{!! session('status') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <div class="container">
                        <p class="text-right">
                            <button type="button" class="btn btn-danger close-window">
                                <i class="fa fa-time text-mute"></i> Close
                            </button>
                        </p>
                        @if (isset($view_names))
                        @foreach ($view_names as $view_name)
                            @switch($view_name)
                                @case('compare')
                                    <h4>
                                        <span class="text-danger">Total Revenue/Miles/Fuel Cost: </span>
                                        <span class="text-success">WK-{{ $search->from_week_num }}, {{ $search->from_year_num }} ~ WK-{{ $search->to_week_num }}, {{ $search->to_year_num }}</span>
                                    </h4>
                                    <div class="table-responsive mb-3">
                                    @include('util.download_data.compare', ['headers' => $data[$view_name]->header, 'values' => $data[$view_name]->data, 'compare_list' => $compare_list])
                                    </div>
                                    @break

                                @case('miles-driver')
                                    <h4>
                                        <span class="text-danger">Miles By Driver: </span>
                                        <span class="text-success">WK-{{ $search->from_week_num }}, {{ $search->from_year_num }} ~ WK-{{ $search->to_week_num }}, {{ $search->to_year_num }}</span>
                                    </h4>
                                    <div class="table-responsive mb-3">
                                    @include('util.download_data.mile_driver', ['headers' => $data[$view_name]->header, 'values' => $data[$view_name]->data])
                                    </div>
                                    @break

                                @case('miles-vehicle')
                                    <h4>
                                        <span class="text-danger">Miles By Vehicle: </span>
                                        <span class="text-success">WK-{{ $search->from_week_num }}, {{ $search->from_year_num }} ~ WK-{{ $search->to_week_num }}, {{ $search->to_year_num }}</span>
                                    </h4>
                                    <div class="table-responsive mb-3">
                                    @include('util.download_data.mile_vehicle', ['headers' => $data[$view_name]->header, 'values' => $data[$view_name]->data])
                                    </div>
                                    @break

                                @case('trips-driver')
                                    <h4>
                                        <span class="text-danger">Trips By Driver: </span>
                                        <span class="text-success">WK-{{ $search->from_week_num }}, {{ $search->from_year_num }} ~ WK-{{ $search->to_week_num }}, {{ $search->to_year_num }}</span>
                                    </h4>
                                    <div class="table-responsive mb-3">
                                    @include('util.download_data.trips_driver', ['headers' => $data[$view_name]->header, 'values' => $data[$view_name]->data])
                                    </div>
                                    @break

                                @case('mpg-vehicle')
                                    <h4>
                                        <span class="text-danger">MPG By Vehicle: </span>
                                        <span class="text-success">WK-{{ $search->from_week_num }}, {{ $search->from_year_num }} ~ WK-{{ $search->to_week_num }}, {{ $search->to_year_num }}</span>
                                    </h4>
                                    <div class="table-responsive mb-3">
                                    @include('util.download_data.mpg_vehicle', ['headers' => $data[$view_name]->header, 'values' => $data[$view_name]->data])
                                    </div>
                                    @break

                                @default
                                    InActive
                            @endswitch
                        @endforeach
                        @else
                            <div class="alert alert-danger alert-dismissable" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> No historical data !</p>
                            </div>
                        @endif
                        <p class="text-right">
                            <button type="button" class="btn btn-danger close-window">
                                <i class="fa fa-time text-mute"></i> Close
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button.close-window').click(function() {
            if (confirm("Do you want to close data view?")) {
                window.close();
            }
        });
    });
});
</script>
</x-app-layout>
