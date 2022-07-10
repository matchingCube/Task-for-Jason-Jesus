<!DOCTYPE html>
<html>
<head>
    <title>Ground Force Trucking</title>
    <style>
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        th, td {
            text-align: left !important;
            padding: 8px !important;
            color: #ffffff !important;
        }

        tr { 
        	background-color: #343a40 !important;
        }
    </style>
</head>
<body>
    <h1>Ground Force Trucking</h1>
    <p>Hi {{ $payroll->driver_name }},</p>
    <p>Here is your Driver Payroll Report.</p>
    <table>
        <tbody>
            <tr>
                <td style="width: 30%;" rowspan="3">
                    <img src="{{ asset('storage/uploads/company/' . $payroll->company->logo) }}" width="105" alt="Company Logo" title="Company Logo" />
                </td>
                <td rowspan="3" style="text-align: center !important; width: 30%; font-size: 20px !important; font-weight: bold !important;">
                    <p>{{ $payroll->company->name }}</p>
                    <p>{{ 'Driver Payroll Report' }}</p>
                </td>
                <td style="text-align: right !important; font-weight: bold !important;">Date:</td>
                <td style="font-weight: bold !important;">{{ $payroll->from_date }} - {{ $payroll->to_date }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right !important; font-weight: bold !important;"></td>
                <td style="font-weight: bold !important;"></td>
            </tr>
            <tr style="background-color: #3e444a !important; font-weight: bold !important;">
                <td colspan="4">Payment Date: {{ $payroll->payment_date }}</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 5px !important;">
    	<thead>
        	<tr>
            	<th style="text-align: center !important;">Driver ID</th>
                <th style="text-align: center !important;">Driver Name</th>
                <th style="text-align: center !important;"># of Trips</th>
                <th style="text-align: center !important;">Metro $</th>
                <th style="text-align: center !important;">Miles Driven</th>
                <th style="text-align: center !important;">$/Mile</th>
                <th style="text-align: center !important;">Total</th>
            </tr>
        </thead>
        <tbody>
        	<tr style="background-color: #3e444a !important;">
                <td style="text-align: center !important;">{{ $payroll->driver_id }}</td>
                <td style="text-align: center !important;">{{ $payroll->driver_name }}</td>
                <td style="text-align: center !important;">{{ $payroll->fr_trips_num }}</td>
                <td style="text-align: center !important;">${{ number_format($payroll->fr_price, 2) }}</td>
                <td style="text-align: center !important;">{{ $payroll->other_miles }}</td>
                <td style="text-align: center !important;">${{ number_format($payroll->other_price, 2) }}</td>
                <td style="text-align: center !important;">${{ number_format($payroll->total_price, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <table style="margin-top: 5px !important;">
        <tr>
            <td style="text-align: center !important; font-size: 20px !important; font-weight: bold;">
                {{ __('Driver Payroll Report Detail') }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                {{ __('LineHaul Trips') }}
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Origin</th>
                <th>Destination</th>
                <th style="text-align: right !important;">Miles</th>
                <th style="text-align: right !important;">Pay Rate</th>
                <th style="text-align: right !important;">Payroll Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payroll->trips as $trip)
            <tr style="background-color: #3e444a !important;">
                <td>{{ $trip->date }}</td>
                <td>{{ $trip->origin }}</td>
                <td>{{ $trip->destination }}</td>
                <td style="text-align: right !important;">{{ number_format($trip->miles, 2) }}</td>
                <td style="text-align: right !important;">{{ $trip->pay_rate_unit }}{{ number_format($trip->pay_rate, 2) }}</td>
                <td style="text-align: right !important;">${{ number_format($trip->value, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" style="font-weight: bold !important;">Total</td>
                <td  style="text-align: right !important; font-weight: bold !important;">
                    ${{ number_format($payroll->total_price, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <p>Thank you!</p>
</body>
</html>
