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
                    <i class="far fa-calendar text-primary"></i>
                </span>
                <span class="">Weekly Schedule</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Search Form</h3>
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
            <form action="/schedule/search" method="POST" autocomplete="off" id="search-form">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            @php
                            if (!isset($year_num)) {
                                $year_num = Date("Y");
                            }
                            if (!isset($week_num)) {
                                $week_num = Date("W");
                            }
                            if (!isset($driver_id)) {
                                $driver_id = '';
                            }
                            @endphp
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
                            <label for="driver-id">Driver ID :</label>
                            <input type="text" 
                                class="form-control" 
                                name="driver-id" 
                                id="driver-id" 
                                value="{{ $driver_id }}"
                                placeholder="Enter Driver ID.." 
                            />
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="search-schedule">&nbsp;</label>
                            <button class="form-control btn btn-primary ml-auto mr-3" id="search-schedule" name="search-schedule">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-schedule" name="add-schedule">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <button type="button" class="form-control btn btn-danger ml-auto mr-3" id="remove-bulk" name="remove-bulk">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <button type="button" class="form-control btn btn-success ml-auto mr-3" id="send-sms" name="send-sms">
                                <i class="fa fa-sms"></i> Send SMS
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 20px;">
                            <input type="checkbox" name="all-check" id="all-check">
                        </th>
                        <th class="text-left"><strong><i class="far fa-user"></i> Driver</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Saturday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Sunday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Monday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Tuesday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Wednesday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Thursday</strong></th>
                        <th class="d-none d-md-table-cell text-center"><strong>Friday</strong></th>
                        <th class="d-none d-sm-table-cell text-center"><strong>Sent SMS</strong></th>
                        <th class="d-none d-sm-table-cell text-center"><strong>Response</strong></th>
                        <th class="d-none d-md-table-cell text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $idx => $schedule)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="checked-schedules[]" value="{{ $schedule->id }}" />
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            <p class="m-0">{{ 'ID: ' .$schedule->driver_id }}</p>
                            <a href="/schedule/get/{{ $schedule->id }}">{{ $schedule->driver_name }}</a>
                            @if ($schedule->driver->work_status == 0)
                                <p class="m-0"><span class="badge badge-pill badge-danger">No longer working</span></p>
                            @endif
                            <p class="m-0">
                                @if ($schedule->driver_phone)
                                <strong>
                                    <span class="badge badge-pill badge-violet">&nbsp;{{ '+' . $schedule->driver_phone }}&nbsp;
                                </strong>
                                @endif
                            </p>
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->sat_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->sat_start_time }}, {{ $schedule->sat_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->sun_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->sun_start_time }}, {{ $schedule->sun_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->mon_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->mon_start_time }}, {{ $schedule->mon_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->tue_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->tue_start_time }}, {{ $schedule->tue_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->wed_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->wed_start_time }}, {{ $schedule->wed_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->thu_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->thu_start_time }}, {{ $schedule->thu_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($schedule->fri_start_time == 'OFF')
                            <p class="m-0"><strong><span class="badge badge-danger">OFF</span></strong></p>
                            @else
                            <p class="m-0"><strong>{{ $schedule->fri_start_time }}, {{ $schedule->fri_tractor_id }}</strong></p>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($schedule->sent_sms)
                            <span class="badge badge-pill badge-success">{{ 'Yes' }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($schedule->response == 1)
                            <span class="badge badge-pill badge-success">{{ 'Accept' }}</span>
                            @elseif ($schedule->response == 2)
                            <span class="badge badge-pill badge-danger">{{ 'Reject' }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-dark view-schedule" title="View Schedule" data-id="{{ $schedule->id }}">
                                    <i class="fa fa-fw fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark edit-schedule" title="Edit Schedule" data-id="{{ $schedule->id }}">
                                    <i class="fa fa-fw fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark remove-schedule" title="Remove Schedule" data-id="{{ $schedule->id }}">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if (count($schedules) == 0)
                    <tr>
                        <td colspan="12" class="text-center">
                            <span class="text-warning">No Schedules</span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Schedule Info: Pop Out Block Modal -->
    <div class="modal fade" id="modal-schedule-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Schedule Info</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter" id="schedule-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w600 text-right">Year : </td>
                                        <td class="text-left" id="t_year_num"></td>
                                        <td class="font-w600 text-right">Week : </td>
                                        <td class="text-left" id="t_week_num"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Dates : </td>
                                        <td class="text-left" id="t_dates" colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Driver ID : </td>
                                        <td class="text-left" id="t_driver_id"></td>
                                        <td class="font-w600 text-right">Name : </td>
                                        <td class="text-left" id="t_driver_name"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Driver Phone # : </td>
                                        <td class="text-left" id="t_driver_phone"></td>
                                        <td class="font-w600 text-right">TCheck : </td>
                                        <td class="text-left" id="t_tcheck"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Spare Unit : </td>
                                        <td class="text-left" id="t_spare_unit"></td>
                                        <td class="font-w600 text-right">Fleet Net : </td>
                                        <td class="text-left text-success" id="t_fleet_net"></td>
                                    </tr>
                                    <tr><td colspan="4"></td></tr>
                                    <tr>
                                        <td class="font-w600 text-right">Saturday - Start Time : </td>
                                        <td class="text-left" id="t_sat_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_sat_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Sunday - Start Time : </td>
                                        <td class="text-left" id="t_sun_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_sun_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Monday - Start Time : </td>
                                        <td class="text-left" id="t_mon_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_mon_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Tuesday - Start Time : </td>
                                        <td class="text-left" id="t_tue_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_tue_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Wednesday - Start Time : </td>
                                        <td class="text-left" id="t_wed_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_wed_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Thursday - Start Time : </td>
                                        <td class="text-left" id="t_thu_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_thu_tractor_id"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600 text-right">Friday - Start Time : </td>
                                        <td class="text-left" id="t_fri_start_time"></td>
                                        <td class="font-w600 text-right">Tracktor : </td>
                                        <td class="text-left" id="t_fri_tractor_id"></td>
                                    </tr>
                                    <tr><td colspan="4"></td></tr>
                                    <tr>
                                        <td class="font-w600 text-right">Sent SMS : </td>
                                        <td class="text-left" id="t_sent_sms"></td>
                                        <td class="font-w600 text-right">Response : </td>
                                        <td class="text-left" id="t_response"></td>
                                    </tr>
                                </tbody>
                            </table>
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
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#schedule-table').removeClass('d-none');
            $('#modal-schedule-info .table-responsive .alert').remove();

            $('#t_year_num').html('');
            $('#t_week_num').html('');
            $('#t_dates').html('');
            $('#t_sent_sms').html('');
            $('#t_driver_id').html('');
            $('#t_driver_name').html('');
            $('#t_driver_phone').html('');
            $('#t_tcheck').html('');
            $('#t_spare_unit').html('');
            $('#t_fleet_net').html('');
            $('#t_sat_start_time').html('');
            $('#t_sat_tractor_id').html('');
            $('#t_sun_start_time').html('');
            $('#t_sun_tractor_id').html('');
            $('#t_mon_start_time').html('');
            $('#t_mon_tractor_id').html('');
            $('#t_tue_start_time').html('');
            $('#t_tue_tractor_id').html('');
            $('#t_wed_start_time').html('');
            $('#t_wed_tractor_id').html('');
            $('#t_thu_start_time').html('');
            $('#t_thu_tractor_id').html('');
            $('#t_fri_start_time').html('');
            $('#t_fri_tractor_id').html('');
            $('#t_response').html('');
        }
        $('button.view-schedule').click(function() {
            $('#modal-schedule-info').modal('show');
            var id = $(this).data('id');
            $.ajax({
				url:        "/schedule/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        inital_modal();
                        
                        $('#t_year_num').html(data.schedule.year_num);
                        $('#t_week_num').html(data.schedule.week_num);
                        $('#t_dates').html(data.schedule.from_date + ' - ' + data.schedule.to_date);
                        $('#t_sent_sms').html(data.schedule.sent_sms == 1 ? '<span class="badge badge-pill badge-success">Sent</span>' : '');
                        $('#t_response').html(
                            (data.schedule.response == 1) ? 
                                '<span class="badge badge-pill badge-success">Accept</span>' : 
                                ((data.schedule.response == 2) ? '<span class="badge badge-pill badge-danger">Reject</span>' : '')
                        );
                        $('#t_driver_id').html(data.schedule.driver_id);
                        $('#t_driver_name').html('<span class="badge badge-primary">' + data.schedule.driver_name + '</span>');
                        $('#t_driver_phone').html(data.schedule.driver_phone ? '<span class="badge badge-pill badge-violet">+' + data.schedule.driver_phone + '</span>' : '');
                        $('#t_tcheck').html(data.schedule.tcheck);
                        $('#t_spare_unit').html(data.schedule.spare_unit);
                        $('#t_fleet_net').html(data.schedule.fleet_net);
                        $('#t_sat_start_time').html(data.schedule.sat_start_time != 'OFF' ? data.schedule.sat_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_sat_tractor_id').html(data.schedule.sat_tractor_id);
                        $('#t_sun_start_time').html(data.schedule.sun_start_time != 'OFF' ? data.schedule.sun_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_sun_tractor_id').html(data.schedule.sun_tractor_id);
                        $('#t_mon_start_time').html(data.schedule.mon_start_time != 'OFF' ? data.schedule.mon_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_mon_tractor_id').html(data.schedule.mon_tractor_id);
                        $('#t_tue_start_time').html(data.schedule.tue_start_time != 'OFF' ? data.schedule.tue_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_tue_tractor_id').html(data.schedule.tue_tractor_id);
                        $('#t_wed_start_time').html(data.schedule.wed_start_time != 'OFF' ? data.schedule.wed_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_wed_tractor_id').html(data.schedule.wed_tractor_id);
                        $('#t_thu_start_time').html(data.schedule.thu_start_time != 'OFF' ? data.schedule.thu_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_thu_tractor_id').html(data.schedule.thu_tractor_id);
                        $('#t_fri_start_time').html(data.schedule.fri_start_time != 'OFF' ? data.schedule.fri_start_time : '<span class="badge badge-danger">OFF</span>');
                        $('#t_fri_tractor_id').html(data.schedule.fri_tractor_id);
                    } else {
                        inital_modal();
                        
                        $('#schedule-table').addClass('d-none');
                        $('#modal-schedule-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                    }
                    $('#modal-schedule-info').modal('show');
				}
            });
        });
        $('button.edit-schedule').click(function() {
            var id = $(this).data('id');
            location.href = '/schedule/edit/' + id;
        });
        $('button.remove-schedule').click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove!'
            }).then((result) => {
                if (result.value) {
                    var id = $(this).data('id');
                    location.href = '/schedule/remove/' + id;
                }
            });
        });
        $('button#add-schedule').click(function() {
            location.href = '/schedule/add';
        });
        $('input[type=checkbox][name=all-check]').click(function() {
            var schedules = $('input[type=checkbox][name="checked-schedules[]"]');
            for (let i = 0; i < schedules.length; i++) {
                schedules[i].checked = this.checked;
            }
        });
        $('button#remove-bulk').click(function() {
            var schedules = $('input[type=checkbox][name="checked-schedules[]"]');
            var ids = '';
            var checkedList = [];
            for (let i = 0; i < schedules.length; i++) {
                if (schedules[i].checked) {
                    ids += $(schedules[i]).val() + '|';
                    checkedList.push(schedules[i]);
                }
            }
            
            if (ids == '') {
                Swal.fire(
                    "Error",
                    "Please choose the schedules for removing.",
                    "error"
                );
                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:        "/schedule/remove-bulk",
                        dataType:   "json",
                        type:       "post",
                        data:       {
                                        _token: "{{ csrf_token() }}",
                                        ids: ids
                                    },
                        success:    function( data ) {
                            if (data.type == 'success') {
                                // remove rows from table
                                checkedList.forEach(c => {
                                    $(c).closest('tr').remove();
                                });

                                Swal.fire(
                                    "Note",
                                    "Schedules(IDs: " + data.ids + ") were removed successfully.",
                                    "success"
                                );
                            } else {
                                Swal.fire(
                                    "Error",
                                    data.message,
                                    "error"
                                );
                            }
                        },
                        error: function (jqXHR, exception) {
                            Swal.fire(
                                "Error",
                                "Sorry, we got the error while processing. Please retry later!",
                                "error"
                            );
                        }
                    });
                }
            })
        });
        $('button#send-sms').click(function() {
            var schedules = $('input[type=checkbox][name="checked-schedules[]"]');
            var ids = '';
            var checkedList = [];
            for (let i = 0; i < schedules.length; i++) {
                if (schedules[i].checked) {
                    ids += $(schedules[i]).val() + '|';
                    checkedList.push(schedules[i]);
                }
            }
            
            if (ids == '') {
                Swal.fire(
                    "Error",
                    "Please choose the schedules for sending sms.",
                    "error"
                );
                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "It might take a minute.",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:        "/schedule/send-sms",
                        dataType:   "json",
                        type:       "post",
                        data:       {
                                        _token: "{{ csrf_token() }}",
                                        ids: ids
                                    },
                        success:    function( data ) {
                            if (data.type == 'success') {
                                Swal.fire(
                                    "Note",
                                    "SMS messages have been sent to the drivers(" + data.ids + ").",
                                    "success"
                                ).then((result) => {
                                    console.log(result);
                                    if (result.value) {
                                        $('form#search-form').submit();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    "Error",
                                    data.message,
                                    "error"
                                );
                            }
                        },
                        error: function (jqXHR, exception) {
                            Swal.fire(
                                "Error",
                                "Sorry, we got the error while processing. Please retry later!",
                                "error"
                            );
                        }
                    });
                }
            })
        });
    });
});
</script>
</x-app-layout>
