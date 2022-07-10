<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-truck-moving text-primary"></i>
                </span>
                <span class="">Tractors Information</span>
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
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">Tractors List</h3>
                </div>
                <div class="block-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    <div class="table-responsive push text-right">
                        <button class="btn btn-dark" id="add-tractor">
                            <i class="fa fa-plus"></i> Add Tractor
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-vcenter" id="tractors-table">
                            <thead>
                                <tr>
                                    <th class="text-center">Tractor #</th>
                                    <th class="text-center" style="width: 10%;">Model</th>
                                    <th class="text-center" style="width: 10%;">VIN</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">Year</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">License Plate</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 10%;">BIT</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">Oil Changes</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">Smart Witness Serial</th>
                                    <th class="d-none d-md-table-cell text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($tractors) && count($tractors) > 0)
                            @foreach ($tractors as $tractor)
                                <tr>
                                    <td class="font-w600 font-size-sm text-center text-primary">{{ $tractor->tractor_id }}</td>
                                    <td class="font-w600 font-size-sm text-center">{{ $tractor->model }}</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <span class="badge badge-success">{{ $tractor->vin }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $tractor->year }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">
                                        <span class="badge badge-primary">{{ $tractor->license_plate }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center {{ $tractor->bit_color }}">{{ $tractor->bit_date }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-right">{{ $tractor->oil_changes }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $tractor->smart_witness_serial }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-tractor" title="View Tractor" data-id="{{ $tractor->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-tractor" title="Edit Tractor" data-id="{{ $tractor->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-tractor" title="Remove Tractor" data-id="{{ $tractor->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @endif    
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-tractor-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Tractor Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="tractor-table">
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
                                                    <td class="font-w800 text-right">Miles Of Last BIT : </td>
                                                    <td class="text-left" id="t_last_bit_miles"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">BIT : </td>
                                                    <td class="text-left" id="t_bit"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Oil Changes : </td>
                                                    <td class="text-left" id="t_oil_changes"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Insurance Book Value : </td>
                                                    <td class="text-left" id="t_insurance_book_value"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Smart Witness Serial : </td>
                                                    <td class="text-left" id="t_smart_witness_serial"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Omnitracs Device ID : </td>
                                                    <td class="text-left" id="t_omnitracs_device_id"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Pre Pass : </td>
                                                    <td class="text-left" id="t_pre_pass"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">T Check : </td>
                                                    <td class="text-left" id="t_t_check"></td>
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
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#tractor-table').removeClass('d-none');
            $('#modal-tractor-info .table-responsive .alert').remove();

            $('#t_tractor_id').html('');
            $('#t_model').html('');
            $('#t_vin').html('');
            $('#t_year').html('');
            $('#t_license_plate').html('');
            $('#t_last_bit_miles').html('');
            $('#t_bit').html('');
            $('#t_bit').removeClass('text-warning');
            $('#t_oil_changes').html('');
            $('#t_insurance_book_value').html('');
            $('#t_smart_witness_serial').html('');
            $('#t_omnitracs_device_id').html('');
            $('#t_pre_pass').html('');
            $('#t_t_check').html('');
        }
        $('button.view-tractor').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/tractors/get-tractor",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        inital_modal();
                        
                        $('#t_tractor_id').html(data.tractor.tractor_id);
                        $('#t_model').html(data.tractor.model);
                        $('#t_vin').html('<span class="badge badge-success">' + (data.tractor.vin == null ? '' : data.tractor.vin) + '</span>');
                        $('#t_year').html(data.tractor.year);
                        $('#t_license_plate').html('<span class="badge badge-info">' + (data.tractor.license_plate == null ? '' : data.tractor.license_plate) + '</span>');
                        $('#t_last_bit_miles').html(data.tractor.last_bit_miles);
                        $('#t_bit').html(data.tractor.bit_date);
                        $('#t_bit').addClass(data.tractor.bit_color);
                        $('#t_oil_changes').html(data.tractor.oil_changes);
                        $('#t_insurance_book_value').html('$ ' + parseFloat(data.tractor.insurance_book_value).toLocaleString('en'));
                        $('#t_smart_witness_serial').html(data.tractor.smart_witness_serial);
                        $('#t_omnitracs_device_id').html(data.tractor.omnitracs_device_id);
                        $('#t_pre_pass').html(data.tractor.pre_pass);
                        $('#t_t_check').html(data.tractor.t_check);

                        $('#modal-tractor-info').modal('show');
                    } else {
                        inital_modal();
                        
                        $('#tractor-table').addClass('d-none');
                        $('#modal-tractor-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                        $('#modal-tractor-info').modal('show');
                    }
				}
            });
        });
        $('button.edit-tractor').click(function() {
            var id = $(this).data('id');
            location.href = '/tractors/edit/' + id;
        });
        $('button.remove-tractor').click(function() {
            var id = $(this).data('id');
            location.href = '/tractors/remove/' + id;
        });
        $('button#add-tractor').click(function() {
            location.href = '/tractors/add';
        });
    });
});
</script>
</x-app-layout>