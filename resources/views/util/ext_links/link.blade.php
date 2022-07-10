<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-link text-primary"></i>
                </span>
                <span class="">Website Information</span>
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
                    <h3 class="block-title">Website Info Form</h3>
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
                    <form class="js-validation" action="/util/ext-links/save" method="POST" id="link-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($link)){{ $link->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-links">
                                <i class="fa fa-list"></i> View Links
                            </button>
                            <button type="submit" class="btn btn-primary save-link">
                                <i class="fa fa-save"></i> Save Link
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="link-table">
                                <tbody>
                                    @if (isset($link))
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Link #<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 80%;">
                                            <input type="text" class="form-control" name="id" value="{{ $link->id }}" readonly/>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Name<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="name" value="@if (isset($link)){{ $link->name }}@endif" placeholder="Enter website name.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">URL<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="url" value="@if (isset($link)){{ $link->url }}@else {{ 'http://' }}@endif" placeholder="Enter URL.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Description : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <textarea name="description" rows="10" class="form-control" placeholder="Enter description..">@if (isset($link)){{ $link->description }}@endif</textarea>
                                        </td>
                                    </tr>
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
    $('button.view-links').click(function() {
        location.href = "/util/ext-links";
    });
});
</script>
</x-app-layout>