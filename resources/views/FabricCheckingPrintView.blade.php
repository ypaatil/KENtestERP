<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fabric Checking | Ken Global Designs Pvt. Ltd. </title>
    <!-- Web Fonts -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @font-face {
            font-family: "Gotham";
            src: url("Gotham Fonts Family/Gotham-Regular.woff2") format("woff2"),
                url("Gotham Fonts Family/Gotham-Regular.woff") format("woff"),
                url("Gotham Fonts Family/Gotham-Regular.ttf") format("truetype");
            font-weight: 400;
            font-style: normal;
        }

        body {
            /* font-family: "Times New Roman", Times, serif; */
            background-color: #f8f9fa;
            color: #000000ff;
            padding: 20px;
        }

        .invoice-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #0c0c0c;
            padding: 8px;
        }

        .table-bordered th {
            vertical-align: middle;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;

        }

        .imgs {
            max-width: 80px;
            max-height: 80px;
        }

        thead tr {

            text-align: center;
        }

        tfoot tr {

            text-align: center;
            border: 1px solid;
        }

        :root {
            --label-width: 160px;
        }

        .info-row {
            display: flex;
            margin-bottom: -2px;
        }

        .info-row .label {
            font-weight: bold;
            width: var(--label-width);
            min-width: var(--label-width);
        }

        .info-row .colon {
            width: 15px;
            text-align: center;
        }

        .info-row .value {
            flex: 1;
        }


        /*     PRINT STYLES      */

        @media print {
            body {
                /* font-family: "Times New Roman", Times, serif; */
                background-color: #f8f9fa;
                color: #000000ff;
                padding: 20px;
            }

            #printInvoice,
            #printInvoice * {
                visibility: visible;
            }

            #printInvoice {
                position: absolute;
                left: 0;
                top: 0;

                width: 100%;
                max-width: 100% !important;
                margin: 0;
                padding: 5mm;
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }



            .btn,
            .d-print-none {
                display: none !important;
            }

            /* General text */
            body,
            html,
            #printInvoice {

                line-height: 1.5 !important;
                color: #000 !important;
            }

            .info-row .label,
            .info-row .colon {

                font-weight: bold !important;
            }

            /* Table styles */
            .table {
                width: 100% !important;

                border-collapse: collapse !important;

                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                padding: 2px;
            }

            @media print {

                .table-bordered th {
                    vertical-align: middle;
                    text-align: center;
                    white-space: nowrap;

                }

                th,
                td {
                    white-space: normal !important;
                    /* allow text to wrap */

                }

                #printInvoice {
                    width: 100% !important;
                    max-width: 100% !important;
                }
            }


            .table-bordered td,
            .table-bordered th {

                font-size: 11pt !important;

                text-align: center !important;
            }

            thead tr,
            tfoot tr {

                -webkit-print-color-adjust: exact;
                font-weight: bold !important;

            }

            /* Expand column widths */
            .row {
                display: flex !important;
                flex-wrap: nowrap !important;
                width: 100% !important;
                margin: 0;
            }

            .col-md-4 {
                width: 33.33% !important;
                flex: 0 0 33.33% !important;
            }

            .col-md-6 {
                width: 50% !important;
                flex: 0 0 50% !important;
            }

            .col-md-3 {
                width: 33.33% !important;
                flex: 0 0 33.33% !important;
            }

            .col-md-9 {
                width: 66.66% !important;
                flex: 0 0 66.66% !important;
            }

            .p-2 {
                padding: 12px !important;
            }

            body,
            html {
                overflow: visible !important;
            }
        }

        .verticalLine {
            border-right: solid grey;
            margin-right: 10px;

        }

        .double-color-hr {
            display: flex;
            width: 100%;
            height: 4px;
            margin: 0;
        }

        .double-color-hr .blue-part {
            width: 39%;
            background-color: #02659C;
        }

        .double-color-hr .green-part {
            width: 80%;
            background-color: #A8CF47;
        }

        /* 🔹 Make all borders black */
        .border,
        .border-end,
        .border-start,
        .border-bottom,
        .border-top,
        .table-bordered td,
        .table-bordered th {
            border-color: #000 !important;
        }

        .table-bordered tr th:first-child,
        .table-bordered tr td:first-child {
            border-left: none !important;
        }

        .table-bordered tr th:last-child,
        .table-bordered tr td:last-child {
            border-right: none !important;
        }


        .outer-border {
            border: 2px solid #000;
            padding: 0px !important;
            margin: 10px auto;
            max-width: 95%;
            background: #fff;
            height: 100%;
        }

        @media print {

            @page {
                size: A4;
                margin-top: 12mm;
                margin-right: 1mm;
                border: 1px solid #000;
                /* some browsers support this */
                height: 100%;
                margin-bottom: 10mm;
            }

            .outer-border {
                border: none !important;
                margin: 0 !important;

                padding: 0 !important;
                max-width: 100% !important;
                height: 100%;
            }

            .invoice-container img {
                max-width: 2000px;
                /* shrink logo if needed */
                height: 150px;
                margin-top: 5px;
            }

            .invoice-container h4 {
                font-size: 20pt;

            }

            .table-bordered tr th:first-child,
            .table-bordered tr td:first-child {
                border-left: none !important;
            }

            .table-bordered tr th:last-child,
            .table-bordered tr td:last-child {
                border-right: none !important;
            }


            .invoice-container h4 {
                font-size: 18pt;
                /* make text a little smaller for print */
            }

            .invoice-container p {
                font-size: 12pt;
            }

            td.text-start {
                text-align: left !important;
            }

            td.text-end {
                text-align: right !important;
            }

            /* Set fixed column widths only for print */
            table.table-bordered th:nth-child(1),
            table.table-bordered td:nth-child(1) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(2),
            table.table-bordered td:nth-child(2) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(3),
            table.table-bordered td:nth-child(3) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(4),
            table.table-bordered td:nth-child(4) {
                width: auto !important;
                /* Garment Color column */
            }

            table.table-bordered th:nth-child(5),
            table.table-bordered td:nth-child(5) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(6),
            table.table-bordered td:nth-child(6) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(7),
            table.table-bordered td:nth-child(7) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(8),
            table.table-bordered td:nth-child(8) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(9),
            table.table-bordered td:nth-child(9) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(10),
            table.table-bordered td:nth-child(10) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(11),
            table.table-bordered td:nth-child(11) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(12),
            table.table-bordered td:nth-child(12) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(13),
            table.table-bordered td:nth-child(13) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(14),
            table.table-bordered td:nth-child(14) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(15),
            table.table-bordered td:nth-child(15) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(16),
            table.table-bordered td:nth-child(16) {
                width: auto !important;
            }

            table.table-bordered th:nth-child(17),
            table.table-bordered td:nth-child(17) {
                width: auto !important;
            }

        }
    </style>
