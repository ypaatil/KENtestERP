@php
function indianNumberFormat($num) {
$num = (string) $num;
$afterDecimal = '';
if (strpos($num, '.') !== false) {
list($num, $afterDecimal) = explode('.', $num);
$afterDecimal = '.' . $afterDecimal;
}
$lastThree = substr($num, -3);
$restUnits = substr($num, 0, -3);
if ($restUnits != '') {
$lastThree = ',' . $lastThree;
}
$restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
return $restUnits . $lastThree . $afterDecimal;
}
@endphp



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Order Costing | Ken Global Designs Pvt. Ltd.</title>
    <!-- Web Fonts -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
            line-height: 1.1;
        }

        
            .table-bordered th {
                vertical-align: middle;
                text-align: center;
            }


        .table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Apply same layout to all costing tables */
        .table-fixed {
            table-layout: fixed;
            /* forces equal column widths */
            width: 100%;
            /* same width for all tables */
        }

        /* Make sure each column has consistent width */
        .table-fixed th,
        .table-fixed td {
            word-wrap: break-word;
            /* wrap long content */
            overflow-wrap: break-word;
            white-space: normal;
            font-size: 11pt !important;
            /* allow wrapping */
        }

        /* Example: force 7 equal columns */
        .table-fixed th:nth-child(1),
        .table-fixed td:nth-child(1) {
            width: 14.28%;
        }

        .table-fixed th:nth-child(2),
        .table-fixed td:nth-child(2) {
            width: 14.28%;
        }

        .table-fixed th:nth-child(3),
        .table-fixed td:nth-child(3) {
            width: 17%;
        }

        .table-fixed th:nth-child(4),
        .table-fixed td:nth-child(4) {
            width: 14.28%;
        }

        .table-fixed th:nth-child(5),
        .table-fixed td:nth-child(5) {
            width: 14.28%;
        }

        .table-fixed th:nth-child(6),
        .table-fixed td:nth-child(6) {
            width: 14.28%;
        }

        .table-fixed th:nth-child(7),
        .table-fixed td:nth-child(7) {
            width: 14.28%;
        }

        /* for fourth table */
        /* Fourth table (Costing Breakdown) */
        .table-breakdown {
            width: 50%;
            /* make it narrower */
            margin: 20px auto;
            /* center horizontally */
            border-collapse: collapse;
            text-align: center;

            /* smaller text than other tables */
        }

        .table-breakdown th,
        .table-breakdown td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: middle;
            font-size: 11pt !important;
            line-height: 1.1;
        }

        /* Reduce row height */
        .table-breakdown tr {


            /* smaller row height */
        }

        .table-breakdown th {

            font-weight: bold;
        }

        .section {

            font-weight: bold;
            text-align: center;
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
            .table-section {
                page-break-inside: avoid;
                /* ensures heading and table stay together */
            }

            body {
                /* font-family: "Times New Roman", Times, serif; */
                background-color: #fff;
                /* color: black; */
                padding: 20px;
                color: #000000ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;

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
                table-layout: fixed !important;
                border-collapse: collapse !important;

                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                padding: 10px;
            }

            @media print {


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
            border: 1px solid #000;
            padding: 0px !important;
            margin: 9px auto;
            max-width: 95%;
            background: #fff;
            height: 100%;
        }

        table.table-bordered th:nth-child(1),
        table.table-bordered td:nth-child(1) {
            width: 300px !important;
        }

        table.table-bordered th:nth-child(2),
        table.table-bordered td:nth-child(2) {
            width: 300px !important;
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
            }

            .invoice-container img {
                max-width: 2000px;
                /* shrink logo if needed */
                height: 150px;
                margin-top: 5px;
            }



            .table-bordered tr th:first-child,
            .table-bordered tr td:first-child {
                border-left: none !important;
            }

            .table-bordered tr th:last-child,
            .table-bordered tr td:last-child {
                border-right: none !important;
            }

            .table-bordered th {
                vertical-align: middle;
                text-align: center;
            }

            table.table-bordered th:nth-child(1),
            table.table-bordered td:nth-child(1) {
                width: 300px !important;
            }

            table.table-bordered th:nth-child(2),
            table.table-bordered td:nth-child(2) {
                width: 300px !important;
            }

            table.table-bordered th:nth-child(3),
            table.table-bordered td:nth-child(3) {
                width: 9% !important;
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

            table.table-breakdown th:nth-child(3),
            table.table-breakdown th:nth-child(3) {
                width: 200px !important;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
                /* repeats header on new page */
            }

            tfoot {
                display: table-footer-group;
                /* repeats footer on new page */
            }

            /* Optional: prevent large tables from splitting at all */
            .table-fixed,
            .table-breakdown {
                page-break-inside: avoid;
            }

        }

            @media print {


                .table-bordered th {
                    vertical-align: middle;
                    text-align: center;
                }

                .invoice-container h4 {
                    font-size: 18pt;
                    /* make text a little smaller for print */
                }

                .invoice-container p {
                    font-size: 14pt;
                    /* make text a little smaller for print */
                }

                td.text-start {
                    text-align: left !important;
                }

                td.text-end {
                    text-align: right !important;
                }

            }


        
    </style>
</head>

<body>
    <!-- Buttons -->
    <!-- Buttons -->
    <div class="  mb-3 d-print-none" style="margin-left: 1200px;">
        <div class="btn-group d-print-none"> <a href="javascript:window.print()" class="btn btn-info"> Print</a> </div>
        <!-- <button type="button" id="export_button" class="btn btn-warning">Export</button> -->
    </div>


    <!-- Invoice Container -->
    <div class="container-fluid invoice-container">
        <div class="invoice">
            <main>
                <div id="printInvoice" class="outer-border">
                    <!-- Header -->
                    <div class="row">
                        <div class="col-md-4 verticalLine text-center p-2">
                            <img src="https://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130" width="230">
                        </div>

                        <div class="col-md-7 " style=" margin-top:10px;">
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

                    <h4 style="margin-bottom: 3px;" class=" fw-bold  text-center">COSTING SHEET</h4>

                    <!-- Sales Info -->
                    <div class="row  border-top border-bottom  g-0">
                        <div class="col-md-4 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Sales Order No</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->sales_order_no }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Execution SBU</div>
                                <div class="colon">:</div>
                                <div class="value"> @php if($SalesOrderCostingMaster[0]->in_out_id == 1){ echo "Inhouse"; }if($SalesOrderCostingMaster[0]->in_out_id == 2){ echo "Outsource"; } @endphp</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Order Type</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->order_type }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Buyer PO No</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->po_code }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Buyer Name</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $SalesOrderCostingMaster[0]->ac_short_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Brand Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->brand_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Market</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->order_group_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Currency</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->currency_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Rate {{ $SalesOrderCostingMaster[0]->currency_name }}</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->inr_rate }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Exchange Rate</div>
                                <div class="colon">:</div>
                                <div class="value">{{ money_format('%!i',$SalesOrderCostingMaster[0]->exchange_rate) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Rate (INR) </div>
                                <div class="colon">:</div>
                                <div class="value">{{ number_format( $SalesOrderCostingMaster[0]->order_rate,2 )}}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Quantity</div>
                                <div class="colon">:</div>
                                <div class="value">{{ indianNumberFormat($SalesOrderCostingMaster[0]->total_qty) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Order Value</div>
                                <div class="colon">:</div>
                     <div class="value">{{ indianNumberFormat(round($SalesOrderCostingMaster[0]->order_value ?? 0)) }}</div>


                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <div class="info-row">
                                <div class="label">Style Category</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->fg_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->fg_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style No</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->style_no }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style Description</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->style_description }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">SAM</div>
                                <div class="colon">:</div>
                                <div class="value">{{ number_format($SalesOrderCostingMaster[0]->sam,2) }} </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row  border-bottom  g-0">
                        <div class="col-md-4 p-2  border-end">
                            <div class="info-row">
                                <div class="label">Payment Terms</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->ptm_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Delivery Terms</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->delivery_term_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Shipment Mode</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->ship_mode_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Delivery Place</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->warehouse_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Country</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->c_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Order Received Date</div>
                                <div class="colon">:</div>
                                <div class="value">{{ \Carbon\Carbon::parse ($SalesOrderCostingMaster[0]->order_received_date ?? '')->format('d-m-Y') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Plan Cut Date (PCD)</div>
                                <div class="colon">:</div>
                                <div class="value">{{ \Carbon\Carbon::parse($SalesOrderCostingMaster[0]->plan_cut_date ?? '')->format('d-m-Y')  }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Inspection Date</div>
                                <div class="colon">:</div>
                                <div class="value">{{ \Carbon\Carbon::parse($SalesOrderCostingMaster[0]->inspection_date ?? '')->format('d-m-Y') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Shipment Date</div>
                                <div class="colon">:</div>
                                <div class="value">{{ \Carbon\Carbon::parse( $SalesOrderCostingMaster[0]->shipment_date ?? '')->format('d-m-Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <div class="info-row">
                                <div class="label">Bulk Merchant Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->merchant_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">PD Merchant Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $SalesOrderCostingMaster[0]->PDMerchant_name }}</div>
                            </div>
                        </div>
                    </div>
                    @php $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $SalesOrderCostingMaster[0]->sz_code)->get();
                    @endphp
                    <!-- Assortment Table -->
                    <h4 class=" fw-bold">Fabric Details:</h4>
                    <table class="table table-bordered table-sm table-fixed">
                        <thead >
                            <tr>
                                <th>Classification </th>
                                <th>Description </th>
                                <th>Cnsm/Gmt </th>
                                <th>Cost / Pcs </th>
                                <th>Wastage %</th>
                                <th> Cnsm/Gmt with Wastage % </th>
                                <th> Gmt Cost</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $FabricList = App\Models\SalesOrderFabricCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_fabric_costing_details.class_id')
                            ->where('sales_order_fabric_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_fabric_costing_details.description','sales_order_fabric_costing_details.consumption',
                            'sales_order_fabric_costing_details.rate_per_unit',
                            'sales_order_fabric_costing_details.wastage','sales_order_fabric_costing_details.bom_qty','sales_order_fabric_costing_details.total_amount']);
                            $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
                            ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
                            'sales_order_sewing_trims_costing_details.rate_per_unit',
                            'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);
                            $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();
                            $no=1;
                            $fabricTotalAmt=0;
                            @endphp
                            @foreach($FabricList as $rowDetail)
                            <tr>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>
                                <td class="text-start">{{ $rowDetail->description }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                                <td class="text-end">{{number_format(round($rowDetail->rate_per_unit,2),2) }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->wastage,2),2) }} </td>
                                <td class="text-end">{{ number_format(round($rowDetail->bom_qty,2),2) }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->total_amount,2),2) }}</td>

                            </tr>
                            @php $fabricTotalAmt=$fabricTotalAmt + $rowDetail->total_amount; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="6" class="text-end"><b>Total:</b></td>
                                <td class="text-end">{{ number_format(round($fabricTotalAmt,2),2) }}</td>


                            </tr>
                        </tfoot>
                    </table>
                    <!-- second table -->
                    <h4 style="margin-top:-15px;" class="fw-bold">Sewing Trims Details:</h4>
                    <table class="table table-bordered table-sm table-fixed">
                        <thead>
                            <tr>
                                <th>Classification </th>
                                <th>Description </th>
                                <th>Cnsm/Gmt </th>
                                <th>Cost / Pcs </th>
                                <th>Wastage %</th>
                                <th>Cnsm/Gmt with Wastage %</th>
                                <th>Gmt Cost</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
                            ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
                            'sales_order_sewing_trims_costing_details.rate_per_unit',
                            'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);
                            $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();
                            $no=1;
                            $SewingTotalAmt=0;
                            @endphp
                            @foreach($SewingTrimsList as $rowDetail)
                            <tr>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>
                                <td class="text-start">{{ $rowDetail->description }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                                <td class="text-end">{{number_format(round($rowDetail->rate_per_unit,2),2) }}</td>
                                <td class="text-end"> {{ number_format(round($rowDetail->wastage,2),2) }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->bom_qty,2),2) }}</td>
                                <td class="text-end">{{ number_format(round($rowDetail->total_amount,2),2) }}</td>

                            </tr>
                            @php $SewingTotalAmt=$SewingTotalAmt + $rowDetail->total_amount;@endphp
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="6" class="text-end"><b>Total:</b></td>
                                <td class="text-end">{{ number_format(round($SewingTotalAmt,2),2)}}</td>


                            </tr>
                        </tfoot>
                    </table>

                    <!-- thirdd table -->
                    <div class="table-section">
                        <h4 style="margin-top:-15px;" class="table-section fw-bold">Packing Trims Details:</h4>
                        <table class="table table-bordered table-sm table-fixed" style="height: 100px;">
                            <thead>
                                <tr>
                                    <th>Classification </th>
                                    <th>Description </th>
                                    <th>Cnsm/Gmt </th>
                                    <th>Cost / Pcs </th>
                                    <th>Wastage %</th>
                                    <th> Cnsm/Gmt with Wastage %</th>
                                    <th> Gmt Cost</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_packing_trims_costing_details.class_id')->where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_packing_trims_costing_details.description','sales_order_packing_trims_costing_details.consumption',
                                'sales_order_packing_trims_costing_details.rate_per_unit',
                                'sales_order_packing_trims_costing_details.wastage','sales_order_packing_trims_costing_details.bom_qty','sales_order_packing_trims_costing_details.total_amount']);
                                $no=1;
                                $PackingTotalAmt=0;
                                @endphp
                                @foreach($PackingTrimsList as $rowDetail)
                                <tr>
                                    <td class="text-start">{{ $rowDetail->class_name }}</td>
                                    <td class="text-start">{{ $rowDetail->description }}</td>
                                    <td class="text-end">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                                    <td class="text-end">{{number_format(round($rowDetail->rate_per_unit,2),2) }}</td>
                                    <td class="text-end"> {{ number_format(round($rowDetail->wastage,2),2) }} </td>
                                    <td class="text-end"> {{ number_format(round($rowDetail->bom_qty,2),2) }} </td>
                                    <td class="text-end"> {{number_format(round($rowDetail->total_amount,2),2) }}</td>

                                </tr>
                                @php $PackingTotalAmt=$PackingTotalAmt + $rowDetail->total_amount; @endphp
                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>

                                    <td colspan="6" class="text-end"><b>Total:</b></td>
                                    <td class="text-end">{{ number_format(round($PackingTotalAmt,2),2)}}</td>


                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @php
                    if($SalesOrderCostingMaster[0]->fabric_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOffabric =($SalesOrderCostingMaster[0]->fabric_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOffabric = 0;
                    }
                    if($SalesOrderCostingMaster[0]->sewing_trims_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfsewing_trims_value=($SalesOrderCostingMaster[0]->sewing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfsewing_trims_value = 0;
                    }
                    if($SalesOrderCostingMaster[0]->packing_trims_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfpacking_trims_value=($SalesOrderCostingMaster[0]->packing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfpacking_trims_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->production_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfproduction_value=($SalesOrderCostingMaster[0]->production_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfproduction_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->agent_commision_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfagent_commision_value=($SalesOrderCostingMaster[0]->agent_commision_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfagent_commision_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->transaport_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOftransaport_value=($SalesOrderCostingMaster[0]->transaport_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOftransaport_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->other_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfother_value=($SalesOrderCostingMaster[0]->other_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfother_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->dbk_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfdbk_value=($SalesOrderCostingMaster[0]->dbk_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfdbk_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->printing_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfprinting_value=($SalesOrderCostingMaster[0]->printing_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfprinting_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->printing_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfembroidery_value=($SalesOrderCostingMaster[0]->embroidery_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfembroidery_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->ixd_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfixd_value=($SalesOrderCostingMaster[0]->ixd_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfixd_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->garment_reject_value > 0 && ($SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->dbk_value) > 0)
                    {
                    $percentOfgarment_reject_value=($SalesOrderCostingMaster[0]->garment_reject_value / ($SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->dbk_value)) * 100;
                    }
                    else
                    {
                    $percentOfgarment_reject_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->testing_charges_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOftesting_charges_value=($SalesOrderCostingMaster[0]->testing_charges_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOftesting_charges_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->finance_cost_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOffinance_cost_value=($SalesOrderCostingMaster[0]->finance_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOffinance_cost_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->extra_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfextra_value=($SalesOrderCostingMaster[0]->extra_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfextra_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->total_cost_value > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOftotal_cost_value=($SalesOrderCostingMaster[0]->total_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOftotal_cost_value = 0;
                    }

                    if($SalesOrderCostingMaster[0]->dbk_value1 > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $percentOfdbk_value1=($SalesOrderCostingMaster[0]->dbk_value1 / $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $percentOfdbk_value1 = 0;
                    }

                    $totalCost=$SalesOrderCostingMaster[0]->embroidery_value + $SalesOrderCostingMaster[0]->printing_value + $SalesOrderCostingMaster[0]->dbk_value + $SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value;

                    if($totalCost > 0 && $SalesOrderCostingMaster[0]->order_rate > 0)
                    {
                    $totalmakingper=($totalCost/ $SalesOrderCostingMaster[0]->order_rate) * 100;
                    }
                    else
                    {
                    $totalmakingper = 0;
                    }

                    $mainstyleData = DB::table('main_style_master')->select('mainstyle_image')->where('mainstyle_id', $SalesOrderCostingMaster[0]->mainstyle_id)->first();

                    @endphp

                    <!-- fourth table -->

                    <div style="display: flex; justify-content: space-between; gap: 10px; width: 100%;">
                        <!-- ===== LEFT TABLE ===== -->
                        <table class="table-bordered table-sm border-start border-end table-breakdown" style="width: 40%; ">
                            <tr>
                                <th colspan="3">Costing Summary</th>
                            </tr>
                            <tr>
                                <th>Particular</th>
                                <th>Cost / Pcs</th>
                                <th nowrap>% of FOB</th>
                            </tr>
                            <tr>
                                <td class="text-start">Fabric Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->fabric_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOffabric, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Trims Cost</td>
                                @php
                                $trimCost = $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value;
                                $totalCost = $SalesOrderCostingMaster[0]->embroidery_value + $SalesOrderCostingMaster[0]->printing_value + $SalesOrderCostingMaster[0]->dbk_value + $SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value;
                                @endphp
                                <td class="text-end">{{ number_format($trimCost,2) }}</td>
                                <td class="text-end">{{ number_format((float)($percentOfsewing_trims_value + $percentOfpacking_trims_value), 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Manufacturing Cost (CM)</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->production_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Garment Washing Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->dbk_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Printing Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->printing_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Embroidery Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->embroidery_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr class="section">
                                <td class="text-start">Total Making Cost</td>
                                <td class="text-end">{{ number_format($totalCost,2) }}</td>
                                <td class="text-end">{{ number_format((float)$totalmakingper, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Garment Rejection</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->garment_reject_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">IXD Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->ixd_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfixd_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Commission Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->agent_commision_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Transport Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->transaport_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Overhead Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->other_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
                            </tr>



                            <!-- ===== RIGHT TABLE ===== -->


                            <tr>
                                <td class="text-start">Testing Charges</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->testing_charges_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Finance Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->finance_cost_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">Other Cost Value</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->extra_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfextra_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr class="section">
                                <td class="text-start">Total Cost</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->total_cost_value,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
                            </tr>
                            <tr>
                                <td class="text-start">DBK Value 1</td>
                                <td class="text-end">{{ number_format($SalesOrderCostingMaster[0]->dbk_value1,2) }}</td>
                                <td class="text-end">{{ number_format((float)$percentOfdbk_value1, 2, '.', '') }}%</td>
                            </tr>
                            <tr class="section">
                                @php
                                $profit_value = ($SalesOrderCostingMaster[0]->order_rate - $SalesOrderCostingMaster[0]->total_cost_value + $SalesOrderCostingMaster[0]->dbk_value1);
                                $profitpercentage = (($profit_value / $SalesOrderCostingMaster[0]->order_rate) * 100);
                                @endphp
                                <td class="text-start">Profit %</td>
                                <td class="text-end">{{ number_format($profit_value,2) }}</td>
                                <td class="text-end">
                                    @if($SalesOrderCostingMaster[0]->order_type == 3)
                                    --
                                    @else
                                    {{ number_format((float)$profitpercentage, 2, '.', '') }}%
                                    @endif
                                </td>
                            </tr>
                            <tr class="section">
                                <td class="text-start">FOB Rate ({{ $SalesOrderCostingMaster[0]->currency_name }})</td>
                                <td class="text-end">{{ money_format('%!i',$SalesOrderCostingMaster[0]->inr_rate) }}</td>
                                <td class="text-end">--</td>
                            </tr>
                            <tr>
                                <td class="text-start">Exchange Rate</td>
                                <td class="text-end">{{ money_format('%!i',$SalesOrderCostingMaster[0]->exchange_rate) }}</td>
                                <td class="text-end">--</td>
                            </tr>
                            <tr>
                                <td class="text-start">Order Rate (INR)</td>
                                <td class="text-end">{{ money_format('%!i',$SalesOrderCostingMaster[0]->order_rate) }}</td>
                                <td class="text-end">--</td>
                            </tr>
                            <tr>
                                <td class="text-start">CMOHP</td>
                                @php
                                $profit_value = ($SalesOrderCostingMaster[0]->order_rate - $SalesOrderCostingMaster[0]->total_cost_value + $SalesOrderCostingMaster[0]->dbk_value1);
                                $cmohp = $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->other_value + $profit_value;
                                $cmohp_value = ($SalesOrderCostingMaster[0]->sam > 0) ? ($cmohp / $SalesOrderCostingMaster[0]->sam) : 0;
                                @endphp
                                <td class="text-end">{{ money_format('%!i',$cmohp_value) }}</td>
                                <td class="text-end">--</td>
                            </tr>
                            <tr>
                                <td class="text-start">CMOHP Value</td>
                                <td class="text-end">{{ money_format('%!i',$cmohp) }}</td>
                                <td class="text-end">--</td>
                            </tr>
                        </table>
                    </div>




                    <!-- Prepared & Verified -->
                    <br>
                    <br>

                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="text-4 mt-2">Prepared by: </h5>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-3">
                            <h5 class="text-4 mt-2" style="margin-left: -40px;">Approved by:</h5>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-4 mt-2" style="margin-left: 15px;">Comments</h5>
                    </div>


                </div>

            </main>

        </div>
    </div>

    <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
    <script>
        function html_table_to_excel(type) {
            var data = document.getElementById('invoice');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });

            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });

            XLSX.writeFile(file, 'Costing Sheet.' + type);
        }

        const export_button = document.getElementById('export_button');

        export_button.addEventListener('click', () => {
            html_table_to_excel('xlsx');
        });

        $('#printInvoice').click(function() {
            Popup($('.invoice')[0].outerHTML);

            function Popup(data) {
                //  window.print();
                return true;
            }
        });

        $('title').html("Costing Sheet");
    </script>
</body>

</html>