<x-guest-layout>
    <x-jet-validation-errors class="mb-4" />
    <!-- Main Container -->
    <main id="main-container">

        <!-- Page Content -->
        <div class="bg-image" style="background-image: url('/media/photos/Header-Photo-Update-4.jpg');">
            <div class="hero-static">
                <div class="content">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4">
                            <!-- Sign Up Block -->
                            <div class="block block-themed block-fx-shadow mb-0">
                                <div class="block-header bg-success">
                                    <h3 class="block-title">Create Account</h3>
                                    <div class="block-options">
                                        <a class="btn-block-option font-size-sm" href="javascript:void(0)" data-toggle="modal" data-target="#one-signup-terms">View Terms</a>
                                        <a class="btn-block-option" href="{{ route('login') }}" data-toggle="tooltip" data-placement="left" title="Sign In">
                                            <i class="fa fa-sign-in-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="p-sm-3 px-lg-4 py-lg-5">
                                        <h1 class="mb-2">{{ config('app.name') }}</h1>
                                        <p>Please fill the following details to create a new account.</p>

                                        <!-- Sign Up Form -->
                                        <!-- jQuery Validation (.js-validation-signup class is initialized in js/pages/op_auth_signup.min.js which was auto compiled from _es6/pages/op_auth_signup.js) -->
                                        <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                        
                                        <form class="js-validation-signup" action="{{ route('register') }}" method="POST">
                                        @csrf
                                            <div class="py-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-lg form-control-alt" id="name" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Username">
                                                </div>
                                                <div class="form-group">
                                                    <input type="email" class="form-control form-control-lg form-control-alt" id="email" name="email" :value="old('email')" required placeholder="Email">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control form-control-lg form-control-alt" id="password" name="password" required autocomplete="new-password" placeholder="Password">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control form-control-lg form-control-alt" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Password Confirm">
                                                </div>
                                                
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6 col-xl-5">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        <i class="fa fa-fw fa-plus mr-1"></i> Sign Up
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control text-right">
                                                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                                        {{ __('Already registered?') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END Sign Up Form -->
                                    </div>
                                </div>
                            </div>
                            <!-- END Sign Up Block -->
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
