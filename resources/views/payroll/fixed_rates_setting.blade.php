<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Payroll</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Fixed Rates</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row justify-content-lg-center">
        <div class="col-lg-8">
            <div class="block block-themed">
                <div class="block-header bg-primary-darker">
                    <h3 class="block-title">Edit Form</h3>
                </div>
                <div class="block-content">
                    <table id="fixed-rate-empty" hidden>
                        <tbody><x-fixed-rate :rate="null" /></tbody>
                    </table>
                    <form class="form-horizonal" action="/payroll/fixed-rates/save" method="POST" autocomplete="off">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="form-group mr-3 mb-1">
                                <button type="button" class="form-control btn btn-secondary ml-auto mr-3" onclick="javascript:window.history.back(-1);">
                                    Cancel
                                </button>
                            </div>
                            <div class="form-group mr-3 mb-1">
                                <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-fixed-rate" name="add-fixed-rate">
                                    <i class="fa fa-plus-circle"></i> Add Fixed Rate
                                </button>
                            </div>
                            <div class="form-group mr-3 mb-1">
                                <button class="form-control btn btn-primary ml-auto mr-3">
                                    <i class="fa fa-save"></i> Update Setting
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-vcenter" id="rates-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 30%;">From Miles</th>
                                        <th class="text-center" style="width: 30%;">To Miles</th>
                                        <th class="text-center" style="width: 30%;">Fixed Rate</th>
                                        <th class="text-center" style="width: 65px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rates as $rate)
                                        <x-fixed-rate :rate="$rate" />
                                    @endforeach
                                    @if (count($rates) == 0)
                                    <tr id="no-fixed-rates">
                                        <td class="text-center" colspan="4">No Fixed Rates</td>
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
        $('button#add-fixed-rate').click(function() {
            $('tr#no-fixed-rates').remove();
            var rate = $("#fixed-rate-empty tbody").html();
            $("table#rates-table tbody").append($(rate));
        });
        $(document).on('click', 'button.remove-fixed-rate', function() {
            $(this).closest('tr').remove();

            if ($('table#rates-table tbody tr').length == 0) {
                $("table#rates-table tbody").append(
                    '<tr id="no-fixed-rates">' +
                    '<td class="text-center" colspan="4">' +
                    'No Fixed Rates' +
                    '</td>' +
                    '</tr>'
                );
            }
        });
    });
});
</script>
</x-app-layout>