</head>
@if(count($fabricChekingMaster)>0)
@foreach($fabricChekingMaster as $rowMaster)

<body>

    <div class="  mb-3 d-print-none" style="margin-left: 1200px;">
        <button class="btn btn-primary" onclick="window.print()">
            Print
        </button>
        <!-- <button type="button" id="export_button" class="btn btn-warning" style="margin-left: 10px;">Export</button>   -->
    </div>


    <!-- Invoice Container -->
    <div class="container-fluid invoice-container">
        <div class="invoice">
            <main>
                <div id="printInvoice" class="outer-border">
                    <!-- Header -->
                    <div class="row">
                        <div class="col-md-4 verticalLine text-center p-2">
                            <img src="http://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130" width="230">
                        </div>

                        <div class="col-md-7" style="margin-top:10px;">
                            <h4 class="mb-2 fw-bold">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                            <p>Reg.Office:18/20 Back Side Of Hotel City In, Industrial Estate, Ichalkaranji-416115<br>
                                Tal Hatkanangale Dist Kolhapur Maharashtra INDIA.<br>
                                Works: Gat No 298&299,At Kondigare, Tal: Shirol, Dist: Kolhapur-416102 <br>
                                Tel : +91230 2438538 Email:office@kenindia.in
                            </p>
                        </div>

                        <div class="col-md-2">
                            <!-- <h6 class="fw-bold">Date: 15-09-2023</h6> -->
                        </div>
                    </div>

                    <div class="double-color-hr">
                        <div class="blue-part"></div>
                        <div class="green-part"></div>
                    </div>

                    <h4 class=" fw-bold mt-6 text-center">Fabric Checking </h4>

                    <!-- Sales Info -->
                    <div class="row  border-top border-bottom  g-0">
                        <div class="col-md-6 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Checking No.</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $rowMaster->chk_code }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Checking Date</div>
                                <div class="colon">: </div>
                                <div class="value"> {{ \Carbon\Carbon::parse($rowMaster->chk_date)->format('d-m-Y') }}</div>
                            </div>
                             <div class="info-row">
                                <div class="label">PO No.</div>
                                <div class="colon">: </div>
                                <div class="value">{{ $rowMaster->po_code }} </div>
                            </div>
                             <div class="info-row">
                                <div class="label">PO Type</div>
                                <div class="colon">: </div>
                                <div class="value">{{ $rowMaster->po_type_name   }}</div> 
                            </div>
                           
                            <div class="info-row">
                                <div class="label">GRN No</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $rowMaster->in_code }} </div>
                            </div>




                        </div>
                        <div class="col-md-6 p-2 ">
                            <div class="info-row">
                                <div class="label">Invoice No.</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $rowMaster->invoice_no }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Invoice Date</div>
                                <div class="colon">:</div>
                                <div class="value">{{ \Carbon\Carbon::parse($rowMaster->invoice_date)->format('d-m-Y') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Supplier Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $rowMaster->Ac_name }}</div>
                            </div>
                              <div class="info-row">
                                <div class="label">Buyer Name</div>
                                <div class="colon">: </div>
                                <div class="value">{{ $rowMaster->buyer  }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Fabric Gate No.</div>
                                <div class="colon">: </div>
                                <div class="value">{{ $rowMaster->fge_code  }}</div>
                            </div>


                        </div>

                    </div>


                    <h4 class="text-center mt-6 fw-bold">Fabric Checking Details</h4>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Item Code</th>
                                <th>Roll No</th>
                                <th>Fabric  Code</th>

                                <th>Actual Width</th>
                                <th>Supplier Roll No</th>
                                <th>Fabric Quality</th>
                                <th>GRN Qty</th>
                                <th>QC Qty</th>
                                <th>Short Qty</th>
                                <th>Excess Qty</th>
                                <th>UOM</th>
                                <th>Shade</th>
                                <th>Status</th>
                                <th>Defect</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::
                            leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                            ->leftJoin('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')

                            ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                            ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                            ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                            ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                            ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                            ->whereIn('fabric_checking_details.status_id', [1,2])
                            ->get(['fabric_checking_details.*','fabric_check_status_master.fcs_name',
                            'item_master.item_description','item_master.item_name','item_master.item_description',
                            'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name','unit_master.unit_name']);
                            $totalPassed=0; $totalExtra=0; $totalshort=0;
                            $no=1; @endphp
                            @foreach($FabricChekingdetailslists as $rowDetail)

                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>
                                <td class="text-center">{{ $rowDetail->track_code }}</td>

                                <td class="text-start">{{ $rowDetail->item_name }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->width,2 )}}</td>
                                <td class="text-end">{{ $rowDetail->roll_no }}</td>
                                <td class="text-start">{{ $rowDetail->item_description }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->old_meter,2) }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->meter,2) }}</td>

                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->reject_short_meter,2) }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->extra_meter,2) }}</td>
                                <td class="text-end">{{ $rowDetail->unit_name }}</td>
                                <td class="text-center">{{ $rowDetail->shade_name }}</td>
                                <td class="text-start">{{ $rowDetail->fcs_name }}</td>
                                <td class="text-end">{{ $rowDetail->fabricdefect_name }}</td>
                            </tr>
                            @php $no=$no+1;
                            $totalPassed=$totalPassed+$rowDetail->meter;
                            $totalExtra=$totalExtra + $rowDetail->extra_meter;
                            $totalshort= $totalshort + $rowDetail->reject_short_meter;

                            @endphp
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold"><b></b></td>
                                <td class="text-end fw-bold"><b>Total:</b></td>
                                <td class="text-end fw-bold">{{ indian_number_format_for_value($rowMaster->total_meter,2) }}</td>
                                <td class="text-end fw-bold">{{indian_number_format_for_value($totalPassed,2)}} </td>
                                <td class="text-end fw-bold">{{indian_number_format_for_value($totalshort,2)}}</td>
                                <td class="text-end fw-bold">{{indian_number_format_for_value($totalExtra,2)}} </td>
                                <td class="text-end fw-bold"> {{ indian_number_format_for_value($rowMaster->total_kg,2)}}</td>
                                <td class="text-end fw-bold" colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>


                    <h4 class="text-center mt-6 fw-bold">Fabric Checking Details: (Rejected)</h4>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Item Code</th>
                                <th>Roll No</th>
                                <th>Fabric Color Code</th>
                                <th>Actual Width</th>
                                <th>Quality</th>
                                <th>GRN Meter</th>
                                <th>QC Meter</th>
                                <th>Short</th>
                                <th>Excess</th>
                                <th>Kg</th>
                                <th>Shade</th>
                                <th>Status</th>
                                <th>Defect</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::
                            leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                            ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                            ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                            ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                            ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                            ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                            ->where('fabric_checking_details.status_id','=',2)
                            ->get(['fabric_checking_details.*','fabric_check_status_master.fcs_name',
                            'item_master.item_description','item_master.item_name','item_master.item_description',
                            'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name']);
                            $totalReject=0;
                            $no=1; @endphp
                            @foreach($FabricChekingdetailslists as $rowDetail)

                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>
                                <td class="text-center">{{ $rowDetail->track_code }}</td>

                                <td class="text-start">{{ $rowDetail->item_name }}</td>
                                <td class="text-end">{{ $rowDetail->width }}</td>
                                <td class="text-start">{{ $rowDetail->item_description }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->old_meter,2) }}</td>
                                <td class="text-end">{{ $rowDetail->meter }}</td>
                                <td class="text-end">{{ $rowDetail->reject_short_meter }}</td>

                                <td class="text-end">{{ $rowDetail->extra_meter }}</td>
                                <td class="text-end">{{ $rowDetail->kg }}</td>
                                <td class="text-end">{{ $rowDetail->shade_name }}</td>
                                <td class="text-start">{{ $rowDetail->fcs_name }}</td>
                                <td class="text-end">{{ $rowDetail->fabricdefect_name }}</td>

                            </tr>
                            @php
                            $no=$no+1;
                            $totalReject=$totalReject+$rowDetail->meter;
                            @endphp
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-end fw-bold" colspan="8"><b>Checker Name:{{ $rowMaster->in_narration }} </b></td>
                                <td class="text-end fw-bold">Received Total: {{$rowMaster->total_meter}} </td>
                                <td class="text-end fw-bold">Passed Meter: {{$totalPassed - $totalReject}}</td>
                                <td class="text-end fw-bold">Rejected Meter: {{$totalReject}} </td>
                                <td class="text-end fw-bold" colspan="2"><b>Total Pass </b>: {{round((($totalPassed/$rowMaster->total_meter)*100),2) - round((($totalReject/$rowMaster->total_meter)*100),2)}}%</td>
                                <td class="text-end fw-bold" colspan="2"><b>Total Reject </b>: {{round((($totalReject/$rowMaster->total_meter)*100),2)}}% </td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h4 class="text-center mt-6 fw-bold">Shade Summary</h4>
                            <table class="table table-bordered border-end border-start table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Shade</th>
                                        <th>Meter</th>
                                        <th>Roll No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php

                                    $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::select('fabric_checking_details.*','fabric_check_status_master.fcs_name',
                                    DB::raw('sum(fabric_checking_details.meter) as summeter') ,'fabric_checking_details.shade_id', DB::raw('GROUP_CONCAT(fabric_checking_details.track_code SEPARATOR " ") as track_codes'),
                                    'item_master.item_description','item_master.item_name','item_master.item_description',
                                    'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name')
                                    ->leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                                    ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                                    ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                                    ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                                    ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                                    ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                                    ->whereIn('fabric_checking_details.status_id', [1,2])
                                    ->groupby('fabric_checking_details.shade_id')
                                    ->get();

                                    $totalsum=0;
                                    $no=1; @endphp
                                    @foreach($FabricChekingdetailslists as $rowDetail)

                                    <tr>
                                        <td class="text-end" style="vertical-align: middle;">{{ $no }}</td>
                                        <td class="text-start" style="vertical-align: middle;padding-left: 8%;">{{ $rowDetail->shade_name }}</td>
                                        <td class="text-end" style="vertical-align: middle;">{{ $rowDetail->summeter }}</td>
                                        <td class="text-start" style="vertical-align: middle;">{{ $rowDetail->track_codes}}</td>
                                    </tr>
                                    @php
                                    $no=$no+1;
                                    $totalsum=$totalsum+$rowDetail->summeter;
                                    @endphp
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-end " nowrap><b>Total Meter:</b></td>
                                        <td class="text-end fw-bold"> {{$totalsum}} </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        @endforeach
                        @endif
                        <div class="col-md-6">
                            <h4 class="text-center mt-6 fw-bold">Width Summary</h4>
                            <table class="table table-bordered border-start border-end table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th nowrap>Actual Width</th>
                                        <th>Meter</th>
                                        <th>No. Of Rolls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no=1;
                                    $totalsum=0;
                                    $FabricActual_Width = App\Models\FabricCheckingDetailModel::select('fabric_checking_details.*',
                                    DB::raw('GROUP_CONCAT(fabric_checking_details.track_code SEPARATOR " ") as track_codes'),
                                    DB::raw('sum(fabric_checking_details.meter) as totalMeter'))
                                    ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                                    ->groupby('fabric_checking_details.width')
                                    ->get();
                                    @endphp
                                    @foreach($FabricActual_Width as $rowDetail)
                                    <tr>
                                        <td class="text-end" style="vertical-align: middle;">{{ $no }}</td>
                                        <td class="text-end" style="vertical-align: middle;">{{ $rowDetail->width }}</td>
                                        <td class="text-end" style="vertical-align: middle;" class="text-right">{{$rowDetail->totalMeter}}</td>
                                        <td class="text-start" style="vertical-align: middle;">{{$rowDetail->track_codes}}</td>
                                    </tr>
                                    @php
                                    $no=$no+1;
                                    $totalsum=$totalsum+$rowDetail->totalMeter;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-end fw-bold">Total : </td>
                                        <td class="text-end fw-bold">{{$totalsum}}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <table class="table" style="margin-top: 100px;">
                        <tr>
                            <th>Prepared By:</th>
                            <th>Checked By:</th>
                            <th>Approved By:</th>
                            <th>Authorized By:</th>
                        </tr>

                    </table>

                </div>
            </main>
        </div>
    </div>

    <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>


    <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
    <script>

    </script>



</body>

</html>