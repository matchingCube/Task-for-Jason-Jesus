<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-csv text-primary"></i>
                </span>
                <span class="">GF Statements</span>
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
                    <h3 class="block-title">Import GF_STATEMENT_DETAILS_LHL_ENT</h3>
                </div>
                <div class="block-content">
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
                    <form action="/upload/statement" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="gf-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Year<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <select class="form-control" id="selected-year" name="selected-year" required>
                                                @for ($i = 2019; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}" @if ($i == date('Y')) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Week #<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <select class="form-control" id="selected-week" name="selected-week" required>
                                                @for ($i = 1; $i <= 52; $i++)
                                                    <option value="{{ $i }}" @if ($i == date('W')) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">File for importing<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <input type="file" 
                                                    id="upload-file" 
                                                    name="upload-file" 
                                                    class="form-control" 
                                                    accept=".csv"
                                                    required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;"></td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-upload text-mute"></i> Import St
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="check-st">
                                                <i class="fa fa-check-circle"></i> Check St
                                            </button>
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
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button#check-st').click(function() {
            var year = $("#selected-year").val();
            var week = $("#selected-week").val();
            $.ajax({
				url:        "/upload/check-st",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                year: year,
                                week: week
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        Swal.fire(
                            "Warning",
                            data.msg,
                            "warning"
                        );
                    } else {
                        Swal.fire(
                            "Note",
                            data.msg,
                            "success"
                        );
                    }
                }
            });
        });
    });
});
</script>
</x-app-layout>
