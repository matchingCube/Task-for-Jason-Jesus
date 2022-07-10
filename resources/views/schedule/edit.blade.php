<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-user text-primary"></i>
                </span>
                <span class="">Edit Schedule</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">Schedule Info Form</h3>
                </div>
                <div class="block-content">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="font-w600 m-1"><i class="fa fa-fw fa-times-circle"></i> {{ session('error') }}</p>
                        </div>
                    @endif
                    <form class="js-validation" action="/schedule/save" method="POST" id="schedule-form" autocomplete="off">
                        @csrf
                        @php
                            if (!isset($schedule)) {
                                $year_num = old('year_num') ?? Date("Y");
                                $week_num = old('week_num') ?? Date("W");
                            } else {
                                $year_num = $schedule->year_num;
                                $week_num = $schedule->week_num;
                            }
                        @endphp
                        <input type="hidden" name="id" value="@if (isset($schedule)){{ $schedule->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-schedules">
                                <i class="fa fa-list"></i> View Schedules
                            </button>
                            <button type="submit" class="btn btn-primary save-schedule">
                                <i class="fa fa-save"></i> Save Schedule
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="schedule-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">Year # <span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <select class="form-control" id="year_num" name="year_num" required>
                                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                                    <option value="{{ $i }}" @if ($i == $year_num) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Week # <span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <select class="form-control" id="week_num" name="week_num" required>
                                                @for ($i = 1; $i <= 52; $i++)
                                                    <option value="{{ $i }}" @if ($i == $week_num) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">From Date <span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="js-datepicker form-control" 
                                                    name="from_date" 
                                                    value="@if (isset($schedule)){{ $schedule->from_date }}@else{{ old('from_date') }}@endif" 
                                                    data-week-start="0" 
                                                    data-autoclose="true" 
                                                    data-today-highlight="true" 
                                                    data-date-format="mm/dd/yyyy" 
                                                    placeholder="mm/dd/yyyy"
                                                    required>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">To Date <span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="js-datepicker form-control" 
                                                    name="to_date" 
                                                    value="@if (isset($schedule)){{ $schedule->to_date }}@else{{ old('to_date') }}@endif" 
                                                    data-week-start="0" 
                                                    data-autoclose="true" 
                                                    data-today-highlight="true" 
                                                    data-date-format="mm/dd/yyyy" 
                                                    placeholder="mm/dd/yyyy"
                                                    required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">Driver <span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <select class="form-control" id="driver_id" name="driver_id" required>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}" 
                                                        @if (isset($schedule) && $driver->driver_id == $schedule->driver_id) selected 
                                                        @elseif ($driver->id == old('driver_id')) selected 
                                                        @endif
                                                    >
                                                        {{ $driver->driver_id }} - {{ $driver->driver_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">Phone : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="phone-number-prefix">+</span>
                                                </div>
                                                <input type="number" 
                                                        class="form-control" 
                                                        name="driver_phone" 
                                                        value="@if (isset($schedule)){{ $schedule->driver_phone }}@else{{ old('driver_phone') }}@endif" 
                                                        placeholder="Enter phone number.." 
                                                        aria-describedby="phone-number-prefix" />
                                            </div>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">TCheck : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="tcheck" 
                                                    value="@if (isset($schedule)){{ $schedule->tcheck }}@else{{ old('tcheck') }}@endif" 
                                                    placeholder="Enter T Check.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">Spare Unit : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="spare_unit" 
                                                    value="@if (isset($schedule)){{ $schedule->spare_unit }}@else{{ old('spare_unit') }}@endif" 
                                                    placeholder="Enter spare unit.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Fleet Net <span class="text-danger">*</span>: </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="fleet_net" 
                                                    value="@if (isset($schedule)){{ $schedule->fleet_net }}@else{{ old('fleet_net') }}@endif" 
                                                    placeholder="Enter fleet net.." 
                                                    required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">Sent SMS : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <select class="form-control" id="sent_sms" name="sent_sms">
                                                <option value="0" @if (isset($schedule) && $schedule->sent_sms == 0) selected @elseif (old('sent_sms') == 0) selected @endif>Not sent yet</option>
                                                <option value="1" @if (isset($schedule) && $schedule->sent_sms == 1) selected @elseif (old('sent_sms') == 1) selected @endif>Sent</option>
                                            </select>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Response : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <select class="form-control" id="response" name="response">
                                                <option value="0" 
                                                    @if (isset($schedule) && $schedule->response != 1 && $schedule->response != 2) selected 
                                                    @elseif (old('response') != 1 && old('response') != 2) selected @endif>Not response yet</option>
                                                <option value="1" @if (isset($schedule) && $schedule->response == 1) selected @elseif (old('response') == 1) selected @endif>Accept</option>
                                                <option value="2" @if (isset($schedule) && $schedule->response == 2) selected @elseif (old('response') == 2) selected @endif>Reject</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Saturday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="sat_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->sat_start_time }}@else{{ old('sat_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="sat_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->sat_tractor_id }}@else{{ old('sat_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Sunday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="sun_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->sun_start_time }}@else{{ old('sun_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="sun_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->sun_tractor_id }}@else{{ old('sun_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Monday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="mon_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->mon_start_time }}@else{{ old('mon_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    retuired />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="mon_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->mon_tractor_id }}@else{{ old('mon_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Tuesday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="tue_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->tue_start_time }}@else{{ old('tue_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="tue_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->tue_tractor_id }}@else{{ old('tue_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Wednesday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="wed_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->wed_start_time }}@else{{ old('wed_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="wed_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->wed_tractor_id }}@else{{ old('wed_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Thursday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="thu_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->thu_start_time }}@else{{ old('thu_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="thu_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->thu_tractor_id }}@else{{ old('thu_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 15%;">
                                            <span class="text-primary"><strong>Friday</strong></span> 
                                            - Start Time <span class="text-danger">*</span> : 
                                        </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="fri_start_time" 
                                                    value="@if (isset($schedule)){{ $schedule->fri_start_time }}@else{{ old('fri_start_time') }}@endif" 
                                                    placeholder="Format: 5:00 AM or OFF" 
                                                    required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 15%;">Tractor ID : </td>
                                        <td class="text-left" style="width: 35%;">
                                            <input type="text" 
                                                    class="form-control" 
                                                    name="fri_tractor_id" 
                                                    value="@if (isset($schedule)){{ $schedule->fri_tractor_id }}@else{{ old('fri_tractor_id') }}@endif" 
                                                    placeholder="Enter tractor ID.." />
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
<script type="text/javascript">
jQuery(function($){
    One.helpers(['datepicker']);

    $('button.view-schedules').click(function() {
        location.href = "/schedule/search";
    });
});
</script>
</x-app-layout>