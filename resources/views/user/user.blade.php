<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-user text-primary"></i>
                </span>
                <span class="">User</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">User Info Form</h3>
                </div>
                <div class="block-content">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <form class="js-validation" action="/user/save" method="POST" id="user-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($user)){{ $user->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-users">
                                <i class="fa fa-list"></i> View Users
                            </button>
                            <button type="submit" class="btn btn-primary save-user">
                                <i class="fa fa-save"></i> Save User
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="user-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Name<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="name" value="@if (isset($user)){{ $user->name }}@else{{ old('name') }}@endif" placeholder="Enter name.." required />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Email<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="email" class="form-control" name="email" value="@if (isset($user)){{ $user->email }}@else{{ old('email') }}@endif" placeholder="Enter email.." required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Password : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="password" class="form-control" name="password" value="@if (!isset($user)){{ old('password') }}@endif" placeholder="Enter password.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Confirm password : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="password" class="form-control" name="confirm-password" value="@if (!isset($user)){{ old('confirm-password') }}@endif" placeholder="Enter confirm password.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Company<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select name="company" id="company" class="form-control" required>
                                                @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" @if (isset($user) && ($user->company_id == $company->id)) {{ __('selected') }} @elseif (old('company') == $company->id) {{ __('selected') }} @endif>{{ $company->name . ' (' . $company->brand . ')' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Role<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select name="role" id="role" class="form-control" required>
                                                @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" @if (isset($user) && ($user->role == $role->id)) {{ __('selected') }} @elseif (old('role') == $role->id) {{ __('selected') }} @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    @if (isset($user))
                                    <tr>
                                        <td class="font-w800 text-right  align-top" style="width: 20%;">Permissions : </td>
                                        <td class="text-left  align-top" style="width: 30%;">
                                            @foreach ($permissions as $permission)
                                                @php
                                                    $flag = false
                                                @endphp
                                                @foreach ($user->permissions as $up)
                                                    @if ($permission->id == $up->permission->id)
                                                        @php
                                                            $flag = true
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="permissions[]" id="p-{{ $permission->id }}" value="{{ $permission->id }}" @if($flag) {{ 'checked' }}@endif/>
                                                    <label class="form-check-label" for="p-{{ $permission->id }}">{{ $permission->action }}</label>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button.view-users').click(function() {
            location.href = "/user/list";
        });
    });
});
</script>
</x-app-layout>