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
                    <i class="fa fa-lock text-primary"></i>
                </span>
                <span class="">Permissions</span>
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
                    <h3 class="block-title">Permission List</h3>
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
                    <form action="/permission/update" method="POST" autocomplete="off" id="permission-form">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <button class="form-control btn btn-primary ml-auto mr-3" id="save-permission" name="save-permission">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-dark table-vcenter" id="permissions-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%;"></th>
                                        @foreach ($permissions as $p)
                                        <th class="text-center">{{ $p->action }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($users) && count($users) > 0)
                                @foreach ($users as $u)
                                    <tr>
                                        <td class="font-w600 font-size-sm text-left text-primary">{{ $u->name }}</td>
                                        @foreach ($permissions as $p)
                                            @php
                                                $flag = false;
                                            @endphp
                                            @foreach ($u->permissions as $up)
                                                @if ($p->id == $up->permission_id)
                                                    <td class="font-w600 font-size-sm text-center text-primary">
                                                        <input type="checkbox" class="form-check-input position-static" name="permissions[]" value="{{$u->id}}-{{$p->id}}" checked />
                                                    </td>
                                                    @php
                                                        $flag = true;
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($flag == false)
                                                <td class="font-w600 font-size-sm text-center text-primary">
                                                    <input type="checkbox" class="form-check-input position-static" name="permissions[]" value="{{$u->id}}-{{$p->id}}" />
                                                </td>
                                            @endif
                                        @endforeach
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        
    });
});
</script>
</x-app-layout>