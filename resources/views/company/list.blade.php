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
                    <i class="fa fa-building text-primary"></i>
                </span>
                <span class="">Companies</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Company List</h3>
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
                    <form action="/company/list" method="POST" autocomplete="off" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="company-brand">Company Brand :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="company-brand" 
                                        id="company-brand" 
                                        value="{{ $company_brand }}"
                                        placeholder="Enter Company Brand.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="company-name">Company Name :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="company-name" 
                                        id="company-name" 
                                        value="{{ $company_name }}"
                                        placeholder="Enter Company Name.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="company-description">Description :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="company-description" 
                                        id="company-description" 
                                        value="{{ $company_description }}"
                                        placeholder="Enter Company Description.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="search-company">&nbsp;</label>
                                    <button class="form-control btn btn-primary ml-auto mr-3" id="search-company" name="search-company">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="add-company">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-company" name="add-company">
                                        <i class="fa fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-dark table-vcenter" id="users-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%;">Brand</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Logo</th>
                                    <th class="d-none d-sm-table-cell text-center">Description</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($companies) && count($companies) > 0)
                            @foreach ($companies as $company)
                                <tr>
                                    <td class="font-w600 font-size-sm text-left text-primary">{{ $company->brand }}</td>
                                    <td class="font-w600 font-size-sm text-left">{{ $company->name }}</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        @if ($company->logo)
                                        <img class="" src="{{ asset('storage/uploads/company/' . $company->logo) }}" alt="" width="48" height="48">
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-left">{{ $company->description }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-company" title="View Company" data-id="{{ $company->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-company" title="Edit Company" data-id="{{ $company->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-company" title="Remove Company" data-id="{{ $company->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="9">No Companies</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-company-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Company Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="company-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 50%;">Brand : </td>
                                                    <td class="text-left text-primary" id="u_brand"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Name : </td>
                                                    <td class="text-left" id="u_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Description : </td>
                                                    <td class="text-left" id="u_description"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Logo : </td>
                                                    <td class="text-left" id="u_logo"></td>
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
            $('#company-table').removeClass('d-none');
            $('#modal-company-info .table-responsive .alert').remove();

            $('#u_brand').html('');
            $('#u_name').html('');
            $('#u_description').html('');
            $('#u_logo').html('');
        }
        $('button.view-company').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/company/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    inital_modal();

                    if (data.type == 'success') {
                        $('#u_brand').html(data.company.brand);
                        $('#u_name').html(data.company.name);
                        $('#u_description').html(data.company.description);
                        $('#u_logo').html('<img src="/storage/uploads/company/' + data.company.logo + '" width="150" height="150" />');
                    } else {
                        $('#company-table').addClass('d-none');
                        $('#modal-company-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                    }
                    $('#modal-company-info').modal('show');
				}
            });
        });
        $('button.edit-company').click(function() {
            var id = $(this).data('id');
            location.href = '/company/edit/' + id;
        });
        $('button.remove-company').click(function() {
            var id = $(this).data('id');
            location.href = '/company/remove/' + id;
        });
        $('button#add-company').click(function() {
            location.href = '/company/add';
        });
    });
});
</script>
</x-app-layout>