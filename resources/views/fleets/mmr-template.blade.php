<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>U.S. Monthly Maintenance Record, MGBA-355</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<style type="text/css">
    p {
        font-size: 13px;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .page-title {
        border: 2px solid #525252;
        background-color: #e9e9e9;
        width: 100% !important;
        text-align: center !important;
        box-sizing: border-box;
        font-size: 22px;
    }
    .block-1 {
        font-size: 13px;
    }

    /* font sizes */
    .f-10 {
        font-size: 10px !important;
    }
    .f-13 {
        font-size: 13px !important;
    }
    .f-16 {
        font-size: 16px !important;
    }
    .f-18 {
        font-size: 18px !important;
    }
    .f-20 {
        font-size: 20px !important;
    }

    /* widiths */
    .w-100 {
        width: 100% !important;
        margin: 0;
        padding: 0;
    }
    .w-5 {
        width: 5% !important;
    }
    .w-10 {
        width: 10% !important;
    }
    .w-15 {
        width: 15% !important;
    }
    .w-25 {
        width: 25% !important;
    }
    .w-30 {
        width: 30% !important;
    }
    .w-40 {
        width: 40% !important;
    }
    .w-45 {
        width: 45% !important;
    }
    .w-50 {
        width: 50% !important;
    }
    .w-60 {
        width: 60% !important;
    }
    .w-70 {
        width: 70% !important;
    }
    .w-75 {
        width: 75% !important;
    }
    
    /* borders */
    .border {
        border: 1px solid grey !important;
    }

    /* margins */
    .mt-0 {
        margin-top: 0px !important;
    }
    .mt-20 {
        margin-top: 20px !important;
    }
    .mt-30 {
        margin-top: 30px !important;
    }
    .mb-10 {
        margin-bottom: 10px !important;
    }
    .mb-20 {
        margin-bottom: 20px !important;
    }
    .my-5 {
        margin: 5px 0 !important;
    }
    .my-10 {
        margin: 10px 0 !important;
    }
    .my-20 {
        margin: 20px 0 !important;
    }
    
    /* paddings */
    .py-0 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .pt-10 {
        padding-top: 10px !important;
    }
    .pt-0 {
        padding-top: 0px !important;
    }
    .pb-0 {
        padding-bottom: 0px !important;
    }
    .pl-10 {
        padding-left: 10px !important;
    }

    /* line height */
    .lh-14 {
        line-height: 14px !important;
    }

    @page {
        margin-bottom:0px;
    }
    footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 50px;
    }
