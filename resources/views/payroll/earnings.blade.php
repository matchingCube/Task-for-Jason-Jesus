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
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Payroll</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Send Report Email</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title">Driver Payroll Report Settings</h3>
                </div>
                <div class="block-content">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <i class="fa fa-fw fa-info-circle"></i> 
                                {!! session('status') !!} 
                            </p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0">
                                <i class="fa fa-fw fa-info-circle"></i> 
                                {!! session('error') !!} 
                                Please check <a href="/payroll/setting" class="font-italic font-weight-bolder">payroll setting</a>
                            </p>
                        </div>
                    @endif
                    <form action="/payroll/driver-earnings-report" method="POST" id="report-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="driver-name">Driver Name :</label>
                                    <input type="text"
                                            class="form-control"
                                            name="driver-name"
                                            id="driver-name"
                                            value="{{ $driver_name }}"
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="search-drivers">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-primary ml-auto mr-3" id="search-drivers" name="search-drivers">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="from-date">From <span class="text-danger">*</span> :</label>
                                    <input type="text"
                                            class="js-datepicker form-control"
                                            name="from-date"
                                            id="from-date"
                                            value="{{ get_from_date($payment_date, 'Y-m-d') }}"
                                            data-week-start="0"
                                            data-autoclose="true"
                                            data-today-highlight="true"
                                            data-date-format="yyyy-mm-dd"
                                            placeholder="yyyy-mm-dd"
                                            required
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="to-date">To <span class="text-danger">*</span> :</label>
                                    <input type="text"
                                            class="js-datepicker form-control"
                                            name="to-date"
                                            id="to-date"
                                            value="{{ get_to_date($payment_date, 'Y-m-d') }}"
                                            data-week-start="0"
                                            data-autoclose="true"
                                            data-today-highlight="true"
                                            data-date-format="yyyy-mm-dd"
                                            placeholder="yyyy-mm-dd"
                                            required
                                    />
                                    
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="payment-date">Payment Date <span class="text-danger">*</span> :</label>
                                    <input type="text"
                                            class="js-datepicker form-control"
                                            name="payment-date"
                                            id="payment-date"
                                            value="{{ get_payment_date($payment_date, 'Y-m-d') }}"
                                            data-week-start="0"
                                            data-autoclose="true"
                                            data-today-highlight="true"
                                            data-date-format="yyyy-mm-dd"
                                            placeholder="yyyy-mm-dd"
                                            required
                                    />
                                    
                                </div>
                            </div>
                            @if ($available)
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="send-bulk-reports">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-primary ml-auto mr-3" id="send-bulk-reports" name="send-bulk-reports">
                                        <i class="fa fa-mail-bulk"></i> Send Reports
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter" id="drivers-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="width: 20px;">
                                            <input type="checkbox" name="all-check" id="all-check">
                                        </th>
                                        <th class="text-center" style="width: 80px;">#</th>
                                        <th class="text-center" style="width: 10%;">Driver ID</th>
                                        <th class="text-center" style="width: 15%;">Name</th>
                                        <th class="text-center" style="width: 15%;">Email</th>
                                        <th class="text-center" style="width: 10%;">Phone</th>
                                        <th class="text-center" style="width: 10%;">License</th>
                                        <th class="text-center">Address</th>
                                        <th class="d-none d-md-table-cell text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($drivers) && count($drivers) > 0)
                                @foreach ($drivers as $idx => $driver)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="checked-drivers[]" value="{{ $driver->id }}" />
                                        </td>
                                        <td class="font-w600 font-size-sm text-center">{{ $idx + 1 }}</td>
                                        <td class="font-w600 font-size-sm text-center">
                                            <a href="/drivers/edit/{{ $driver->id }}" target="_self"><strong class="text-primary">{{ $driver->driver_id }}</strong></a>
                                        </td>
                                        <td class="font-w600 font-size-sm text-left">
                                            {{ $driver->driver_name }}
                                            @if ($driver->work_status == 0)
                                            <span class="font-w600 badge badge-pill badge-danger">No longer working</span>
                                            @endif
                                        </td>
                                        <td class="font-w600 font-size-sm text-left">
                                            {{ $driver->email }}
                                        </td>
                                        <td class="font-w600 font-size-sm text-center">
                                            <span class="badge badge-pill badge-violet">{{ $driver->phone ? '+'.$driver->phone : '' }}</span>
                                        </td>
                                        <td class="font-w600 font-size-sm text-center">
                                            <span class="badge badge-success">{{ $driver->license }}</span>
                                        </td>
                                        <td class="font-w600 font-size-sm text-left">{{ $driver->address }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if ($available)
                                                <button type="button" class="btn btn-sm btn-dark view-report" title="View Report" data-id="{{ $driver->id }}">
                                                    <i class="fa fa-fw fa-file-invoice-dollar"></i> View Report
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="font-w600 font-size-sm text-center">No Drivers</td>
                                    </tr>
                                @endif    
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    One.helpers(['datepicker']);
    
    $(document).ready(function() {
        var email_sent = "{{ session('email_sent') }}";
        if (email_sent == "1") {
            One.helpers('notify', {from: 'top', align: 'right', message: 'Driver Payroll Report sent successfully!'});
        }
        
        function checkTimeFrame() {
            var from_date = $('#from-date').val();
            var to_date = $('#to-date').val();
            var payment_date = $('#payment-date').val();

            if (!from_date || !to_date) {
                return {
                            'type': false,
                            'message': "Please enter from/to date for report!",
                        };
            }
            if (from_date > to_date) {
                return {
                            'type': false,
                            'message': "'From date' should be less than 'To date'!"
                        };
            }

            return {
                'type': true,
                'message': "Passed Validation!",
                'from_date': from_date,
                'to_date': to_date,
                'payment_date': payment_date
            }
        }

        $('button#send-bulk-reports').click(function() {
            Swal.fire({
                    title: 'Are you sure?',
                    html: "Do you want to send payroll report to chosen drivers?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Send!'
                }).then((result) => {
                    if (result.value) {
                        var drivers = $('input[type=checkbox][name="checked-drivers[]"]:checked');
                        if (!drivers || drivers.length == 0) {
                            Swal.fire(
                                "Warning",
                                "Please choose drivers.",
                                "warning"
                            );

                            return false;
                        }

                        var result = checkTimeFrame();
                        if (result.type === false) {
                            Swal.fire(
                                "Warning",
                                result.message,
                                "warning"
                            );
                            return false;
                        }

                        $('#report-form').attr('action', '/payroll/driver-earnings-report');
                        $('#report-form').submit();
                    }
            });
        });

        $('button.view-report').click(function() {
            var id = $(this).attr('data-id');

            if (!id) {
                Swal.fire(
                    "Warning",
                    "Please choose a driver!",
                    "warning"
                );
                return false;
            }

            var result = checkTimeFrame();
            if (result.type === false) {
                Swal.fire(
                    "Warning",
                    result.message,
                    "warning"
                );
                return false;
            }

            location.href = "/payroll/driver-earnings-report/" + id 
                            + "/" + result.from_date 
                            + "/" + result.to_date
                            + "/" + result.payment_date;
        });

        $('input[type=checkbox][name=all-check]').click(function() {
            var drivers = $('input[type=checkbox][name="checked-drivers[]"]');
            for (let i = 0; i < drivers.length; i++) {
                drivers[i].checked = this.checked;
            }
        });
        
        $('button#search-drivers').click(function() {
            $('form#report-form').attr('action', '/payroll/drivers');
            $('form#report-form').submit();
        });
    });
});
</script>
</x-app-layout>