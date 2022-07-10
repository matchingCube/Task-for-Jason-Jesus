<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-truck-moving text-primary"></i>
                </span>
                <span class="">Fleet</span>
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
                    <h3 class="block-title">Fleet Info Form</h3>
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
                    <form class="js-validation" action="/fleet/save" method="POST" id="fleet-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($fleet)){{ $fleet->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-fleets">
                                <i class="fa fa-list"></i> View Fleets
                            </button>
                            <button type="submit" class="btn btn-primary save-fleet">
                                <i class="fa fa-save"></i> Save Fleet
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="fleets-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Tractor #<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <input type="text" class="form-control" name="tractor_id" value="@if (isset($fleet)){{ $fleet->tractor_id }}@else{{ old('tractor_id') }}@endif" placeholder="Enter tractor #.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Model : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="model" value="@if (isset($fleet)){{ $fleet->model }}@else{{ old('model') }}@endif" placeholder="Enter model.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">VIN : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="vin" value="@if (isset($fleet)){{ $fleet->vin }}@else{{ old('vin') }}@endif" placeholder="Enter VIN.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Year : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" class="form-control" name="year" value="@if (isset($fleet)){{ $fleet->year }}@else{{ old('year') }}@endif" placeholder="Enter Year.." min="1990" max="{{ date('Y') }}" />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">License Plate : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="license_plate" value="@if (isset($fleet)){{ $fleet->license_plate }}@else{{ old('license_plate') }}@endif" placeholder="Enter License Plate.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">T Check #: </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="t_check" value="@if (isset($fleet)){{ $fleet->t_check }}@else{{ old('t_check') }}@endif" placeholder="Enter T Check.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Pre Pass : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="pre_pass" value="@if (isset($fleet)){{ $fleet->pre_pass }}@else{{ old('pre_pass') }}@endif" placeholder="Enter Pre Pass.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Service Provider : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="service_provider" value="@if (isset($fleet)){{ $fleet->service_provider }}@else{{ old('service_provider') }}@endif" placeholder="Enter Service Provider.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">QIV : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="qiv" value="@if (isset($fleet)){{ $fleet->qiv }}@else{{ old('qiv') }}@endif" placeholder="Enter QIV.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">BIT : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="js-datepicker form-control" name="bit" value="@if (isset($fleet)){{ $fleet->bit }}@else{{ old('bit') }}@endif" data-week-start="0" data-autoclose="true" data-today-highlight="true" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Book Value : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" class="form-control" name="book_value" value="@if (isset($fleet)){{ $fleet->book_value }}@else{{ old('book_value') }}@endif" placeholder="Enter Book Value.." min="0" max="" step="0.01" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Domicile : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="domicile" value="@if (isset($fleet)){{ $fleet->domicile }}@else{{ old('domicile') }}@endif" placeholder="Enter Domicile.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Domicile Email : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="domicile_email" value="@if (isset($fleet)){{ $fleet->domicile_email }}@else{{ old('domicile_email') }}@endif" placeholder="Enter Domicile Email.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">VEDR : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="vedr" value="@if (isset($fleet)){{ $fleet->vedr }}@else{{ old('vedr') }}@endif" placeholder="Enter VEDR.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">ELD : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="eld" value="@if (isset($fleet)){{ $fleet->eld }}@else{{ old('eld') }}@endif" placeholder="Enter ELD.." />
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
    One.helpers(['datepicker']);
    
    $(document).ready(function() {
        $('button.view-fleets').click(function() {
            location.href = "/fleet/list";
        });
    });
});
</script>
</x-app-layout>