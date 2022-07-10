<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
    .badge-violet {
        color: #fff;
        background-color: #7F00FF;
    }
    @media (min-width: 768px) {
        .modal-dialog {
            max-width: 700px;
            margin: 1.75rem auto;
        }
    }
</style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fas fa-file-pdf text-primary"></i>
                </span>
                <span class="">Monthly Maintenance Record</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">MMR</h3>
                </div>
                <div class="block-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {!! session('success') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {!! session('error') !!}</p>
                        </div>
                    @endif
                    <table id="fleet-mmr-empty" hidden>
                        <tbody><x-fleet-mmr :fleets="$fleets" /></tbody>
                    </table>
                    <form action="/mmr/send-email" method="POST" autocomplete="off" id="mmr-form">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="form-group mr-3">
                                <button type="button" class="form-control btn btn-success ml-auto mr-3" id="upload-signs" name="upload-signs">
                                    <i class="fa fa-upload"></i> Upload Signature
                                </button>
                            </div>
                            <div class="form-group mr-3">
                                <button class="form-control btn btn-primary ml-auto mr-3" id="send-email" name="send-email">
                                    <i class="fas fa-paper-plane"></i> Send MMRs
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="fleet-table">
                                <thead>
                                    <tr>
                                        <th class="table-dark" colspan="4">Main Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Year <span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select class="form-control" id="year-num" name="year-num" required>
                                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                                    <option value="{{ $i }}" @if ($i == Date("Y")) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Month<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select class="form-control" id="month-num" name="month-num" required>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    @php
                                                        $month = date('m');
                                                        $dateObj = DateTime::createFromFormat('!m', $i);
                                                        $monthName = $dateObj->format('F');
                                                    @endphp
                                                    <option value="{{ $i }}" @if ($i == Date("m")) selected @endif>{{ $monthName }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Sign <span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select name="sign" class="form-control" required>
                                                @foreach ($signs as $sign)
                                                <option value="{{ $sign->name }}">{{ $sign->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Date Completed<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <input type="text"
                                                    class="js-datepicker form-control"
                                                    name="completed-date"
                                                    value=""
                                                    data-week-start="0"
                                                    data-autoclose="true"
                                                    data-today-highlight="true"
                                                    data-date-format="mm-dd-yy"
                                                    placeholder="mm-dd-yy"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end">
                            <div class="form-group mr-3 mb-1">
                                <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-maintenance" name="add-maintenance">
                                    <i class="fa fa-plus-circle"></i> Add Tractor / Maintenance
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-vcenter" id="mmr-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">Maintenance</th>
                                        <th class="text-center" style="width: 10%;">Out of Service</th>
                                        <th class="text-center" style="width: 130px;">Tractor</th>
                                        <th class="text-center" style="width: 15%;">Current Mileage</th>
                                        <th class="text-center" style="width: 115px;">Date</th>
                                        <th class="text-center">Description of Maintenance Performed</th>
                                        <th class="text-center" style="width: 65px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-maintenances">
                                        <td class="text-center" colspan="7">No Tractors / Maintenances</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <!-- Upload Form: Pop Out Block Modal -->
                <div class="modal fade" id="modal-upload-form" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Upload Signs</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="row push">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 alert alert-info alert-dismissable" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <p style="margin-bottom: 0;">
                                                <i class="fa fa-fw fa-info-circle"></i> <strong>File Names should be whose signature,</strong> and you can upload multiple signs.
                                            </p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <p class="font-size-sm text-muted text-right">
                                                Signature Files:
                                            </p>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12 overflow-hidden">
                                            <form action="/mmr/upload-signs" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" id="upload-files" name="upload-files[]" accept="image/*" multiple>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-upload text-mute"></i> Upload
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Pop Out Block Modal -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    One.helpers(['datepicker']);

    $(document).ready(function() {
        $('.js-datepicker').datepicker('setDate', new Date());

        function initializeDatePicker() {
            var year = $('#year-num').val();
            var month = $('#month-num').val() - 1;
            var startDate = new Date(year, month, 1);
            var endDate = new Date(year, month + 1, 0);
            
            $('.date').datepicker('remove');

            $('.date').datepicker({
                format: 'mm-dd-yy',
                defaultViewDate: startDate,
                clearBtn: true,
                autoclose: true,
                startDate: startDate,
                endDate: endDate
            });
        }

        $('select#year-num, select#month-num').change(function() {
            $('.date').val('');
            initializeDatePicker();
        });
        $('button#upload-signs').click(function() {
            $('#modal-upload-form').modal('show');
        });
        $('button#add-maintenance').click(function() {
            $('tr#no-maintenances').remove();
            var fleet = $("#fleet-mmr-empty tbody").html();
            $("table#mmr-table tbody").append($(fleet));
            initializeDatePicker();
        });
        $(document).on('click', 'button.remove-maintenance', function() {
            $(this).closest('tr').remove();

            if ($('table#mmr-table tbody tr').length == 0) {
                $("table#mmr-table tbody").append(
                    '<tr id="no-maintenances">' +
                    '<td class="text-center" colspan="7">' +
                    'No Tractors / Maintenances' +
                    '</td>' +
                    '</tr>'
                );
            }
        });
        $(document).on('change', 'input[type=number][name="current-mileage[]"]', function() {
            var cValue = $(this).val();
            var tId = $(this).closest('tr').find('select[name="tractor-id[]"]').val();
            
            $.each($('table#mmr-table tbody tr'), function(index, tr) {
                if ($(tr).find('select[name="tractor-id[]"]').val() == tId) {
                    $(tr).find('input[type=number][name="current-mileage[]"]').val(cValue);
                }
            });
        });
        $(document).on('change', 'select[name="maintenance[]"]', function() {
            if ($(this).val() == '1') {
                $(this).closest('tr').find('input[type=text][name="maintenance-date[]"]').prop('required', true);
                $(this).closest('tr').find('input[type=text][name="maintenance-desc[]"]').prop('required', true);
            } else {
                $(this).closest('tr').find('input[type=text][name="maintenance-date[]"]').val('');
                $(this).closest('tr').find('input[type=text][name="maintenance-desc[]"]').val('');
                $(this).closest('tr').find('input[type=text][name="maintenance-date[]"]').prop('required', false);
                $(this).closest('tr').find('input[type=text][name="maintenance-desc[]"]').prop('required', false);
            }
        });
    });
});
</script>
</x-app-layout>