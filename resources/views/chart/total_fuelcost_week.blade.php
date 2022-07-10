<x-app-layout>
<style type="text/css">
    #container {
        height: 500px; 
    }

    .highcharts-figure, .highcharts-data-table table {
        min-width: 310px; 
        max-width: 1000px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }
    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }
    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }
    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
        padding: 0.5em;
    }
    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }
    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-chart-bar text-primary"></i>
                </span>
                <span class="">Total Fuel Cost Per Week</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-content">
                    <form class="js-validation" action="" method="POST" id="date-form">
                        @csrf
                        <div class="row push">
                            <div class="col-md-1"></div>
                            <div class="col-md-2 form-group text-right">
                                <label class="push-t-def">Year<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-2 form-group">
                                <select class="form-control" id="selected-year" name="selected-year" required>
                                    @for ($i = 2019; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" @if ($i == $year_num) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 form-group text-right">
                                <label class="push-t-def">Week # <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-2 form-group">
                                <select class="form-control" id="selected-week" name="selected-week" required>
                                    @for ($i = 1; $i <= 52; $i++)
                                        <option value="{{ $i }}" @if ($i == $week_num) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-primary" id="form-submit">View Chart</button>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </form>
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description">
                        </p>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/highchart-total-fuelcost-week.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        var data = {!! $data !!};
        // Set up the chart
        Highcharts.chart('container', {
            chart: {
                type: 'columnpyramid'
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Total Fuel Cost Per Week'
            },
            plotOptions: {
                columnpyramid: {
                    dataLabels: {
                        enabled: true,
                        format: '$ {y}',
                        y: -10
                    },
                    enableMouseTracking: true
                }
            },
            xAxis: {
                crosshair: true,
                labels: {
                    style: {
                        fontSize: '14px'
                    }
                },
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cost ($)'
                }
            },
            tooltip: {
                valuePrefix: '$ '
            },
            series: [{
                name: 'Fuel Cost',
                colorByPoint: true,
                data: data,
                showInLegend: false
            }]
        });
    });
});    
</script>
</x-app-layout>