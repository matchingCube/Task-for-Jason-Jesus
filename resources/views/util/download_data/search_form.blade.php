<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-excel text-primary"></i>
                </span>
                <span class="">Download Historical Data</span>
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
                    <h3 class="block-title">Search Form</h3>
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
                        <div class="alert alert-info alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> You can search historical data and download as Excel file !</p>
                        </div>
                        <form id="search-form" action="" method="POST" autocomplete="off">
                            @csrf
                            <div class="table-responsive" style="overflow-x: hidden;">
                                <table class="table table-striped table-vcenter" id="download-form-table">
                                    <tbody>
                                        <tr>
                                            <td class="font-w800 text-right">From <span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select class="form-control" id="from-year-num" name="from-year-num" required>
                                                            @for ($i = 2019; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" {{ (isset($search->from_year_num)) ? ($i == $search->from_year_num ? "selected" : "") : ($i == Date("Y") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control" id="from-week-num" name="from-week-num" required>
                                                            @for ($i = 1; $i <= 52; $i++)
                                                            <option value="{{ $i }}" {{ (isset($search->from_week_num)) ? ($i == $search->from_week_num ? "selected" : "") : ($i == Date("W") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-w800 text-right">To <span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select class="form-control" id="to-year-num" name="to-year-num" required>
                                                            @for ($i = 2019; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" {{ (isset($search->to_year_num)) ? ($i == $search->to_year_num ? "selected" : "") : ($i == Date("Y") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control" id="to-week-num" name="to-week-num" required>
                                                            @for ($i = 1; $i <= 52; $i++)
                                                            <option value="{{ $i }}" {{ (isset($search->to_week_num)) ? ($i == $search->to_week_num ? "selected" : "") : ($i == Date("W") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-w800 text-right">Key Metrics<span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="revenue" id="revenue" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('revenue', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="revenue">Revenue</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-total" id="miles-total" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('miles-total', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="miles-total">Total Miles</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-driver" id="miles-driver" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('miles-driver', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="miles-driver">Miles by driver</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-vehicle" id="miles-vehicle" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('miles-vehicle', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="miles-vehicle">Miles by vehicle</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="mpg-vehicle" id="mpg-vehicle" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('mpg-vehicle', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="mpg-vehicle">MPG by vehicle</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="trips-driver" id="trips-driver" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('trips-driver', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="trips-driver">Trips by driver</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="fuelcost-total" id="fuelcost-total" name="key-metrics[]" @if (isset($search->key_metrics) && in_array('fuelcost-total', $search->key_metrics)) checked @endif>
                                                    <label class="form-check-label" for="fuelcost-total">Total Fuelcost</label>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="font-w800 text-right"></td>
                                            <td class="text-left form-group">
                                                <button type="button" class="btn btn-primary" id="download-data">
                                                    <i class="fa fa-download text-mute"></i> Download Data
                                                </button>
                                                <button type="button" class="btn btn-success" id="view-data">
                                                    <i class="fa fa-search text-mute"></i> View Data
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function checkForm() {
            let from_year_num = $('#from-year-num').val();
            let to_year_num = $('#to-year-num').val();
            let from_week_num = $('#from-week-num').val();
            let to_week_num = $('#to-week-num').val();

            from_week_num = (from_week_num < 10) ? '0' + from_week_num : from_week_num;
            to_week_num = (to_week_num < 10) ? '0' + to_week_num : to_week_num;

            let from = from_year_num + from_week_num;
            let to = to_year_num + to_week_num;


            if (from > to) {
                return {'message': "Invalid time period !", 'checked': false};
            }

            let hasKeyMetrics = false;
            $("input[name='key-metrics[]']").each(function() {
                hasKeyMetrics ||= this.checked;
            });

            if (!hasKeyMetrics) {
                return {'message': "Invalid Key Metrics !", 'checked': false};
            }

            return {'checked': true};
        }
        $('button#download-data').click(function() {
            let cf = checkForm();
            if (!cf.checked) {
                alert(cf.message);
                $('#from-year-num').focus();
                return;
            }

            $('form#search-form').attr('target', '');
            $('form#search-form').attr('action', "{{ route('util.download-data.download') }}");
            $('form#search-form').submit();
        });
        $('button#view-data').click(function() {
            let cf = checkForm();
            if (!cf.checked) {
                alert(cf.message);
                $('#from-year-num').focus();
                return;
            }

            $('form#search-form').attr('target', '_blank');
            $('form#search-form').attr('action', "{{ route('util.download-data.view') }}");
            $('form#search-form').submit();
        });
    });
});
</script>
</x-app-layout>
