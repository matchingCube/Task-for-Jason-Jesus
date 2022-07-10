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

        tr:nth-child(even) {background-color: #343a40 !important;}
        tr:nth-child(odd) {background-color: #3e444a !important;}
    </style>
</head>
<body>
    <h1>Ground Force Trucking</h1>
    <p>Hi, {{ $person->name }}</p>
    <p>Here is your Driver Scorecard.</p>
    <table style="width: 30%;">
        <tbody>
            <tr style="background-color: #343a40 !important;">
                <td style="width: 30%;" rowspan="8">
                    <img src="{{ asset('/media/photos/drivers/' . rawurlencode($person->photo)) }}" alt="Driver Photo" title="Driver Photo">
                </td>
                <td colspan="2" style="text-align: center !important;">{{ $person->name }}</td>
            </tr>
            <tr style="background-color: #3e444a !important;">
                <td>FedEX ID</td>
                <td>{{ $person->fedex_id }}</td>
            </tr>
            <tr style="background-color: #343a40 !important;">
                <td>Start Date</td>
                <td>{{ $person->drug_test }}</td>
            </tr>
            <tr style="background-color: #3e444a !important;">
                <td>Birth Date</td>
                <td>{{ $person->birth }}</td>
            </tr>
            <tr style="background-color: #343a40 !important;">
                <td>MEC</td>
                <td 
                    @if ($person->mec_color == 'text-danger') style="color: #d26a5c !important; border: none !important;" @endif
                    @if ($person->mec_color == 'text-warning') style="color: #f3b760 !important; border: none !important;" @endif
                >{{ $person->mec }}</td>
            </tr>
            <tr style="background-color: #3e444a !important;">
                <td>MVR</td>
                <td
                    @if ($person->mvr_color == 'text-danger') style="color: #d26a5c !important; border: none !important;" @endif
                    @if ($person->mvr_color == 'text-warning') style="color: #f3b760 !important; border: none !important;" @endif
                >{{ $person->mvr }}</td>
            </tr>
            <tr style="background-color: #343a40 !important;">
                <td>COV</td>
                <td
                    @if ($person->cov_color == 'text-danger') style="color: #d26a5c !important; border: none !important;" @endif
                    @if ($person->cov_color == 'text-warning') style="color: #f3b760 !important; border: none !important;" @endif
                >{{ $person->cov }}</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%; margin-top: 10px;">
        <thead>
            <tr>
                <th style="background-color: #343a40 !important; width: 100px;"></th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 47</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 48</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 49</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 50</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 51</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 10%;">Week 52</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 2%; border: none; background-color: #ffffff;"></th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 5%;">Q1</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 5%;">Q2</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 5%;">Q3</th>
                <th style="background-color: #343a40 !important; text-align: center !important; width: 5%;">Q4</th>
            </tr>
        </thead>
        <tbody>
        @if (isset($scorecards) && count($scorecards) > 0)
        @for ($i = 0; $i < count($scorecards); $i++)
            <tr>
                <td style="@if ($i % 2 == 0) background-color: #343a40 !important; @else background-color: #3e444a !important; @endif">{{ $scorecards[$i]->type }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_47 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_47 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_48 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_48 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_49 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_49 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_50 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_50 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_51 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_51 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->wk_52 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->wk_52 }}</td>
                <td style="border: none; background-color: #ffffff;"></td>
                <td style="text-align: center !important; @if ($scorecards[$i]->q1 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->q1 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->q2 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->q2 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->q3 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->q3 }}</td>
                <td style="text-align: center !important; @if ($scorecards[$i]->q4 > 0) background-color: #f2d5d1 !important; color: red !important; border: none !important; @else background-color: #cbeeda !important; color: green !important; border: none !important; @endif">{{ $scorecards[$i]->q4 }}</td>
            </tr>
        @endfor
        @endif    
        </tbody>
    </table>
    <p>Thank you!</p>
</body>
</html>