</style>
<body>
    <footer>
        <table class="w-100">
            <tr>
                <td class="w-70 f-13 py-0" style="border-top: 1px solid #000;">
                    This form for service providers is accessed through mybizaccount.fedex.com
                </td>
                <td class="w-30 f-13 text-right" style="border-top: 1px solid #000;">
                    Page 1 of 1
                </td>
            </tr>
        </table>
    </footer>

    <p class="page-title">
        <strong>
            U.S. Monthly Maintenance Record, MGBA-355
        </strong>
    </p>
    <p class="text-right"><strong>17 November 2020</strong></p>
    <p class="block-1 lh-14">
        To comply with U.S. federal regulations, this form must be completed, signed and submitted to FedEx Ground by the 20th 
        of the month following the month for which inspections, repairs, or maintenance were performed on any service provider- 
        owned or -leased equipment. Submit one record for each piece of equipment, even if not regularly providing services.
    </p>

    <!-- main info -->
    <table class="w-100 my-10">
        <tr>
            <td class="w-45 f-13">
                <strong>Maintenance Record for the Month and Year of:</strong>
            </td>
            <td class="w-10"></td>
            <td class="w-45 f-13">
                <strong>Domicile Station/Hub:</strong>
            </td>
        </tr>
        <tr>
            <td class="py-0 border pl-10">
                {{ $mYearMonth }}
            </td>
            <td></td>
            <td class="py-0 border pl-10">
                {{ $tractor->domicile }}
            </td>
        </tr>
        <tr>
            <td class="w-45 f-13 pt-10">
                <strong>Service Provider Business Name:</strong>
            </td>
            <td class="w-10"></td>
            <td class="w-45 f-13 pt-10">
                <strong>Current Mileage* (Odometer Reading)</strong>
            </td>
        </tr>
        <tr>
            <td class="py-0 border pl-10">
                {{ $tractor->sProvider }}
            </td>
            <td></td>
            <td class="py-0 border pl-10">
                {{ $tractor->cMileage }}
            </td>
        </tr>
        <tr>
            <td class="w-45 f-13 pt-10">
                <strong>Vehicle Unit #:</strong>
            </td>
            <td class="w-10"></td>
            <td class="w-45 f-10 pt-10 pb-0 lh-14" rowspan="2">
                *If reading has decreased due to odometer repair/replacement, proof
                should also be provided. If unit is undergoing repair and unavailable,
                “N/A” may be utilized for current mileage.
            </td>
        </tr>
        <tr>
            <td class="py-0 border pl-10">
                {{ $tractor->id }}
            </td>
            <td></td>
        </tr>
    </table>

    <!-- has maintenances -->
    <table class="w-100 mb-10">
        <tr>
            <td class="w-70 f-13 pt-10">
                Were any repairs, inspections or preventative maintenance performed on this unit?
            </td>
            <td class="w-15 f-13 pt-10 text-right">
                <input type="checkbox" 
                        @if ($tractor->hasMaint){{ 'checked' }}@endif
                        style="width: 20px; height: 20px; padding-right: 10px;" /> Yes
            </td>
            <td class="w-15 f-13 pt-10 text-right">
                <input type="checkbox"
                        @if (!$tractor->hasMaint){{ 'checked' }}@endif
                        style="width: 20px; height: 20px; padding-right: 10px;" /> No
            </td>
        </tr>
    </table>

    <!-- out of service -->
    <table class="w-100 mb-20">
        <tr>
            <td class="w-70 f-13 pt-10 lh-14">
                If “no” maintenance was performed, was the unit out of service and unable to provide
                service (i.e., awaiting repair, on litigation hold, etc.)?
            </td>
            <td class="w-15 f-13 pt-10 text-right">
                <input type="checkbox"
                        @if ($tractor->oService){{ 'checked' }}@endif
                        style="width: 20px; height: 20px; padding-right: 10px;" /> Yes
            </td>
            <td class="w-15 f-13 pt-10 text-right">
                <input type="checkbox"
                        @if (!$tractor->oService){{ 'checked' }}@endif
                        style="width: 20px; height: 20px; padding-right: 10px;" /> No
            </td>
        </tr>
    </table>

    <!-- block 2 -->
    <p class="f-13 mt-10 lh-14">
        If “yes,” maintenance was performed, then a record of any inspection, repairs or maintenance indicating their date 
        and &nbsp;nature should be included with this form. Copies of invoices, receipts, etc. are preferred; however, in lieu, 
        a list indicating the date and nature of the inspection, maintenance and repairs should be documented in the “Date of Maintenance” and
        “Nature/Description of Maintenance Performed” columns. Section 396.3(b)(3) of the Federal Motor Carrier Safety
        Regulations requires motor carriers to maintain a record of all inspections, repair, and maintenance indicating their date
        and nature.
    </p>

    <!-- Maintenance List Table -->
    <p class="f-13 mt-20 lh-14">
        If documentation is not attached, indicate the date and nature of the inspections, repairs, 
        or maintenance performed in the box below.
    </p>
    <table class="w-100 table-bordered f-13">
        <tr>
            <td class="py-0 pl-10 w-25">
                <strong>Date of Maintenance</strong>
            </td>
            <td class="py-0 pl-10 w-75">
                <strong>Nature/Description of Maintenance Performed</strong>
            </td>
        </tr>
        @foreach ($tractor->maints as $m)
            <tr>
                <td class="py-0 pl-10">
                    {{ $m->mDate }}
                </td>
                <td class="py-0 pl-10">
                    {{ $m->mDesc }}
                </td>
            </tr>
        @endforeach
        @if (count($tractor->maints) < 5)
            @for ($i = 0; $i < 5 - count($tractor->maints); $i++)
                <tr>
                    <td class="py-0 pl-10">&nbsp;</td>
                    <td class="py-0 pl-10">&nbsp;</td>
                </tr>
            @endfor
        @endif
    </table>

    <!-- block 3 -->
    <table class="w-100 mt-20">
        <tr>
            <td class="w-5 pt-0 f-13 align-top">
                <input type="checkbox" checked>
            </td>
            <td class="w-95 f-13 lh-14">
                By checking this box, I declare that this record is true and correct. Unless otherwise clearly indicated as “out of
                service” on this record, I confirm that the equipment on this record is in compliance with the Federal Motor Carrier
                Safety Regulations 49 C.F.R. 396.3(a)(1) and 396.7 (a) and is in safe operating condition and meets all federal, state
                and local motor vehicle laws. Furthermore, I confirm that preventative maintenance is consistent with the interval
                schedule per 396.3(b)(2).
            </td>
        </tr>
    </table>

    <!-- Signature -->
    <table class="w-100 my-20">
        <tr>
            <td class="w-60 f-13">
                <strong>Signature of Authorized Officer or Business Contact:</strong>
            </td>
            <td class="w-10"></td>
            <td class="w-30 f-13">
                <strong>Date Completed:</strong>
            </td>
        </tr>
        <tr>
            <td class="py-0 border text-center">
                <img src="{{ $sign }}" style="height: 30px; padding-top: 10px;">
            </td>
            <td></td>
            <td class="py-0 border pl-10">
                {{ $cDate }}
            </td>
        </tr>
    </table>
    
    <!-- block 4 -->
    <p class="f-13 mt-20 lh-14 font-italic">
        * The Monthly Maintenance Record (MMR) is FedEx Ground's systematic method of obtaining vehicle maintenance records for
        service provider-owned vehicles in compliance with the Federal Motor Carrier Safety Regulations which require motor carriers to have
        a systematic method of causing vehicles operating under their motor carrier operating authority to be inspected, repaired, and
        maintained. Therefore, if FedEx Ground does not receive records for a vehicle by the 20th of the month following the month in which
        maintenance, repairs, or inspections were performed, packages will not be made available to this vehicle.
    </p>
</body>
</html>