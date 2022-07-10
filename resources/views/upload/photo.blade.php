<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-images text-primary"></i>
                </span>
                <span class="">Driver Photo</span>
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
                <div class="block-header">
                    <h3 class="block-title">Upload Driver Photo</h3>
                </div>
                <div class="block-content">
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>You can choose <b>multiple</b> photo files.</p>
                        </div>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{!! session('status') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <div class="row push">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <p class="font-size-sm text-muted text-right">
                                Driver Photo File:
                            </p>
                        </div>
                        <div class="col-xl-4 col-lg-8 col-md-4 col-sm-4 col-xs-12 overflow-hidden">
                            <form action="/upload/photo" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" id="upload-file" name="upload-files[]"  accept="image/*" multiple>
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
            </div>
        </div>
    </div>
</div>
</x-app-layout>