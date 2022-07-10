<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
</style>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Payroll</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Rates</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Drivers List</h3>
        </div>
        <div class="block-content">
            @if (session('status'))
                <div class="alert alert-success alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p><i class="fa fa-fw fa-info-circle"></i> {!! session('status') !!}</p>
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
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                    <p class="font-size-sm text-muted">
                        <i class="fa fa-info-circle"></i> You can set fixed rates per mileage <strong>(it will affect globally.)</strong> and prices per mile for each driver.
                    </p>
                </div>
            </div>

            <form action="/payroll/rates" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="driver-id">Driver ID :</label>
                            <input type="text" name="driver-id" id="driver-id" class="form-control" value="{{ $driver_id }}"/>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="driver-name">Driver Name :</label>
                            <input type="text" name="driver-name" id="driver-name" class="form-control" value="{{ $driver_name }}"/>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="min-rate">Min Rate :</label>
                            <input type="number" name="min-rate" id="min-rate" class="form-control" min="0" step="0.0001" value="{{ $min_rate }}"/>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="max-rate">Max Rate :</label>
                            <input type="number" name="max-rate" id="max-rate" class="form-control" min="0" max="1000000" step="0.0001" value="{{ $max_rate }}"/>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="search-driver">&nbsp;</label>
                            <button class="form-control btn btn-primary ml-auto mr-3" id="search-driver" name="search-driver"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="fixed-rates-setting">&nbsp;</label>
                            <button type="button" class="form-control btn btn-success fixed-rates-setting" id="fixed-rates-setting" name="fixed-rates-setting"><i class="fa fa-cog"></i> Fixed Rate Setting</button>
                        </div>
                    </div>
                </div>
            </form>
            
            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">
                            <strong><i class="far fa-user"></i></strong>
                        </th>
                        <th class="d-none d-md-table-cell text-center" style="width: 20%;"><strong>Driver ID</strong></th>
                        <th class="text-center"><strong>Driver Name</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Work Status</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Price Per Mile</strong></th>
                        <th class="text-center" style="width: 100px;"><strong>Actions</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rates as $idx => $rate)
                    <tr>
                        <td class="text-center">
                            {{ $idx + 1 }}
                        </td>
                        <td class="d-none d-md-table-cell text-center">
                            {{ $rate->driver_id }}
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            {{ $rate->driver_name }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($rate->work_status == 0)
                                <span class="badge badge-pill badge-danger">No longer working</span>
                            @else
                                <span class="badge badge-pill badge-success">Working now</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            $ {{ number_format($rate->price_per_mile, 2) }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-dark edit-rate" data-toggle="tooltip" title="Edit Rate" data-id="{{ $rate->id }}">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark remove-rate" data-toggle="tooltip" title="Delete Rate" data-id="{{ $rate->id }}">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark change-workstatus" data-toggle="tooltip" title="Change Work Status" data-id="{{ $rate->id }}">
                                    <i class="fa fa-exchange-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $("button.edit-rate").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/rate/" + id;
        });

        $("button.remove-rate").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/rate/remove/" + id;
        });
        
        $("button.fixed-rates-setting").click(function() {
            window.location.href = "/payroll/fixed-rates";
        });

        $("button.change-workstatus").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/work-status/save/" + id;
        });
    });
});
</script>
</x-app-layout>
