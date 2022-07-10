<x-app-layout>
<style type="text/css">
    #container {
        height: 500px;
    }

    .highcharts-figure, .highcharts-data-table table {
        min-width: 310px;
        max-width: 1000px;
        margin: 0 auto;
    }

    #sliders td input[type=range] {
        display: inline;
    }
    #sliders td {
        padding-right: 1em;
        white-space: nowrap;
    }
</style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-chart-bar text-primary"></i>
                </span>
                <span class="">Total Miles Per Week</span>
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
<script src="{{ mix('js/highchart-total-miles-week.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        var categories = {!! $categories !!};
        var values = {!! $values !!};
        values = values.map(el=>parseInt(el));

        // Set up the chart
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column',
                width: 1000
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Total Miles Per Week'
            },
            subtitle: {
                text: ''
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{y} miles',
                        y: -10
                    },
                    depth: 100
                }
            },
            xAxis: {
                categories: categories,
                labels: {
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Driven distance (miles)'
                }
            },
            series: [{
                data: values,
                showInLegend: false,
                name: 'Miles'
            }]
        });
    });
});    
</script>
</x-app-layout>