<x-app-layout>
<style>
    tr { 
        background-color: #343a40 !important;
    }
    tr.weak-gray {
        background-color: #3e444a !important;
    }
</style>
<div class="hero-static d-flex align-items-center">
    <div class="w-100">
        <div class="bg-white">
            <div class="content content-full">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10 col-xl-10 py-2">
                        <form action="/payroll/send-email" id="send-email-form" method="POST">
                            @csrf
                            <input type="hidden" name="driver-id" value="{{ $payroll->id }}" />
                            <input type="hidden" name="from-date" value="{{ $from_date }}" />
                            <input type="hidden" name="to-date" value="{{ $to_date }}" />
                            <input type="hidden" name="payment-date" value="{{ $payment_date }}" />
                        </form>
                        <div class="text-center mb-5">
                            <p class="mb-2">
                                <i class="fa fa-2x fa-file-invoice-dollar text-primary"></i>
                            </p>
                            <h1 class="h3 mb-1 font-w600 text-uppercase">
                                Driver Payroll Report
                            </h1>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Driver List
                            </a>
                            @can('manage-payroll-setting')
                            <a class="btn btn-lg btn-alt-success" href="/payroll/fixed-rates">
                                <i class="fa fa-cogs"></i> <span class="d-none d-sm-inline-block ml-1">Fixed Rates</span>
                            </a>
                            <a class="btn btn-lg btn-alt-success" href="/payroll/rate/{{ $payroll->id }}">
                                <i class="fa fa-cog"></i> <span class="d-none d-sm-inline-block ml-1">Price Per Mile</span>
                            </a>
                            @endcan
                            <a class="btn btn-lg btn-alt-success" href="javascript:void(0);" id="btn-send-email" data-id="{{ $payroll->id }}">
                                <i class="fa fa-envelope"></i> <span class="d-none d-sm-inline-block ml-1">Send Email</span>
                            </a>
                        </div>
                        @can('manage-payroll-setting')
                        <hr>
                        <div class="alert alert-info" role="alert">
                            <p class="mb-0"><strong><i class="fa fa-info-circle"></i></strong> You can set the fixed rates and price per mile for driver.</p>
                        </div>
                        @endcan
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissable" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <p class="mb-0">
                                    <i class="fa fa-fw fa-info-circle"></i> 
                                    {!! session('error') !!} 
                                </p>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter">
                                <tbody>
                                    <tr>
                                        <td style="width: 350px;" rowspan="3">
                                            <img src="{{ asset('storage/uploads/company/' . $payroll->company->logo) }}"
                                                alt="Company Logo"
                                                title="{{ $payroll->company->brand }}"
                                                width="105"
                                            />
                                        </td>
                                        <td rowspan="3" class="text-center font-weight-bolder font-size-h3">
                                            <p>{{ $payroll->company->name }}</p>
                                            <p class="mb-0">{{ 'Driver Payroll Report' }}</p>
                                        </td>
                                        <td class="text-right font-weight-bolder" style="width: 135px;">Date:</td>
                                        <td class="font-weight-bolder" style="width: 215px;">{{ $payroll->from_date }} - {{ $payroll->to_date }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right font-weight-bolder"></td>
                                        <td class="font-weight-bolder"></td>
                                    </tr>
                                    <tr class="font-weight-bolder weak-gray">
                                        <td colspan="4">Payment Date: {{ $payroll->payment_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter mt-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">Driver ID</th>
                                        <th class="text-center">Driver Name</th>
                                        <th class="text-center"># of Trips</th>
                                        <th class="text-center">Metro $</th>
                                        <th class="text-center">Miles Driven</th>
                                        <th class="text-center">$/Mile</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="weak-gray">
                                        <td class="text-center">{{ $payroll->driver_id }}</td>
                                        <td class="text-center">{{ $payroll->driver_name }}</td>
                                        <td class="text-center">{{ $payroll->fr_trips_num }}</td>
                                        <td class="text-center">${{ number_format($payroll->fr_price, 2) }}</td>
                                        <td class="text-center">{{ $payroll->other_miles }}</td>
                                        <td class="text-center">${{ number_format($payroll->other_price, 2) }}</td>
                                        <td class="text-center">${{ number_format($payroll->total_price, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter mt-1 mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-center font-weight-bolder font-size-h3">
                                            {{ __('Driver Payroll Report Detail') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bolder">
                                            {{ __('LineHaul Trips') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-striped table-dark table-vcenter mt-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th class="text-right">Miles</th>
                                        <th class="text-right">Pay Rate</th>
                                        <th class="text-right">Payroll Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($payroll->trips))
                                    @foreach ($payroll->trips as $trip)
                                    <tr class="weak-gray">
                                        <td>{{ $trip->date }}</td>
                                        <td>{{ $trip->origin }}</td>
                                        <td>{{ $trip->destination }}</td>
                                        <td class="text-right">{{ number_format($trip->miles, 2) }}</td>
                                        <td class="text-right">{{ $trip->pay_rate_unit }}{{ number_format($trip->pay_rate, 2) }}</td>
                                        <td class="text-right">${{ number_format($trip->value, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="weak-gray">
                                        <td class="text-center" colspan="5">No Trips</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="font-weight-bolder">Total</td>
                                        <td  class="text-right font-weight-bolder">
                                            ${{ number_format($payroll->total_price, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function checkTimeFrame() {
            var from_date = $('#from-date').val();
            var to_date = $('#to-date').val();
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
                'to_date': to_date
            }
        }

        $('#btn-send-email').click(function() {
            Swal.fire({
                    title: 'Are you sure?',
                    html: "Do you want to send payroll report to driver - {{ $payroll->driver_name }}?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Send!'
                }).then((result) => {
                    if (result.value) {
                        var id = $(this).attr('data-id');
            
                        if (!id) {
                            Swal.fire(
                                "Warning",
                                "Can't get ID for driver!",
                                "warning"
                            );
                            return false;
                        }

                        $('#send-email-form').submit();
                    }
                });
        });
    });
});
</script>
</x-app-layout>
