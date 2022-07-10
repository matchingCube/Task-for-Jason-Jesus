<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-truck-moving text-primary"></i>
                </span>
                <span class="">Tractor Information</span>
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
                    <h3 class="block-title">Tractor Info Form</h3>
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
                    <form class="js-validation" action="/tractors/save" method="POST" id="tractor-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($tractor)){{ $tractor->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-tractors">
                                <i class="fa fa-list"></i> View Tractors
                            </button>
                            <button type="submit" class="btn btn-primary save-tractor">
                                <i class="fa fa-save"></i> Save Tractor
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="tractors-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Tractor #<span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <input type="text" class="form-control" name="tractor_id" value="@if (isset($tractor)){{ $tractor->tractor_id }}@endif" placeholder="Enter tractor #.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Model : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="model" value="@if (isset($tractor)){{ $tractor->model }}@endif" placeholder="Enter model.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">VIN : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="vin" value="@if (isset($tractor)){{ $tractor->vin }}@endif" placeholder="Enter VIN.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Year : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" class="form-control" name="year" value="@if (isset($tractor)){{ $tractor->year }}@endif" placeholder="Enter Year.." min="1990" max="{{ date('Y') }}" />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">License Plate : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="license_plate" value="@if (isset($tractor)){{ $tractor->license_plate }}@endif" placeholder="Enter License Plate.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Miles Of Last BIT : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" class="form-control" name="last_bit_miles" value="@if (isset($tractor)){{ $tractor->last_bit_miles }}@endif" placeholder="Enter Miles Of Last BIT.." min="0" max="" step="0.01" />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">BIT : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="js-datepicker form-control" name="bit" value="@if (isset($tractor)){{ $tractor->bit }}@endif" data-week-start="0" data-autoclose="true" data-today-highlight="true" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Oil Changes : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="oil_changes" value="@if (isset($tractor)){{ $tractor->oil_changes }}@endif" placeholder="Enter Oil Changes.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Insurance Book Value : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" class="form-control" name="insurance_book_value" value="@if (isset($tractor)){{ $tractor->insurance_book_value }}@endif" placeholder="Enter Insurance Book Value.." min="0" max="" step="0.01" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Smart Witness Serial : </td>
                                        <td class="text-left" style="width: 20%;">
                                            <input type="text" class="form-control" name="smart_witness_serial" value="@if (isset($tractor)){{ $tractor->smart_witness_serial }}@endif" placeholder="Enter Smart Witness Serial.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">Omnitracs Device ID : </td>
                                        <td class="text-left" style="width: 20%;">
                                            <input type="text" class="form-control" name="omnitracs_device_id" value="@if (isset($tractor)){{ $tractor->omnitracs_device_id }}@endif" placeholder="Enter Omnitracs Device ID.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Pre Pass : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="pre_pass" value="@if (isset($tractor)){{ $tractor->pre_pass }}@endif" placeholder="Enter Pre Pass.." />
                                        </td>
                                        <td class="font-w800 text-right" style="width: 20%;">T Check : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="t_check" value="@if (isset($tractor)){{ $tractor->t_check }}@endif" placeholder="Enter T Check.." />
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

    $('button.view-tractors').click(function() {
        location.href = "/tractors";
    });
});
</script>
</x-app-layout>