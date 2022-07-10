<x-app-layout>
<style type="text/css">
    .highcharts-figure, .highcharts-data-table table {
        min-width: 310px; 
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
                    <i class="fa fa-chart-pie text-primary"></i>
                </span>
                <span class="">Miles Per Week By Vehicle</span>
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
                        <div id="container-pie"></div>
                        </p>
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/highchart-miles-week-driver.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    var weeks = {!! $data !!};
    var categories = [];
    var vehicles = [];
    var data = [];
    var average = [];
    var pie_data = [];
    var limit = {!! $limit !!};
    var year_num = {!! $year_num !!};
    var week_num = {!! $week_num !!};

    function initialData() {
        for (let i = week_num - limit + 1; i <= week_num; i++) {
            categories.push('WK-' + i + ', ' + year_num);
        }

        $(weeks).each(function() {
            let vehicle = this.vehicle;
            if (vehicles.indexOf(vehicle) == -1) {
                vehicles.push(vehicle);
            }
            this.miles = parseFloat(parseFloat(this.miles).toFixed(2));
        });
        
        for (let i = 0; i < vehicles.length; i++) {
            let temp = [];
            for (let j = 0; j < categories.length; j++) {
                let flag = false;
                for (let k = 0; k < weeks.length; k++) {
                    if (weeks[k].vehicle == vehicles[i] && weeks[k].week == categories[j]) {
                        temp[j] = weeks[k].miles;
                        flag = true;
                        break;
                    }
                }
                if (flag == false) {
                    temp[j] = 0;
                }
            }
            data.push({
                            type: 'column',
                            name: vehicles[i],
                            data: temp
                        });
        }

        //get spline data
        for (let i = 0; i < categories.length; i++) {
            let sum_miles = 0;
            for (let j = 0; j < weeks.length; j++) {
                if (weeks[j].week == categories[i]) {
                    sum_miles += weeks[j].miles;
                }
            }
            average.push(Math.round(sum_miles / vehicles.length * 100) / 100);
        }

        //get pie data
        for (let i = 0; i < vehicles.length; i++) {
            let sum_miles = 0;
            for (let j = 0; j < weeks.length; j++) {
                if (weeks[j].vehicle == vehicles[i]) {
                    sum_miles += weeks[j].miles;
                }
            }
            if (i == 0) {
                pie_data.push({
                            name: vehicles[i],
                            y: sum_miles,
                            sliced: true,
                            selected: true
                        });
            } else {
                pie_data.push([vehicles[i], sum_miles]);
            }
            
        }

        //add spline chart
        data.push({
                        type: 'spline',
                        name: 'Average',
                        data: average,
                        marker: {
                            lineWidth: 2,
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: 'white'
                        }
                    });
    }

    $(document).ready(function() {
        initialData();

        Highcharts.chart('container', {
            chart: {
                width: 1000
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Miles Per Week By Vehicle'
            },
            xAxis: {
                categories: categories,
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Driven distance (miles)'
                }
            },
            tooltip: {
                valueSuffix: ' miles'
            },
            series: data
        });
        
        Highcharts.chart('container-pie', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            credits: {
                enabled: false
            },
            title: {
                text: 'Total Miles Per Vehicle'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:.1f}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Miles',
                colorByPoint: true,
                data: pie_data
            }]
        });
    });
});    
</script>
</x-app-layout>