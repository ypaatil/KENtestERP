<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
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

                .table-bordered th {
                    vertical-align: middle;
                    text-align: center;
                    white-space: nowrap;
                }

                table.table th,
                table.table td {
                    color: #000 !important;
                    white-space: normal !important;

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

            .table-bordered.summary-table tr th:first-child,
            .table-bordered.summary-table tr td:first-child,
            .table-bordered.summary-table tr th:last-child,
            .table-bordered.summary-table tr td:last-child {
                border-left: 1px solid #000 !important;
                border-right: 1px solid #000 !important;
            }
              table.first th:nth-child(1),
            table.first td:nth-child(1) {
                width: 65px !important;
            }

            table.first th:nth-child(2),
            table.first td:nth-child(2) {
                width: 200px !important;
            }

            table.first th:nth-child(3),
            table.first td:nth-child(3) {
                width: auto !important;
            }

            table.first th:nth-child(4),
            table.first td:nth-child(4) {
                width: auto !important;

            }

            
            table.second th:nth-child(1),
            table.second td:nth-child(1) {
                width: 56px !important;
            }

            table.second th:nth-child(2),
            table.second td:nth-child(2) {
                width: auto !important;
            }

            table.second th:nth-child(3),
            table.second td:nth-child(3) {
                width: 200px !important;
            }

            table.second th:nth-child(4),
            table.second td:nth-child(4) {
                width: auto !important;

            }

            table.second th:nth-child(5),
            table.second td:nth-child(5) {
                width: 100px !important;
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
                width: auto !important;
            }

            table.second th:nth-child(9),
            table.second td:nth-child(9) {
                width: auto !important;
            }

            table.second th:nth-child(10),
            table.second td:nth-child(10) {
                width: auto !important;
            }

            table.second th:nth-child(11),
            table.second td:nth-child(11) {
                width: auto !important;
            }

            table.second th:nth-child(12),
            table.second td:nth-child(12) {
                width: auto !important;
            }


        }
    </style>
</head>
@php
$BuyerPurchaseOrderMasterList = App\Models\BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.*','brand_master.brand_name')
->join('brand_master', 'brand_master.brand_id',"=",'buyer_purchse_order_master.brand_id')
->where('tr_code',"=",$BOMList[0]->sales_order_no)->get();

@endphp

