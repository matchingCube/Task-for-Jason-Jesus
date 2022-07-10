<x-app-layout>
<style type="text/css">
    .highcharts-figure, .highcharts-data-table table {
        min-width: 360px; 
        max-width: 1000px;
        margin: 1em auto;
    }
    #container {
        height: 500px;
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
                    <i class="fa fa-chart-line text-primary"></i>
                </span>
                <span class="">MPG Per Week By Vehicle</span>
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
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/highchart-mpg-week-vehicle.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    var miles_data = {!! $miles_data !!};
    var fuels_data = {!! $fuels_data !!};
    var cats = [];
    var vehicles = [];
    var data = [];
    var limit = {!! $limit !!};
    var year_num = {!! $year_num !!};
    var week_num = {!! $week_num !!};
    
    function initialData() {
        for (let i = week_num - limit + 1; i <= week_num; i++) {
            cats.push('WK-' + i + ', ' + year_num);
        }

        $(miles_data).each(function() {
            let vehicle = this.vehicle;
            if (vehicles.indexOf(vehicle) == -1) {
                vehicles.push(vehicle);
            }
        });
        $(fuels_data).each(function() {
            let cat = this.week;
            let vehicle = this.vehicle;
            if (cats.indexOf(cat) == -1) {
                cats.push(cat);
            }
            if (vehicles.indexOf(vehicle) == -1) {
                vehicles.push(vehicle);
            }
        });

        for (let i = 0; i < vehicles.length; i++) {
            let miles = [];
            for (let j = 0; j < cats.length; j++) {
                let flag = false;
                for (let k = 0; k < miles_data.length; k++) {
                    if (miles_data[k].vehicle == vehicles[i] && miles_data[k].week == cats[j]) {
                        miles[j] = miles_data[k].miles;
                        flag = true;
                        break;
                    }
                }
                if (flag == false) {
                    miles[j] = 0;
                }
            }
            
            let fuels = [];
            for (let j = 0; j < cats.length; j++) {
                let flag = false;
                for (let k = 0; k < fuels_data.length; k++) {
                    if (fuels_data[k].vehicle == vehicles[i] && fuels_data[k].week == cats[j]) {
                        fuels[j] = fuels_data[k].fuel_qty;
                        flag = true;
                        break;
                    }
                }
                if (flag == false) {
                    fuels[j] = 0;
                }
            }
            
            let mpgs = [];
            for (let j = 0; j < miles.length; j++) {
                if (fuels[j] == 0) {
                    mpgs[j] = 0;
                } else {
                    mpgs[j] = Math.round(miles[j] / fuels[j] * 10) / 10;
                }
            }
            data.push({
                        name: vehicles[i],
                        data: mpgs
                    });
        }
    }

    $(document).ready(function() {
        initialData();

        Highcharts.chart('container', {
            chart: {
                type: 'line',
                width: 1000
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'MPG Per Week By Vehicle'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: cats
            },
            yAxis: {
                title: {
                    text: 'Miles Per Gallon'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: data
        });
    });
});    
</script>
</x-app-layout>