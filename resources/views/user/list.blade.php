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
                    <i class="fa fa-users text-primary"></i>
                </span>
                <span class="">Users</span>
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
                    <h3 class="block-title">User List</h3>
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
                    <form action="/user/list" method="POST" autocomplete="off" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-name">User Name :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="user-name" 
                                        id="user-name" 
                                        value="{{ $user_name }}"
                                        placeholder="Enter User Name.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-email">Email :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="user-email" 
                                        id="user-email" 
                                        value="{{ $user_email }}"
                                        placeholder="Enter User Email.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-company">Company :</label>
                                    <select name="user-company" id="user-company" class="form-control">
                                        <option value=""></option>
                                        @foreach ($companies as $company)
                                        <option 
                                            value="{{ $company->id }}"
                                            @if ($user_company == $company->id) {{ __('selected') }} @endif
                                        >
                                            {{ $company->name . ' ('. $company->brand . ')' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-role">Role :</label>
                                    <select name="user-role" id="user-role" class="form-control">
                                        <option value=""></option>
                                        @foreach ($roles as $role)
                                        <option 
                                            value="{{ $role->id }}"
                                            @if ($user_role == $role->id) {{ __('selected') }} @endif
                                        >
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="search-user">&nbsp;</label>
                                    <button class="form-control btn btn-primary ml-auto mr-3" id="search-user" name="search-user">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="add-user">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-user" name="add-user">
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
                                    <th class="text-center" style="width: 15%;">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="d-none d-sm-table-cell text-center">Company</th>
                                    <th class="text-center" style="width: 10%;">Role</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">Created At</th>
                                    <th class="d-none d-sm-table-cell text-center" style="width: 15%;">Updated At</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($users) && count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td class="font-w600 font-size-sm text-left text-primary">{{ $user->name }}</td>
                                    <td class="font-w600 font-size-sm text-left">{{ $user->email }}</td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-left">@if ($user->company){{ $user->company->name }}@endif</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        @if ($user->role == 1)
                                        <span class="badge badge-success">{{ $user->roles->name }}</span>
                                        @elseif ($user->role == 2)
                                        <span class="badge badge-primary">{{ $user->roles->name }}</span>
                                        @elseif ($user->role == 3)
                                        <span class="badge badge-secondary">{{ $user->roles->name }}</span>
                                        @else
                                        <span class="badge badge-dark">{{ $user->roles->name }}</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $user->created_at }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-center">{{ $user->updated_at }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-user" title="View User" data-id="{{ $user->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-user" title="Edit User" data-id="{{ $user->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-user" title="Remove User" data-id="{{ $user->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="9">No Users</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-user-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">User Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="user-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 50%;">Name : </td>
                                                    <td class="text-left text-primary" id="u_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Email : </td>
                                                    <td class="text-left" id="u_email"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Company : </td>
                                                    <td class="text-left" id="u_company"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Role : </td>
                                                    <td class="text-left" id="u_role"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Permissions : </td>
                                                    <td class="text-left" id="u_permissions_table"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Created At : </td>
                                                    <td class="text-left" id="u_created_at"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Updated At : </td>
                                                    <td class="text-left" id="u_updated_at"></td>
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
            $('#user-table').removeClass('d-none');
            $('#modal-user-info .table-responsive .alert').remove();

            $('#u_name').html('');
            $('#u_email').html('');
            $('#u_company').html('');
            $('#u_role').html('');
            $('#u_permissions_table').html('');
            $('#u_created_at').html('');
            $('#u_updated_at').html('');
        }
        $('button.view-user').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/user/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    inital_modal();

                    if (data.type == 'success') {
                        $('#u_name').html(data.user.name);
                        $('#u_email').html(data.user.email);
                        $('#u_company').html(data.user.company ? data.user.company.name + " (" + data.user.company.brand + ")" : '');
                        $('#u_role').html(data.user.roles.name);
                        let permissionHtml = "<ul style='margin-bottom: 0;'>";
                        data.user.permissions.forEach((p) => {
                            permissionHtml += "<li>" + p.permission.action + "</li>";
                        });
                        permissionHtml += "</ul>";
                        $('#u_permissions_table').html(permissionHtml);
                        $('#u_created_at').html(data.user.created_at);
                        $('#u_updated_at').html(data.user.updated_at);
                    } else {
                        $('#user-table').addClass('d-none');
                        $('#modal-user-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                    }
                    $('#modal-user-info').modal('show');
				}
            });
        });
        $('button.edit-user').click(function() {
            var id = $(this).data('id');
            location.href = '/user/edit/' + id;
        });
        $('button.remove-user').click(function() {
            var id = $(this).data('id');
            location.href = '/user/remove/' + id;
        });
        $('button#add-user').click(function() {
            location.href = '/user/add';
        });
    });
});
</script>
</x-app-layout>