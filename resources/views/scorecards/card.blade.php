<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-id-card text-primary"></i>
                </span>
                <span class="">Driver Scorecard</span>
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
                <div class="block-content">
                    <div class="row">
                        <table class="table table-striped table-dark table-vcenter" style="width: 30%;" id="persons-table">
                            <tbody>
                                <tr>
                                    <td class="text-center" style="width: 30%;" rowspan="8">
                                        <img class="img-avatar img-avatar100" src="{{ asset('/media/photos/drivers/' . $person->photo) }}" alt="">
                                    </td>
                                    <td class="font-w800 text-center" colspan="2">{{ $person->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">FedEX ID</td>
                                    <td class="text-left">{{ $person->fedex_id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">Start Date</td>
                                    <td class="text-left">{{ $person->drug_test }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">Birth Date</td>
                                    <td class="text-left">{{ $person->birth }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">MEC</td>
                                    <td class="text-left {{ $person->mec_color }}">{{ $person->mec }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">MVR</td>
                                    <td class="text-left {{ $person->mvr_color }}">{{ $person->mvr }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">COV</td>
                                    <td class="text-left {{ $person->cov_color }}">{{ $person->cov }}</td>
                                </tr>
                                <tr>
                                    <td class="font-w800">Email</td>
                                    <td class="text-left">{{ $person->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <table class="table table-striped table-dark table-vcenter" id="scorecard-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 100px;"></th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 47</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 48</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 49</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 50</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 51</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Week 52</th>
                                    <th class="d-none d-sm-table-cell text-center empty" style="width: 2%;"></th>
                                    <th class="text-center" style="width: 5%;">Q1</th>
                                    <th class="text-center" style="width: 5%;">Q2</th>
                                    <th class="text-center" style="width: 5%;">Q3</th>
                                    <th class="text-center" style="width: 5%;">Q4</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($scorecard) && count($scorecard) > 0)
                            @foreach ($scorecard as $item)
                                <tr>
                                    <td class="text-left">{{ $item->type }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_47 > 0) table-danger @else table-success @endif">{{ $item->wk_47 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_48 > 0) table-danger @else table-success @endif">{{ $item->wk_48 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_49 > 0) table-danger @else table-success @endif">{{ $item->wk_49 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_50 > 0) table-danger @else table-success @endif">{{ $item->wk_50 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_51 > 0) table-danger @else table-success @endif">{{ $item->wk_51 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center @if ($item->wk_52 > 0) table-danger @else table-success @endif">{{ $item->wk_52 }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center empty"></td>
                                    <td class="font-size-sm text-center @if ($item->q1 > 0) table-danger @else table-success @endif">{{ $item->q1 }}</td>
                                    <td class="font-size-sm text-center @if ($item->q2 > 0) table-danger @else table-success @endif">{{ $item->q2 }}</td>
                                    <td class="font-size-sm text-center @if ($item->q3 > 0) table-danger @else table-success @endif">{{ $item->q3 }}</td>
                                    <td class="font-size-sm text-center @if ($item->q4 > 0) table-danger @else table-success @endif">{{ $item->q4 }}</td>
                                </tr>
                            @endforeach
                            @endif    
                            </tbody>
                        </table>
                    </div>
                    <div class="row push">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="button" class="btn btn-primary" id="back-to-persons">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button#back-to-persons').click(function() {
            location.href = "/drivers/scorecards";
        });
    });
});
</script>
</x-app-layout>