<body>
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

                    <h3 class=" fw-bold  text-center"> PACKING ORDER</h3>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">PO Date</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ date('d-m-Y',strtotime($BOMList[0]->vpo_date)) }}</div>

                                </div>
                                <div class="info-row">
                                    <div class="label">Process Order No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BOMList[0]->vpo_code }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PO Delivery Date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ date('d-m-Y',strtotime($BOMList[0]->delivery_date)) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Sales Order no</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BOMList[0]->sales_order_no }}</div>
                                </div>



                            </div>

                        </div>
                        <div class="col-md-6 p-2">
                            <div class="info-row">
                                <div class="label">Buyer Brand</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BuyerPurchaseOrderMasterList[0]->brand_name }}  </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Main Style Category</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->mainstyle_name }}  </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Sub Style Category</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->substyle_name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->fg_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style No</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $BOMList[0]->style_no }} </div>
                            </div>


                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                            
                                <!-- <p><b>Vendor:</b> <br>KEN GLOBAL DESIGNS - UNIT - 1 (KONDIGRE)</p>
                                <p><b>Address:</b> <br>GAT NO 298/299,A/P Kondigre, Kolhapur, Maharashtra, 416101 </p> -->
                                <div class="info-row">
                                    <div class="label">Vendor</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->Ac_name }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Address</div>
                                    <div class="colon">:</div>
                                      <div class="value">{{ $BOMList[0]->vendorName }} </div>
                                    <div class="value">{{ $BOMList[0]->address }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->gst_no }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PAN No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $BOMList[0]->pan_no }} </div>
                                </div>

                            </div>


                        </div>
                      
                    </div>
                    @php



                    $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
                    $sizes='';
                    $no=1;
                    foreach ($SizeDetailList as $sz)
                    {
                    $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                    $no=$no+1;
                    }
                    $sizes=rtrim($sizes,',');
                    // DB::enableQueryLog();



                    $VendorPurchaseOrderDetailList = App\Models\VendorPurchaseOrderDetailModel::where('vendor_purchase_order_detail.vpo_code','=', $BOMList[0]->vpo_code)
                    ->join('color_master','color_master.color_id','=','vendor_purchase_order_detail.color_id')
                    ->get(['vendor_purchase_order_detail.*','color_master.color_name']);



                    @endphp

                    <!-- Assortment Table -->
                    <h4 class="text-start   fw-bold">Assortment Details:</h4>
                    <table class="table table-bordered table-sm first">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Color</th>
                                @foreach ($SizeDetailList as $sz)

                                <th>{{$sz->size_name}}</th>

                                @endforeach
                                <th>Total Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach($VendorPurchaseOrderDetailList as $rowDataList)
                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-start">{{ $rowDataList->color_name }}</td>
                                @php
                                $SizeQtyList=explode(',', $rowDataList->size_qty_array)
                                @endphp
                                @foreach($SizeQtyList as $szQty)
                                <td class="text-end">{{ $szQty }} </td>
                                @endforeach
                                <td class="text-end">{{ $rowDataList->size_qty_total }}</td>

                            </tr>
                            @php

                            $no=$no+1;

                            @endphp
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-end">Total : </td>
                                @php
                                $nox=1;$sizex='';
                                foreach ($SizeDetailList as $sz)
                                {
                                $sizex=$sizex.'sum(s'.$nox.') as s'.$nox.',';
                                $nox=$nox+1;
                                }
                                $sizex=rtrim($sizex,',');


                                $SizeTotal= DB::select("select ".$sizex." , sum(size_qty_total) as Total from vendor_purchase_order_size_detail where vpo_code='".$BOMList[0]->vpo_code."'");
                                @endphp


                                @php foreach($SizeTotal as $row)
                                {

                                if(isset($row->s1)) { echo '<td class="text-end">'.$row->s1.'</td>' ; }
                                if(isset($row->s2)) { echo '<td class="text-end">'.$row->s2.'</td>' ; }
                                if(isset($row->s3)) { echo '<td class="text-end">'.$row->s3.'</td>' ; }
                                if(isset($row->s4)) {echo '<td class="text-end">'.$row->s4.'</td>' ; }
                                if(isset($row->s5)) { echo '<td class="text-end">'.$row->s5.'</td>' ; }
                                if(isset($row->s6)) { echo '<td class="text-end">'.$row->s6.'</td>' ; }
                                if(isset($row->s7)) { echo '<td class="text-end">'.$row->s7.'</td>' ;}
                                if(isset($row->s8)) { echo '<td class="text-end">'.$row->s8.'</td>' ;}
                                if(isset($row->s9)) { echo '<td class="text-end">'.$row->s9.'</td>' ;}
                                if(isset($row->s10)) { echo '<td class="text-end">'.$row->s10.'</td>' ;}
                                if(isset($row->s11)) {echo '<td class="text-end">'.$row->s11.'</td>' ;}
                                if(isset($row->s12)) {echo '<td class="text-end">'.$row->s12.'</td>' ;}
                                if(isset($row->s13)) { echo '<td class="text-end">'.$row->s13.'</td>' ;}
                                if(isset($row->s14)) { echo '<td class="text-end">'.$row->s14.'</td>';}
                                if(isset($row->s15)) {echo '<td class="text-end">'.$row->s15.'</td>' ;}
                                if(isset($row->s16)) {echo '<td class="text-end">'.$row->s16.'</td>' ;}
                                if(isset($row->s17)) {echo '<td class="text-end">'.$row->s17.'</td>' ;}
                                if(isset($row->s18)) { echo '<td class="text-end">'.$row->s18.'</td>' ;}
                                if(isset($row->s19)) { echo '<td class="text-end">'.$row->s19.'</td>' ;}
                                if(isset($row->s20)) {echo '<td class="text-end">'.$row->s20.'</td>' ;}
                                echo '<td class="text-end">'.$row->Total.'</td>' ;
                                }




                                @endphp
                            </tr>

                        </tbody>

                    </table>
                    <div></div>
                    @if($BOMList[0]->process_id==1)
                    <h4 class="text-start  fw-bold">Fabric Details:</h4>
                    <table class="table table-bordered text-1 table-sm second" >
                        <thead>
                            <tr style=" text-align:center;">
                                <th>SrNo</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Colors</th>
                                <th>Classification</th>
                              
                                <th>Cons(Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <!--<th>Final Cons</th>-->
                                <!--<th>Piece Qty</th>-->
                                <th>Work Order Req. Qty (Incl Wastage)</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            //DB::enableQueryLog();
                            $FabricList = App\Models\VendorPurchaseOrderFabricDetailModel::
                            select('item_master.item_name','classification_master.class_name','vendor_purchase_order_fabric_details.description','vendor_purchase_order_fabric_details.item_code',
                            'vendor_purchase_order_fabric_details.consumption','unit_master.unit_name','vendor_purchase_order_fabric_details.wastage',
                            'vendor_purchase_order_fabric_details.final_cons')
                            ->join('item_master','item_master.item_code','=','vendor_purchase_order_fabric_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','vendor_purchase_order_fabric_details.class_id')
                            ->join('unit_master','unit_master.unit_id','=','vendor_purchase_order_fabric_details.unit_id')
                            ->selectRaw('sum(vendor_purchase_order_fabric_details.bom_qty) as totalbom_qty,vendor_purchase_order_fabric_details.size_qty as totalsize_qty')
                            ->where('vendor_purchase_order_fabric_details.vpo_code','=', $BOMList[0]->vpo_code)
                            ->groupBy('vendor_purchase_order_fabric_details.item_code')
                            ->get();
                            // dd(DB::getQueryLog());
                            $no=1;
                            @endphp
                            @foreach($FabricList as $rowDetail)
                            @php

                            $RemarkList= App\Models\BOMFabricDetailModel::select('remark')->
                            where('item_code', $rowDetail->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();

                            $ColorLists = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
                            'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                            ->where('item_code','=',$rowDetail->item_code)->where('tr_code','=',$BOMList[0]->sales_order_no)->DISTINCT()->get();

                            $colors='';
                            foreach($ColorLists as $row)
                            {
                            $colors=$colors.$row->color_name.', ';
                            }
                            @endphp

                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>
                                <td class="text-start">{{ $rowDetail->item_name }}</td>
                                <td class="text-start">{{ $colors }}</td>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>
                              
                                <td class="text-end">{{ $rowDetail->consumption  }}</td>
                                <td class="text-center">{{ $rowDetail->unit_name  }}</td>
                                <td class="text-end">{{ $rowDetail->wastage  }}</td>
                                <td class="text-end">{{ $rowDetail->totalbom_qty  }}</td>
                                <td class="text-end">{{ isset($RemarkList[0]->remark) ? $RemarkList[0]->remark : ''  }}</td>
                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    <h4 class="text-start fw-bold ">Trim Fabric Details:</h4>
                    <table class="table table-bordered text-1 table-sm second">
                        <thead>
                            <tr style=" text-align:center;">
                                <th>SrNo</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Colors</th>
                                <th>Classification</th>
                            
                                <th>Cons(Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <!--<th>Final Cons</th>-->
                                <!--<th>Piece Qty</th>-->
                                <th>Work Order Req. Qty (Incl Wastage)</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php

                            $FabricList = App\Models\VendorPurchaseOrderTrimFabricDetailModel::
                            select('item_master.item_name','classification_master.class_name','vendor_purchase_order_trim_fabric_details.description',
                            'vendor_purchase_order_trim_fabric_details.item_code',
                            'vendor_purchase_order_trim_fabric_details.consumption','unit_master.unit_name','vendor_purchase_order_trim_fabric_details.wastage',
                            'vendor_purchase_order_trim_fabric_details.final_cons')
                            ->join('item_master','item_master.item_code','=','vendor_purchase_order_trim_fabric_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','vendor_purchase_order_trim_fabric_details.class_id')
                            ->join('unit_master','unit_master.unit_id','=','vendor_purchase_order_trim_fabric_details.unit_id')
                            ->selectRaw('sum(vendor_purchase_order_trim_fabric_details.bom_qty) as totalbom_qty,vendor_purchase_order_trim_fabric_details.size_qty as totalsize_qty')
                            ->where('vendor_purchase_order_trim_fabric_details.vpo_code','=', $BOMList[0]->vpo_code)
                            ->groupBy('vendor_purchase_order_trim_fabric_details.item_code')
                            ->get();


                            $no=1;

                            @endphp





                            @foreach($FabricList as $rowDetail)

                            @php
                            // DB::enableQueryLog();
                            $ColorListpacking= App\Models\BOMTrimFabricDetailModel::select('color_id','remark')->
                            where('item_code', $rowDetail->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();

                            // $query = DB::getQueryLog();
                            // $query = end($query);
                            // dd($query);
                            $colorids = explode(',', isset($ColorListpacking[0]->color_id) ? $ColorListpacking[0]->color_id : "" );
                            //$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');


                            $ColorListpacking= App\Models\VendorPurchaseOrderDetailModel::
                            join('color_master','vendor_purchase_order_detail.color_id','=','color_master.color_id')
                            ->where('vendor_purchase_order_detail.sales_order_no', $BOMList[0]->sales_order_no)
                            ->where('vendor_purchase_order_detail.vpo_code', $BOMList[0]->vpo_code)
                            ->whereIn('vendor_purchase_order_detail.color_id', $colorids)->where('delflag','=', '0')->distinct('color_master.color_id')->get('color_name');




                            $colorstrimfabric='';
                            foreach($ColorListpacking as $colorpk)
                            {
                            $colorstrimfabric=$colorstrimfabric.$colorpk->color_name.', ';
                            }
                            @endphp
                            <tr>
                                <td class="text-end">{{ $no }}</td>
                                <td  class="text-center">{{ $rowDetail->item_code }}</td>
                                <td  class="text-start">{{ $rowDetail->item_name }}</td>
                                <td class="text-start">{{rtrim($colorstrimfabric, ', ');}} </td>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>
                           
                                <td  class="text-end">{{ $rowDetail->consumption  }}</td>
                                <td class="text-center">{{ $rowDetail->unit_name  }}</td>
                                <td  class="text-end">{{ $rowDetail->wastage  }}</td>
                                <td  class="text-end">{{ $rowDetail->totalbom_qty  }}</td>
                                <td class="text-end">{{ isset($ColorListpacking[0]->remark) ? $ColorListpacking[0]->remark : "" }}</td>
                            </tr>
                            @php

                            $no=$no+1;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>



                    @elseif($BOMList[0]->process_id==2)

                    @elseif($BOMList[0]->process_id==3)

                    <h4 class="text-start fw-bold " >Packing Trims:</h4>

                    <table class="table table-bordered text-1 table-sm second" >
                        <thead>
                            <tr style=" text-align:center;">
                                <th>SrNo</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Color</th>
                                <th>Sizes</th>
                                <th>Classification</th>
                               
                                <th>Cons(Mtr/Nos)</th>
                                <th>UOM</th>
                                <th>Wastage %</th>
                                <!--<th>Final Cons</th>-->
                                <!--<th>Piece Qty</th>-->
                                <th>Work Order Req. Qty (Incl Wastage)</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                            $PackingTrimsList = App\Models\VendorPurchaseOrderPackingTrimsDetailModel::
                            select('item_master.item_name','classification_master.class_name','vendor_purchase_order_packing_trims_details.sales_order_no','vendor_purchase_order_packing_trims_details.description','vendor_purchase_order_packing_trims_details.item_code',
                            'vendor_purchase_order_packing_trims_details.consumption','unit_master.unit_name','vendor_purchase_order_packing_trims_details.wastage','vendor_purchase_order_packing_trims_details.final_cons')
                            ->leftJoin('item_master','item_master.item_code','=','vendor_purchase_order_packing_trims_details.item_code')
                            ->leftJoin('classification_master','classification_master.class_id','=','vendor_purchase_order_packing_trims_details.class_id')
                            ->leftJoin('unit_master','unit_master.unit_id','=','vendor_purchase_order_packing_trims_details.unit_id')
                            ->selectRaw('sum(DISTINCT vendor_purchase_order_packing_trims_details.bom_qty) as totalbom_qty,sum(DISTINCT vendor_purchase_order_packing_trims_details.size_qty) as totalsize_qty')
                            ->where('vendor_purchase_order_packing_trims_details.vpo_code','=', $BOMList[0]->vpo_code)
                            ->groupBy('vendor_purchase_order_packing_trims_details.item_code')
                            ->get();

                            $nos=1;
                            @endphp
                            @foreach($PackingTrimsList as $rowDetailpacking)
                            @php


                            $SizeListFromBOM=DB::select("select size_array, remark from bom_packing_trims_details where sales_order_no='".$rowDetailpacking->sales_order_no."' and item_code='".$rowDetailpacking->item_code."' limit 0,1");
                            $size_ids = explode(',', $SizeListFromBOM[0]->size_array);
                            $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                            $sizes='';
                            foreach($SizeDetailList as $sz)
                            {
                            $sizes=$sizes.$sz->size_name.', ';
                            }


                            //$colorids = explode(',', $rowDetailpacking->color_id);

                            $sizeids = explode(',', $rowDetailpacking->size_array);



                            //DB::enableQueryLog();
                            $ColorListpacking= App\Models\BOMPackingTrimsDetailModel::select('color_id')->
                            where('item_code', $rowDetailpacking->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();
                            $colorids = explode(',', $ColorListpacking[0]->color_id);
                            //$query = DB::getQueryLog();
                            // $query = end($query);
                            // dd($query);
                            //$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');

                            $ColorListpacking= App\Models\VendorPurchaseOrderDetailModel::
                            join('color_master','vendor_purchase_order_detail.color_id','=','color_master.color_id')
                            ->where('vendor_purchase_order_detail.sales_order_no', $BOMList[0]->sales_order_no)
                            ->where('vendor_purchase_order_detail.vpo_code', $BOMList[0]->vpo_code)
                            ->whereIn('vendor_purchase_order_detail.color_id', $colorids)->where('delflag','=', '0')
                            ->distinct('color_master.color_id')->get('color_name');

                            $colorspk='';
                            foreach($ColorListpacking as $colorpk)
                            {
                            $colorspk=$colorspk.$colorpk->color_name.', ';
                            }




                            $SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');

                            @endphp


                            <tr>
                                <td class="text-end">{{ $nos }}</td>
                                <td class="text-center">{{ $rowDetailpacking->item_code }}</td>
                                <td class="text-start">{{ $rowDetailpacking->item_name }}</td>
                                <td class="text-start">{{rtrim($colorspk, ', ');}} </td>
                                <td class="text-start">{{rtrim($sizes, ', ');}} </td>
                                <td class="text-start">{{ $rowDetailpacking->class_name }}</td>
                               

                                <td  class="text-end">{{ $rowDetailpacking->consumption  }}</td>
                                <td  class="text-center">{{ $rowDetailpacking->unit_name  }}</td>
                                <td  class="text-end">{{ $rowDetailpacking->wastage  }}</td>

                                <td  class="text-end">{{ $rowDetailpacking->totalbom_qty  }}</td>
                                <td class="text-end">{{ isset($SizeListFromBOM[0]->remark) ? $SizeListFromBOM[0]->remark : "" }}</td>
                            </tr>
                            @php

                            $nos=$nos+1;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    @endif



                    <div class="row">
                        <div class="col-md-16">
                            <h4 class="mt-2" style="font-size:15px;">Comments : {{isset($BOMList[0]->narration) ? $BOMList[0]->narration : "" }}</h4>

                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Prepared By:</th>
                            <th>Checked By:</th>
                            <th>Approved By:</th>
                            <th>Authorized By:</th>
                        </tr>

                    </table>
                    

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

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