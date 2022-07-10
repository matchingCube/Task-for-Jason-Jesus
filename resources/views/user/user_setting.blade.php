<x-app-layout>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-user-edit text-primary"></i>
                </span>
                <span class="">User Setting</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title">User Info</h3>
                </div>
                <div class="block-content">
                    <div class="row push">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable col-lg-12 col-md-12" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{!! session('status') !!}</p>
                        </div>
                    @endif
                    </div>
                    
                    <form class="js-validation" action="" method="POST" id="user-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group text-right">
                                <label>Username <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 form-group">
                                <input type="text" class="form-control" id="val-username" name="val-username" placeholder="Enter a username.." value="{{ $user->user_name }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group text-right">
                                <label>Email <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 form-group">
                                <input type="email" class="form-control" id="val-email" name="val-email" placeholder="Your valid email.." value="{{ $user->user_email }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group text-right">
                                <label>New Password <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 form-group">
                                <input type="password" class="form-control" id="val-password" name="val-password" placeholder="Enter your password.." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group text-right">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 form-group">
                                <input type="password" class="form-control" id="val-confirm-password" name="val-confirm-password" placeholder="Confirm your password!" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group text-right">
                            </div>
                            <div class="col-md-4 form-group">
                                <button type="button" class="btn btn-primary" id="form-submit">Submit</button>
                            </div>
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
        $('#form-submit').click(function() {
            pwd = $('#val-password').val();
            confirm_pwd = $('#val-confirm-password').val();
            if ((pwd == "" || confirm_pwd == "") || pwd != confirm_pwd) {
                alert("Confirm Password is not match with password!");
                $('#val-confirm-password').focus();
                return false;
            } else {
                $('#user-form').submit();
            }
        });
    });
});    
</script>
</x-app-layout>