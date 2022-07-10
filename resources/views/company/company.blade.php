<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-building text-primary"></i>
                </span>
                <span class="">Company</span>
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
                    <h3 class="block-title">Company Info Form</h3>
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
                    <form class="js-validation" action="/company/save" method="POST" id="company-form" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($company)){{ $company->id }}@endif" />
                        <div class="table-responsive push text-right">
                            @if (Auth::user()->role == 1)
                            <button type="button" class="btn btn-dark view-companies">
                                <i class="fa fa-list"></i> View Companies
                            </button>
                            @endif
                            <button type="submit" class="btn btn-primary save-company">
                                <i class="fa fa-save"></i> Save Company
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="company-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Brand<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="brand" value="@if (isset($company)){{ $company->brand }}@else{{ old('brand') }}@endif" placeholder="Enter Brand.." required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Name<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="name" value="@if (isset($company)){{ $company->name }}@else{{ old('name') }}@endif" placeholder="Enter Name.." required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Description : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <textarea name="description" rows="10" class="form-control" placeholder="Enter description..">@if (isset($company)){{ $company->description }}@endif</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Logo : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="file" id="logo" name="logo" accept="image/*" onchange="readURL(this);" />
                                            @if (isset($company) && $company->logo)
                                            <img id="blah" src="{{ asset('storage/uploads/company/' . $company->logo) }}" alt="" width="150" height="150" />
                                            @else
                                            <img id="blah" style="max-width: 150px" />
                                            @endif
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            $('#blah').attr('src', '');
        }
    }

    jQuery(function($){
        $(document).ready(function() {
            $('button.view-companies').click(function() {
                location.href = "/company/list";
            });
        });
    });
</script>
</x-app-layout>