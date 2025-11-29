<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BOM Print | Ken Global Designs Pvt. Ltd.</title>
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
            background-color: #fff;
            color: #000000ff;
            padding: 20px;
        }

          .invoice-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            /* box-shadow: 0 0 10px rgba(7, 6, 6, 0.1); */
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
            --label-width: 170px;
        }

        :root {
            --bs-table-color: #000 !important;
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

        table.table th,
        table.table td {
            color: #000 !important;
            white-space: normal !important;

        }


        /*     PRINT STYLES      */

        @media print {
            @font-face {
                font-family: "Gotham";
                src: url("Gotham Fonts Family/Gotham-Regular.woff2") format("woff2"),
                    url("Gotham Fonts Family/Gotham-Regular.woff") format("woff"),
                    url("Gotham Fonts Family/Gotham-Regular.ttf") format("truetype");
                font-weight: 400;
                font-style: normal;
            }

            body {

                background-color: #fff;
                color: #000000ff;
                padding: 20pp
            }

            #printInvoice,
            #printInvoice * {
                visibility: visible;
            }

            #printInvoice {
                position: absolute;
                left: 0;
                top: 0;
                
               
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

           


            .table-bordered td,
            .table-bordered th {
                border: 2px solid #000 !important;
                font-size: 11pt;
                padding: 10px !important;
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

         @media print {

                .table-bordered th {
                    vertical-align: middle;
                    text-align: center;
                    white-space: nowrap;
                }

                table.table th,
                table.table td {
                    color: #000 !important;
                    white-space: normal !important;
                    font-size: 11pt;

                }

                /* th,
                td {
                    white-space: normal !important;
                    font-size: 10pt !important;
                   
              

                } */

                #printInvoice {
                    width: 100% !important;
                    max-width: 100% !important;
                    
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
                size: A4 landscape;
                margin: 10mm;
                margin-top: 12mm;
                margin-right: 1mm;
                border: 1px solid #000;
                /* some browsers support this */
               
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

            .invoice-container h4 {
                font-size: 20pt;
                /* make text a little smaller for print */
            }

            .table-bordered tr th:first-child,
            .table-bordered tr td:first-child {
                border-left: none !important;
            }

            .table-bordered tr th:last-child,
            .table-bordered tr td:last-child {
                border-right: none !important;
            }

            .summary-table {
                width: 500px !important;
                float: right !important;
                margin-left: auto !important;
                margin-right: 0 !important;
            }

            .summary-table th.text-start {
                text-align: left !important;
            }

            .summary-table td,
            .summary-table th {
                border: 2px solid #000 !important;
                /* force borders */
                padding: 6px !important;

            }

          

            .table-bordered.summary-table tr th:first-child,
            .table-bordered.summary-table tr td:first-child,
            .table-bordered.summary-table tr th:last-child,
            .table-bordered.summary-table tr td:last-child {
                border-left: 1px solid #000 !important;
                border-right: 1px solid #000 !important;
            }

            table.first th:nth-child(1),
            table.first td:nth-child(1) {
                width: 60px !important;
            }

            table.first th:nth-child(2),
            table.first td:nth-child(2) {
                width: auto !important;
            }

            table.first th:nth-child(3),
            table.first td:nth-child(3) {
                width: 400px !important;
            }

            table.first th:nth-child(4),
            table.first td:nth-child(4) {
                width: auto !important;

            }

            table.first th:nth-child(5),
            table.first td:nth-child(5) {
                width: auto !important;
            }

            table.first th:nth-child(6),
            table.first td:nth-child(6) {
                width: auto !important;
            }

            table.second th:nth-child(1),
            table.second td:nth-child(1) {
                width: 60px !important;
            }

            table.second th:nth-child(2),
            table.second td:nth-child(2) {
                width: 100px !important;
            }

            table.second th:nth-child(3),
            table.second td:nth-child(3) {
                width: 150px !important;
            }

            table.second th:nth-child(4),
            table.second td:nth-child(4) {
                width: auto !important;

            }

            table.second th:nth-child(5),
            table.second td:nth-child(5) {
                width: auto !important;
            }

            table.second th:nth-child(6),
            table.second td:nth-child(6) {
                width: auto !important;
            }

            table.second th:nth-child(7),
            table.second td:nth-child(7) {
                width: auto !important;
            }

            table.second th:nth-child(8),
            table.second td:nth-child(8) {
                width: 100px !important;
            }

            table.second th:nth-child(9),
            table.second td:nth-child(9) {
                width: 80px !important;
            }

            table.second th:nth-child(10),
            table.second td:nth-child(10) {
                width: 100px !important;
            }

            table.second th:nth-child(11),
            table.second td:nth-child(11) {
                width: 100px !important;
            }

            table.second th:nth-child(12),
            table.second td:nth-child(12) {
                width: 100px !important;
            }

            table.third th:nth-child(1),
            table.third td:nth-child(1) {
                width: 60px !important;
            }

            table.third th:nth-child(2),
            table.third td:nth-child(2) {
                width: auto !important;
            }

            table.third th:nth-child(3),
            table.third td:nth-child(3) {
                width: auto !important;
            }

            table.third th:nth-child(4),
            table.third td:nth-child(4) {
                width: auto !important;

            }

            table.third th:nth-child(5),
            table.third td:nth-child(5) {
                width: auto !important;
            }

            table.third th:nth-child(6),
            table.third td:nth-child(6) {
                width: auto !important;
            }

            table.third th:nth-child(7),
            table.third td:nth-child(7) {
                width: auto !important;
            }

            table.third th:nth-child(8),
            table.third td:nth-child(8) {
                width: auto !important;
            }

            table.third th:nth-child(9),
            table.third td:nth-child(9) {
                width: auto !important;
            }

            table.third th:nth-child(10),
            table.third td:nth-child(10) {
                width: auto !important;
            }

            table.third th:nth-child(11),
            table.third td:nth-child(11) {
                width: auto !important;
            }

            table.third th:nth-child(12),
            table.third td:nth-child(12) {
                width: auto !important;
            }

            table.third th:nth-child(13),
            table.third td:nth-child(13) {
                width: auto !important;
            }

            table.fourth th:nth-child(1),
            table.fourth td:nth-child(1) {
                width: 60px !important;
            }

            table.fourth th:nth-child(2),
            table.fourth td:nth-child(2) {
                width: auto !important;
            }

            table.fourth th:nth-child(3),
            table.fourth td:nth-child(3) {
                width: auto !important;
            }

            table.fourth th:nth-child(4),
            table.fourth td:nth-child(4) {
                width: 200px !important;

            }

            table.fourth th:nth-child(5),
            table.fourth td:nth-child(5) {
                width: 150px !important;
            }

            table.fourth th:nth-child(6),
            table.fourth td:nth-child(6) {
                width: 120px !important;
            }

            table.fourth th:nth-child(7),
            table.fourth td:nth-child(7) {
                width: 230px !important;
            }

            table.fourth th:nth-child(8),
            table.fourth td:nth-child(8) {
                width: 180px !important;
            }

            table.fourth th:nth-child(9),
            table.fourth td:nth-child(9) {
                width: auto !important;
            }

            table.fourth th:nth-child(10),
            table.fourth td:nth-child(10) {
                width: auto !important;
            }

            table.fourth th:nth-child(11),
            table.fourth td:nth-child(11) {
                width: auto !important;
            }

            table.fourth th:nth-child(12),
            table.fourth td:nth-child(12) {
                width: auto !important;
            }

            table.fourth th:nth-child(13),
            table.fourth td:nth-child(13) {
                width: auto !important;
            }

            table.fifth th:nth-child(1),
            table.fifth td:nth-child(1) {
                width: 60px !important;
            }

            table.fifth th:nth-child(2),
            table.fifth td:nth-child(2) {
                width: auto !important;
            }

            table.fifth th:nth-child(3),
            table.fifth td:nth-child(3) {
                width: auto !important;
            }

            table.fifth th:nth-child(4),
            table.fifth td:nth-child(4) {
                width: 200px !important;

            }

            table.fifth th:nth-child(5),
            table.fifth td:nth-child(5) {
                width: 150px !important;
            }

            table.fifth th:nth-child(6),
            table.fifth td:nth-child(6) {
                width: 120px !important;
            }

            table.fifth th:nth-child(7),
            table.fifth td:nth-child(7) {
                width: 230px !important;
            }

            table.fifth th:nth-child(8),
            table.fifth td:nth-child(8) {
                width: 180px !important;
            }

            table.fifth th:nth-child(9),
            table.fifth td:nth-child(9) {
                width: auto !important;
            }

            table.fifth th:nth-child(10),
            table.fifth td:nth-child(10) {
                width: auto !important;
            }

            table.fifth th:nth-child(11),
            table.fifth td:nth-child(11) {
                width: auto !important;
            }

            table.fifth th:nth-child(12),
            table.fifth td:nth-child(12) {
                width: auto !important;
            }

            table.fifth th:nth-child(13),
            table.fifth td:nth-child(13) {
                width: auto !important;
            }



        }
          @media print {





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

            }
    </style>
</head>

<body>
    @if(empty($BOMList) || !isset($BOMList[0]))
    <h3 class="text-danger text-center">BOM Data Not Found</h3>
    @php return; @endphp
    @endif

    <!-- Buttons -->
    <!-- Buttons -->
    <div class="  mb-3 d-print-none" style="margin-left: 1200px;">
        <button class="btn btn-primary" onclick="window.print()">
            Print
        </button>
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

                    <h3 class=" fw-bold  text-center"> </h3>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">BOM Date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BOMList[0]->bom_date }}</div>

                                </div>
                                <div class="info-row">
                                    <div class="label">BOM No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BOMList[0]->bom_code }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Sales Order no</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->sales_order_no }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Buyer</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->Ac_name }} </div>
                                </div>



                            </div>

                        </div>
                        <div class="col-md-6 p-2">

                            <div class="info-row">
                                <div class="label">Cost Type </div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->cost_type_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">FOB Rate</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->order_rate }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Season</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $BOMList[0]->season_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Main Style Category</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->mainstyle_name }}</div>
                            </div>


                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">


                                <div class="info-row">
                                    <div class="label">Sub Style Category</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->substyle_name }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style Name</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BOMList[0]->fg_name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style No:</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->style_no }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Currency</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->currency_name }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Buyer Order No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->bom_code }} </div>
                                </div>

                            </div>


                        </div>

                    </div>
                    @php
                    $BuyerPurchaseOrderMasterList = App\Models\BuyerPurchaseOrderMasterModel::find($BOMList[0]->sales_order_no);
                    $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
                    @endphp

                    <!-- Assortment Table -->
                    <h4 class="text-center  fw-bold">Assortment Details</h4>
                    <table class="table table-bordered table-sm first">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>

                                <th>Color</th>

                                @foreach ($SizeDetailList as $sz)

                                <th>{{$sz->size_name}}</th>

                                @endforeach
                                <th>Total Qty </th>



                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $sizes='';
                            $no=1;
                            foreach ($SizeDetailList as $sz)
                            {
                            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                            $no=$no+1;
                            }
                            $sizes=rtrim($sizes,',');


                            $MasterdataList = DB::select("SELECT sales_order_detail.item_code,item_image_path,sales_order_detail.color_id, color_master.color_name, ".$sizes.",
                            sum(size_qty_total) as size_qty_total from sales_order_detail
                            inner join color_master on color_master.color_id=sales_order_detail.color_id
                            inner join item_master on item_master.item_code=sales_order_detail.item_code
                            where tr_code='".$BOMList[0]->sales_order_no."' group by sales_order_detail.color_id");

                            $no=1;

                            @endphp


                            @foreach($MasterdataList as $rowDataList)
                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDataList->item_code }}</td>

                                <td class="text-start">{{ $rowDataList->color_name }}</td>
                                @if(isset($rowDataList->s1)) <td class="text-end">{{$rowDataList->s1}}</td> @endif
                                @if(isset($rowDataList->s2)) <td class="text-end">{{$rowDataList->s2}}</td>@endif
                                @if(isset($rowDataList->s3)) <td class="text-end">{{$rowDataList->s3}}</td>@endif
                                @if(isset($rowDataList->s4)) <td class="text-end">{{$rowDataList->s4}}</td>@endif
                                @if(isset($rowDataList->s5)) <td class="text-end">{{$rowDataList->s5}}</td>@endif
                                @if(isset($rowDataList->s6)) <td class="text-end">{{$rowDataList->s6}}</td>@endif
                                @if(isset($rowDataList->s7)) <td class="text-end">{{$rowDataList->s7}}</td>@endif
                                @if(isset($rowDataList->s8)) <td class="text-end">{{$rowDataList->s8}}</td>@endif
                                @if(isset($rowDataList->s9)) <td class="text-end">{{$rowDataList->s9}}</td>@endif
                                @if(isset($rowDataList->s10)) <td class="text-end">{{$rowDataList->s10}}</td>@endif
                                @if(isset($rowDataList->s11)) <td class="text-end">{{$rowDataList->s11}}</td>@endif
                                @if(isset($rowDataList->s12)) <td class="text-end">{{$rowDataList->s12}}</td>@endif
                                @if(isset($rowDataList->s13)) <td class="text-end">{{$rowDataList->s13}}</td>@endif
                                @if(isset($rowDataList->s14)) <td class="text-end">{{$rowDataList->s14}}</td>@endif
                                @if(isset($rowDataList->s15)) <td class="text-end">{{$rowDataList->s15}}</td>@endif
                                @if(isset($rowDataList->s16)) <td class="text-end">{{$rowDataList->s16}}</td>@endif
                                @if(isset($rowDataList->s17)) <td class="text-end">{{$rowDataList->s17}}</td>@endif
                                @if(isset($rowDataList->s18)) <td class="text-end">{{$rowDataList->s18}}</td>@endif
                                @if(isset($rowDataList->s19)) <td class="text-end">{{$rowDataList->s19}}</td>@endif
                                @if(isset($rowDataList->s20)) <td class="text-end">{{$rowDataList->s20}}</td> @endif
                                <td class="text-end">{{number_format($rowDataList->size_qty_total)}} </td>

                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach

                            <tr>

                                <td colspan="3" class="text-end">Total : </td>
                                @php
                                $SizeWsList=explode(',', $BuyerPurchaseOrderMasterList->sz_ws_total);
                                @endphp
                                @foreach($SizeWsList as $sztotal)
                                <td class="text-end">{{ number_format($sztotal) }}</td>

                                @endforeach
                                <td class="text-end">{{ number_format($BuyerPurchaseOrderMasterList->total_qty) }}</td>
                            </tr>

                        </tbody>


                    </table>

                    <h4 class="text-center  fw-bold" style="margin-top: -11px;">Fabric Details</h4>
                    <table class="table table-bordered table-sm second">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>


                                <th>PO Code</th>
                                <th>Item Name </th>

                                <th>Colors</th>
                                <th>Classification</th>
                                <th>Description</th>
                                <th>Cons (Mtr/Nos)</th>
                                <th>UOM </th>
                                <th>Wastage %</th>
                                <th>BOM Qty</th>
                                <th>Remark</th>




                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $FabricList = App\Models\BOMFabricDetailModel::
                            join('item_master','item_master.item_code','=','bom_fabric_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','bom_fabric_details.class_id')
                            ->join('unit_master','unit_master.unit_id','=','bom_fabric_details.unit_id')

                            ->where('bom_fabric_details.bom_code','=', $BOMList[0]->bom_code)->get();


                            $no=1;

                            @endphp


                            @foreach($FabricList as $rowDetail)
                            @php

                            $purchaseData = DB::SELECT("SELECT pur_code from purchaseorder_detail WHERE sales_order_no='".$rowDetail->sales_order_no."' AND item_code = '".$rowDetail->item_code."'");

                            $po_codes = "";
                            foreach($purchaseData as $purs)
                            {
                            $po_codes .= $purs->pur_code.",";
                            }
                            @endphp
                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>

                                <td class="text-center">{{ rtrim($po_codes,",") }}</td>
                                <td class="text-start">{{ $rowDetail->item_name }}</td>
                                @php

                                $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
                                'color_master.color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                                ->where('item_code','=',$rowDetail->item_code)->where('tr_code','=',$BOMList[0]->sales_order_no)->DISTINCT()->get();

                                $data='';
                                foreach($ColorList as $row)
                                {
                                $data=$data.$row->color_name.', ';
                                }


                                @endphp
                                <td class="text-start">{{rtrim($data,',')}} </td>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>

                                <td class="text-start">{{ $rowDetail->description  }}</td>

                                <td class="text-end">{{ $rowDetail->consumption  }}</td>
                                <td class="text-center">{{ $rowDetail->unit_name  }}</td>

                                <td class="text-end">{{ $rowDetail->wastage  }}</td>
                                <td class="text-end">{{number_format($rowDetail->bom_qty)}}</td>
                                <td class="text-start">{{ $rowDetail->remark  }}</td>







                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach

                        </tbody>

                    </table>
                    <h4 class="text-center  fw-bold" style="margin-top: -11px;">Trim Fabric Details</h4>
                    <table class="table table-bordered table-sm third">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>


                                <th>PO Code</th>
                                <th> Fabric Color Code </th>
                                <th>Classification</th>
                                <th>Description</th>

                                <th>Garment Color</th>
                                <th>Size </th>
                                <th>Cons (Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <th>BOM Qty</th>
                                <th>Remark</th>




                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $TrimFabricList = App\Models\BOMTrimFabricDetailModel::
                            join('item_master','item_master.item_code','=','bom_trim_fabric_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','bom_trim_fabric_details.class_id')
                            ->join('unit_master','unit_master.unit_id','=','bom_trim_fabric_details.unit_id')

                            ->where('bom_trim_fabric_details.bom_code','=', $BOMList[0]->bom_code)->get();

                            $no=1;

                            @endphp
                            @foreach($TrimFabricList as $rowDetailtrimfabric)



                            @php

                            $color_ids = explode(',', $rowDetailtrimfabric->color_id);

                            $size_ids = explode(',', $rowDetailtrimfabric->size_array);

                            $ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

                            $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');

                            $purchaseData1 = DB::SELECT("SELECT pur_code from purchaseorder_detail WHERE sales_order_no='".$rowDetailtrimfabric->sales_order_no."' AND item_code = '".$rowDetailtrimfabric->item_code."'");

                            $po_codes1 = "";
                            foreach($purchaseData1 as $purs)
                            {
                            $po_codes1 .= $purs->pur_code.",";
                            }
                            @endphp
                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetailtrimfabric->item_code }}</td>



                                <td class="text-center">{{ rtrim($po_codes1,",") }}</td>
                                <td class="text-start">{{ $rowDetailtrimfabric->item_name }} </td>
                                <td class="text-start">{{ $rowDetailtrimfabric->class_name }}</td>

                                <td class="text-start">{{ $rowDetailtrimfabric->description  }}</td>

                                <td class="text-start"> @php
                                    $color="";
                                    foreach($ColorList as $Colors)
                                    {

                                    $color= $color.$Colors->color_name.', ';

                                    }
                                    @endphp
                                    {{ rtrim($color,", ")    }}
                                </td>
                                <td class="text-end"> @php
                                    $size="";
                                    foreach($SizeDetailList as $sizes)
                                    {
                                    $size= $size.$sizes->size_name.','; }
                                    @endphp
                                    {{ rtrim($size,",") }}
                                </td>

                                <td class="text-end">{{ $rowDetailtrimfabric->consumption  }}</td>
                                <td class="text-center">{{ $rowDetailtrimfabric->unit_name  }}</td>
                                <td class="text-end">{{ $rowDetailtrimfabric->wastage  }}</td>
                                <td class="text-end">{{number_format($rowDetailtrimfabric->bom_qty)}}</td>
                                <td class="text-start">{{ $rowDetailtrimfabric->remark  }}</td>







                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach
                        </tbody>

                    </table>

                    <h4 class="text-center  fw-bold" style="margin-top: -11px;">Sewing Trims</h4>
                    <table class="table table-bordered table-sm fourth">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>


                                <th>PO Code</th>
                                <th> Item Name </th>
                                <th>Classification</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Size</th>

                                <th>Cons (Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <th>BOM Qty</th>
                                <th>Remark</th>




                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $SewingTrimsList = App\Models\BOMSewingTrimsDetailModel::
                            join('item_master','item_master.item_code','=','bom_sewing_trims_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','bom_sewing_trims_details.class_id')
                            ->join('unit_master','unit_master.unit_id','=','bom_sewing_trims_details.unit_id')

                            ->where('bom_sewing_trims_details.bom_code','=', $BOMList[0]->bom_code)->get();

                            $no=1;

                            @endphp



                            @foreach($SewingTrimsList as $rowDetailtrims)
                            @php

                            $color_ids = explode(',', $rowDetailtrims->color_id);

                            $size_ids = explode(',', $rowDetailtrims->size_array);

                            $ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

                            $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');

                            $purchaseData2 = DB::SELECT("SELECT pur_code from purchaseorder_detail WHERE sales_order_no='".$rowDetailtrims->sales_order_no."' AND item_code = '".$rowDetailtrims->item_code."'");

                            $po_codes2 = "";
                            foreach($purchaseData2 as $purs)
                            {
                            $po_codes2 .= $purs->pur_code.",";
                            }

                            @endphp

                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetailtrims->item_code }}</td>

                                <td class="text-center">{{ rtrim($po_codes2,",") }}</td>
                                <td class="text-start">{{ $rowDetailtrims->item_name }}</td>
                                <td class="text-start">{{ $rowDetailtrims->class_name }} </td>
                                <td class="text-start">{{ $rowDetailtrims->description  }}</td>

                                <td class="text-start"> @php
                                    $color="";
                                    foreach($ColorList as $Colors)
                                    {

                                    $color= $color.$Colors->color_name.', ';

                                    }
                                    @endphp
                                    {{ rtrim($color,", ")    }}
                                </td>

                                <td class="text-end"> @php
                                    $size="";
                                    foreach($SizeDetailList as $sizes)
                                    {
                                    $size= $size.$sizes->size_name.','; }
                                    @endphp
                                    {{ rtrim($size,",") }}
                                </td>
                                <td class="text-end">{{ $rowDetailtrims->consumption  }}</td>

                                <td class="text-center">{{ $rowDetailtrims->unit_name  }}</td>
                                <td class="text-end">{{ $rowDetailtrims->wastage  }}</td>
                                <td class="text-end">{{number_format($rowDetailtrims->bom_qty)}}</td>
                                <td class="text-start">{{ $rowDetailtrims->remark  }}</td>








                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach
                        </tbody>

                    </table>

                    <h4 class="text-center  fw-bold" style="margin-top: -11px;">Packing Trims</h4>
                    <table class="table table-bordered table-sm fifth">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>


                                <th>PO Code</th>
                                <th> Item Name </th>
                                <th>Classification</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Cons (Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <th>BOM Qty</th>
                                <th>Remark</th>




                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $PackingTrimsList = App\Models\BOMPackingTrimsDetailModel::leftJoin('item_master','item_master.item_code','=','bom_packing_trims_details.item_code')
                            ->leftJoin('classification_master','classification_master.class_id','=','bom_packing_trims_details.class_id')
                            ->leftJoin('unit_master','unit_master.unit_id','=','bom_packing_trims_details.unit_id')
                            ->where('bom_packing_trims_details.bom_code','=', $BOMList[0]->bom_code)->get();

                            $nos=1;

                            @endphp




                            @foreach($PackingTrimsList as $rowDetailpacking)



                            @php

                            $colorids = explode(',', $rowDetailpacking->color_id);

                            $sizeids = explode(',', $rowDetailpacking->size_array);


                            $ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');

                            $SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');

                            $purchaseData3 = DB::SELECT("SELECT pur_code from purchaseorder_detail WHERE sales_order_no='".$rowDetailpacking->sales_order_no."' AND item_code = '".$rowDetailpacking->item_code."'");

                            $po_codes3 = "";
                            foreach($purchaseData3 as $purs)
                            {
                            $po_codes3 .= $purs->pur_code.",";
                            }

                            @endphp
                            <tr>
                                <td class="text-end">{{ $nos }}</td>
                                <td class="text-center">{{ $rowDetailpacking->item_code }}</td>

                                <td class="text-center">{{ rtrim($po_codes3,",") }}</td>
                                <td class="text-start">{{ $rowDetailpacking->item_name }}</td>
                                <td class="text-start">{{ $rowDetailpacking->class_name }}</td>
                                <td class="text-start">{{ $rowDetailpacking->description  }}</td>

                                <td class="text-start"> @php
                                    $colorpack="";
                                    foreach($ColorListpacking as $Colorspacking)
                                    {

                                    $colorpack= $colorpack.$Colorspacking->color_name.', ';

                                    }
                                    @endphp
                                    {{ rtrim($colorpack,", ")    }}

                                </td>

                                <td class="text-end">@php
                                    $sizepack="";
                                    foreach($SizeDetailListpacking as $sizespacking)
                                    {
                                    $sizepack= $sizepack.$sizespacking->size_name.','; }
                                    @endphp
                                    {{ rtrim($sizepack,",") }}
                                </td>
                                <td class="text-end">{{ $rowDetailpacking->consumption  }}</td>

                                <td class="text-center">{{ $rowDetailpacking->unit_name  }}</td>
                                <td class="text-end">{{ $rowDetailpacking->wastage  }}</td>
                                <td class="text-end">{{number_format($rowDetailpacking->bom_qty)}}</td>
                                <td class="text-start">{{ $rowDetailpacking->remark  }}</td>








                            </tr>
                            @php

                            $nos=$nos+1;
                            @endphp
                            @endforeach

                        </tbody>

                    </table>






                </div>

            </main>
        </div>
    </div>

    <p class="text-center d-print-none"><a href="#">&laquo; Back to List</a></p>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;

            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
</body>

</html>