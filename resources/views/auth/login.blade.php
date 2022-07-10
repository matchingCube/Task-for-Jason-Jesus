<x-guest-layout>
  <!-- Main Container -->
  <main id="main-container">

    <!-- Page Content -->
    <div class="bg-image" style="background-image: url('/media/photos/Header-Photo-Update-4.jpg');">
      <div class="hero-static">
        @if (session('status'))
          <div class="alert alert-info alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {{ session('status') }}</p>
          </div>
        @endif

        <div class="content">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
              <!-- Sign In Block -->
              <div class="block block-themed block-fx-shadow mb-0">
                <div class="block-header">
                  <h3 class="block-title">Sign In</h3>
                  <div class="block-options">
                    @if (Route::has('password.request'))
                      <a class="btn-block-option font-size-sm"
                        href="{{ route('password.request') }}">{{ __('Reset password') }}</a>
                    @endif
                    <!-- <a class="btn-block-option" href="{{ route('register') }}" data-toggle="tooltip" data-placement="left" title="New Account">
                                            <i class="fa fa-user-plus"></i>
                                        </a> -->
                  </div>
                </div>
                <div class="block-content">
                  <div class="p-sm-3 px-lg-4 py-lg-5">
                    <h1 class="mb-2">{{ config('app.name') }}</h1>
                    <p>Welcome, please login.</p>

                    @if ($errors->any())
                      @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissable" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {{ $error }}</p>
                        </div>
                      @endforeach
                    @endif

                    <!-- Sign In Form -->
                    <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js) -->
                    <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                    <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                      @csrf
                      <div class="py-3">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-alt form-control-lg" id="email"
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Email">
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control form-control-alt form-control-lg" id="password"
                            name="password" required autocomplete="current-password" placeholder="Password">
                        </div>
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                            <!-- <input type="checkbox" class="custom-control-input" id="remember_me" name="remember"> -->
                            <label class="custom-control-label font-w400"
                              for="remember_me">{{ __('Remember me') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-6 col-xl-5">
                          <button type="submit" class="btn btn-block btn-primary">
                            <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                          </button>
                        </div>
                      </div>
                    </form>
                    <!-- END Sign In Form -->
                  </div>
                </div>
              </div>
              <!-- END Sign In Block -->
            </div>
          </div>
        </div>
        <div class="content content-full font-size-sm text-white text-center">
          <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
        </div>
      </div>
    </div>
    <!-- END Page Content -->

  </main>
  <!-- END Main Container -->

</x-guest-layout>
