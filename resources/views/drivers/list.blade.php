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
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Drivers</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Manage Drivers</a>
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
                <div class="block-header bg-primary">
                    <h3 class="block-title">Drivers List</h3>
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
                    <form method="POST" id="drivers-form" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <label for="driver-name">Driver Name :</label>
                                <input type="text"
                                        class="form-control"
                                        name="driver-name"
                                        id="driver-name"
                                        value="@if (isset($driver_name)){{ $driver_name }}@endif"
                                />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <label for="work-status">Work Status :</label>
                                <select class="form-control" id="work-status" name="work-status" required>
                                    <option value="1" @if (isset($work_status) && $work_status == 1) selected @endif>Working now</option>
                                    <option value="0" @if (isset($work_status) && $work_status == 0) selected @endif>Not longer working</option>
                                </select>
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
                                <label for="add-driver">&nbsp;</label>
                                <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-driver" name="add-driver">
                                    <i class="fa fa-plus"></i> Add Driver
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <label for="add-bulk-drivers">&nbsp;</label>
                                <button type="button" class="form-control btn btn-success ml-auto mr-3" id="add-bulk-drivers" name="add-bulk-drivers">
                                    <i class="fa fa-upload"></i> Add Bulk Drivers
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group">
                                <label for="remove-bulk-drivers">&nbsp;</label>
                                <button type="button" class="form-control btn btn-danger ml-auto mr-3" id="remove-bulk-drivers" name="remove-bulk-drivers">
                                    <i class="fa fa-trash"></i> Remove Bulk Drivers
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-dark table-vcenter" id="drivers-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 20px;">
                                        <input type="checkbox" name="all-check" id="all-check">
                                    </th>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th class="text-center" style="width: 100px;"><i class="far fa-user"></i></th>
                                    <th class="text-center" style="width: 10%;">Driver ID</th>
                                    <th class="text-center" style="width: 15%;">Name</th>
                                    <th class="text-center" style="width: 15%;">Email</th>
                                    <th class="text-center" style="width: 10%;">Phone</th>
                                    <th class="text-center" style="width: 10%;">License</th>
                                    <th class="text-center">Address</th>
                                    <th class="d-none d-md-table-cell text-center" style="width: 100px;">Actions</th>
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
                                    <td class="text-center">
                                        @if ($driver->photo)
                                        <img class="img-avatar img-avatar48" src="{{ asset('storage/uploads/driver/' . $driver->photo) }}" alt="">
                                        @else
                                        <img class="img-avatar img-avatar48" src="{{ asset('media/photos/drivers/default.jpg') }}" alt="">
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <strong class="text-primary">{{ $driver->driver_id }}</strong>
                                    </td>
                                    <td class="font-w600 font-size-sm text-left">
                                        {{ $driver->driver_name }}
                                        @if ($driver->work_status == 0)
                                        <span class="font-w600 badge badge-pill badge-danger">No longer working</span>
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm text-center">
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
                                            <button type="button" class="btn btn-sm btn-dark view-driver" title="View Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-driver" title="Edit Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-driver" title="Remove Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="font-w600 font-size-sm text-center">No Drivers</td>
                                </tr>
                            @endif    
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
                <!-- Driver Info: Pop Out Block Modal -->
                <div class="modal fade" id="modal-driver-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Driver Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-dark table-vcenter" id="driver-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w600 text-right">Driver ID : </td>
                                                    <td class="text-left text-primary" id="t_driver_id"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Driver Name : </td>
                                                    <td class="text-left" id="t_driver_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Photo : </td>
                                                    <td class="text-left" id="t_photo"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Email : </td>
                                                    <td class="text-left" id="t_email"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Phone # : </td>
                                                    <td class="text-left" id="t_phone"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">License : </td>
                                                    <td class="text-left" id="t_license"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Address : </td>
                                                    <td class="text-left" id="t_address"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Price Per Mile : </td>
                                                    <td class="text-left" id="t_price_per_mile"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Work Status : </td>
                                                    <td class="text-left" id="t_work_status"></td>
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
                                                Bulk Drivers File:
                                            </p>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12 overflow-hidden">
                                            <form action="/drivers/upload" method="POST" enctype="multipart/form-data">
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
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#driver-table').removeClass('d-none');
            $('#modal-driver-info .table-responsive .alert').remove();

            $('#t_driver_id').html('');
            $('#t_driver_name').html('');
            $('#t_photo').html('');
            $('#t_email').html('');
            $('#t_phone').html('');
            $('#t_license').html('');
            $('#t_address').html('');
            $('#t_price_per_mile').html('');
            $('#t_work_status').html('');
        }
        $('button.view-driver').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/drivers/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        inital_modal();
                        
                        $('#t_driver_id').html(data.driver.driver_id);
                        $('#t_driver_name').html(data.driver.driver_name);
                        if (data.driver.photo) {
                            $('#t_photo').html('<img src="/storage/uploads/driver/' + data.driver.photo + '" width="150" height="150" />');
                        } else {
                            $('#t_photo').html('<img src="/media/photos/drivers/default.jpg" width="150" height="150" />');
                        }
                        $('#t_email').html(data.driver.email);
                        $('#t_phone').html('<span class="badge badge-pill badge-violet">' + (data.driver.phone ? '+' + data.driver.phone : '' )+ '</span>');
                        $('#t_license').html('<span class="badge badge-success">' + (data.driver.license ? data.driver.license : '') + '</span>');
                        $('#t_address').html(data.driver.address);
                        $('#t_price_per_mile').html(data.driver.price_per_mile);
                        $('#t_work_status').html(data.driver.work_status ? 'Working' : '<span class="font-w600 text-danger">No longer working</span>');
                    } else {
                        inital_modal();
                        
                        $('#driver-table').addClass('d-none');
                        $('#modal-driver-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                    }
                    $('#modal-driver-info').modal('show');
				}
            });
        });
        $('button.edit-driver').click(function() {
            var id = $(this).data('id');
            location.href = '/drivers/edit/' + id;
        });
        $('button.remove-driver').click(function() {
            Swal.fire({
                title: 'Are you sure?',
                html: "Do you want to remove this driver?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Remove!'
            }).then((result) => {
                if (result.value) {
                    var id = $(this).data('id');
                    location.href = '/drivers/remove/' + id;
                }
            });
        });
        $('button#add-driver').click(function() {
            location.href = '/drivers/add';
        });
        $('button#add-bulk-drivers').click(function() {
            $('#modal-upload-form').modal('show');
        });
        $('input[type=checkbox][name=all-check]').click(function() {
            var drivers = $('input[type=checkbox][name="checked-drivers[]"]');
            for (let i = 0; i < drivers.length; i++) {
                drivers[i].checked = this.checked;
            }
        });
        $('button#search-drivers').click(function() {
            $('form#drivers-form').attr('action', '/drivers');
            $('form#drivers-form').submit();
        });
        $('button#remove-bulk-drivers').click(function() {
            var drivers = $('input[type=checkbox][name="checked-drivers[]"]:checked');
            if (!drivers || drivers.length == 0) {
                Swal.fire(
                    "Warning",
                    "Please choose drivers.",
                    "warning"
                );

                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                html: "Do you want to remove chosen drivers?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Remove!'
            }).then((result) => {
                if (result.value) {
                    $('form#drivers-form').attr('action', '/drivers/remove-bulk');
                    $('form#drivers-form').submit();
                }
            });
        });
    });
});
</script>
</x-app-layout>