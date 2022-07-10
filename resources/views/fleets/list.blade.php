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
                    <i class="fa fa-truck-moving text-primary"></i>
                </span>
                <span class="">Fleets</span>
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
                    <h3 class="block-title">Fleet List</h3>
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
                    <form action="/fleet/list" method="POST" autocomplete="off" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="tractor-id">Tractor ID :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="tractor-id" 
                                        id="tractor-id" 
                                        value="{{ $tractor_id }}"
                                        placeholder="Enter Tractor ID.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="model">Model :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="model" 
                                        id="model" 
                                        value="{{ $model }}"
                                        placeholder="Enter Model.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="service_provider">Service Provider :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="service_provider" 
                                        id="service_provider" 
                                        value="{{ $service_provider }}"
                                        placeholder="Enter Service Provider.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="search-fleet">&nbsp;</label>
                                    <button class="form-control btn btn-primary ml-auto mr-3" id="search-fleet" name="search-fleet">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row justify-content-end">
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <button type="button" class="form-control btn btn-success ml-auto mr-3" id="upload-fleets" name="upload-fleets">
                                    <i class="fa fa-upload"></i> Upload
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-fleet" name="add-fleet">
                                    <i class="fa fa-plus"></i> Add Fleet
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-dark table-vcenter" id="fleets-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%;">Tractor #</th>
                                    <th class="text-center" style="width: 15%;">Model</th>
                                    <th class="text-center" style="width: 10%;">VIN</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Year</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">License Plate</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">BIT</th>
                                    <th class="d-none d-sm-table-cell text-center">Service Provider</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($fleets) && count($fleets) > 0)
                            @foreach ($fleets as $fleet)
                                <tr>
                                    <td class="font-w600 font-size-sm text-center text-primary">{{ $fleet->tractor_id }}</td>
                                    <td class="font-w600 font-size-sm text-center">{{ $fleet->model }}</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <span class="badge badge-success">{{ $fleet->vin }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $fleet->year }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">
                                        <span class="badge badge-primary">{{ $fleet->license_plate }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center {{ $fleet->bit_color }}">{{ $fleet->bit_date }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $fleet->service_provider }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-fleet" title="View Fleet" data-id="{{ $fleet->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-fleet" title="Edit Fleet" data-id="{{ $fleet->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-fleet" title="Remove Fleet" data-id="{{ $fleet->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="9">No Fleets</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-fleet-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Fleet Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="fleet-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 50%;">Tractor # : </td>
                                                    <td class="text-left text-primary" id="t_tractor_id"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Model : </td>
                                                    <td class="text-left" id="t_model"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">VIN : </td>
                                                    <td class="text-left" id="t_vin"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Year : </td>
                                                    <td class="text-left" id="t_year"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">License Plate : </td>
                                                    <td class="text-left" id="t_license_plate"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">T Check : </td>
                                                    <td class="text-left" id="t_t_check"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Pre Pass : </td>
                                                    <td class="text-left" id="t_pre_pass"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Service Provider : </td>
                                                    <td class="text-left" id="t_service_provider"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">QIV : </td>
                                                    <td class="text-left" id="t_qiv"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">BIT : </td>
                                                    <td class="text-left" id="t_bit"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Domicile : </td>
                                                    <td class="text-left" id="t_domicile"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Domicile Email : </td>
                                                    <td class="text-left" id="t_domicile_email"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Book Value : </td>
                                                    <td class="text-left" id="t_book_value"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">VEDR : </td>
                                                    <td class="text-left" id="t_vedr"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">ELD : </td>
                                                    <td class="text-left" id="t_eld"></td>
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
                <!-- Upload Form: Pop Out Block Modal -->
                <div class="modal fade" id="modal-upload-form" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Upload Form</h3>
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
                                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> While uploading data, old data will be updated !</p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <p class="font-size-sm text-muted text-right">
                                                Fleets File:
                                            </p>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12 overflow-hidden">
                                            <form action="/fleet/upload" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" id="upload-file" name="upload-file" accept=".xlsx,.xls,.csv">
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
    $(document).ready(function() {
        function inital_modal() {
            $('#fleet-table').removeClass('d-none');
            $('#modal-fleet-info .table-responsive .alert').remove();

            $('#t_tractor_id').html('');
            $('#t_model').html('');
            $('#t_vin').html('');
            $('#t_year').html('');
            $('#t_license_plate').html('');
            $('#t_t_check').html('');
            $('#t_pre_pass').html('');
            $('#t_service_provider').html('');
            $('#t_qiv').html('');
            $('#t_bit').html('');
            $('#t_bit').removeClass('text-warning text-danger');
            $('#t_domicile').html('');
            $('#t_domicile_email').html('');
            $('#t_book_value').html('');
            $('#t_vedr').html('');
            $('#t_eld').html('');
        }
        $('button.view-fleet').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/fleet/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    inital_modal();

                    if (data.type == 'success') {
                        $('#t_tractor_id').html(data.fleet.tractor_id);
                        $('#t_model').html(data.fleet.model);
                        $('#t_vin').html('<span class="badge badge-success">' + (data.fleet.vin == null ? '' : data.fleet.vin) + '</span>');
                        $('#t_year').html(data.fleet.year);
                        $('#t_license_plate').html('<span class="badge badge-info">' + (data.fleet.license_plate == null ? '' : data.fleet.license_plate) + '</span>');
                        $('#t_t_check').html(data.fleet.t_check);
                        $('#t_pre_pass').html(data.fleet.pre_pass);
                        $('#t_service_provider').html(data.fleet.service_provider);
                        $('#t_qiv').html(data.fleet.qiv);
                        $('#t_bit').html(data.fleet.bit_date);
                        $('#t_bit').addClass(data.fleet.bit_color);
                        $('#t_domicile').html(data.fleet.domicile);
                        $('#t_domicile_email').html(data.fleet.domicile_email);
                        $('#t_book_value').html('$ ' + parseFloat(data.fleet.book_value).toLocaleString('en'));
                        $('#t_vedr').html(data.fleet.vedr);
                        $('#t_eld').html(data.fleet.eld);
                        
                        $('#modal-fleet-info').modal('show');
                    } else {
                        $('#fleet-table').addClass('d-none');
                        $('#modal-fleet-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                        $('#modal-fleet-info').modal('show');
                    }
				}
            });
        });
        $('button.edit-fleet').click(function() {
            var id = $(this).data('id');
            location.href = '/fleet/edit/' + id;
        });
        $('button.remove-fleet').click(function() {
            var id = $(this).data('id');
            location.href = '/fleet/remove/' + id;
        });
        $('button#add-fleet').click(function() {
            location.href = '/fleet/add';
        });
        $('button#upload-fleets').click(function() {
            $('#modal-upload-form').modal('show');
        });
    });
});
</script>
</x-app-layout>