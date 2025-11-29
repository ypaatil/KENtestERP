<!DOCTYPE html>
<html lang="en">

<head>
    @php setlocale(LC_MONETARY, 'en_IN'); @endphp
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase Order | Ken Global Designs Pvt. Ltd.</title>  

    <!-- Web Fonts -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            padding: 30px;
        }

        .invoice-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            /* box-shadow: 0 0 10px rgba(7, 6, 6, 0.1); */
            border-radius: 5px;
        }

        .table-bordered th {
            align-items: center;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #020000ff;
            padding: 8px;

        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            color: #000 !important;
            font-weight: normal;
            /* optional, remove bold if unwanted */
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
                /* font-family: "Times New Roman", Times, serif; */
                background-color: #fff;
                color: #000000ff;
                padding: 30px;
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

            @page {
                size: A4;
                margin-top: 12mm;
                margin-right: 1mm;
                border: 1px solid #000;
                /* some browsers support this */
                height: 100%;
                margin-bottom: 10mm;
            }

            .btn,
            .d-print-none {
                display: none !important;
            }

            /* General text */
            body,
            html,
            #printInvoice {
                /* font-size: 16pt !important; */
                line-height: 1.5 !important;
                color: #000 !important;
            }

            .info-row .label,
            .info-row .colon {
                /* font-size: 16pt !important; */
                font-weight: bold !important;
            }

            /* Table styles */
            .table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;

            }

            .table td {
                color: #000 !important;
                font-weight: normal;
                /* optional, remove bold if unwanted */
            }

            .table-bordered th {
                vertical-align: middle;
                text-align: center;
            }

            .table-bordered td,
            .table-bordered th {

                /* font-weight: bold; */
                padding: 10px !important;
                font-size: 11pt;

            }

            .table-bordered tr th:first-child,
            .table-bordered tr td:first-child {
                border-left: none !important;
            }

            .table-bordered tr th:last-child,
            .table-bordered tr td:last-child {
                border-right: none !important;
            }

            table.first th:nth-child(1),
            table.first td:nth-child(1) {
                width: 200px !important;
            }

            table.first th:nth-child(2),
            table.first td:nth-child(2) {
                width: 80px !important;
            }

            table.first th:nth-child(3),
            table.first td:nth-child(3) {
                width: 400px !important;
            }

            table.first th:nth-child(4),
            table.first td:nth-child(4) {
                width: auto !important;
                /* Garment Color column */
            }

            table.first th:nth-child(5),
            table.first td:nth-child(5) {
                width: auto !important;
            }

            table.first th:nth-child(6),
            table.first td:nth-child(6) {
                width: auto !important;
            }

            table.first th:nth-child(7),
            table.first td:nth-child(7) {
                width: auto !important;
            }

            table.first th:nth-child(8),
            table.first td:nth-child(8) {
                width: auto !important;
            }

            thead tr,
            tfoot tr {

                -webkit-print-color-adjust: exact;
                /* font-weight: bold !important; */

                color: #000;
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

        .table-bordered {
            border-left: none !important;
            /* remove left outer border */
            border-right: none !important;
            /* remove right outer border */
            border-collapse: collapse;
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

        .outer-border {
            border: 2px solid #000;
            padding: 0px !important;
            margin: 10px auto;
            max-width: 95%;
            background: #fff;
            height: 100%;
        }


        @media print {


            .outer-border {
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            td.text-start {
                text-align: left !important;
            }

            td.text-end {
                text-align: right !important;
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
        }

        @media print {
            .invoice-container {
                max-width: 80%;
                /* reduce to 80% of page width */
                margin: 0 auto;
                /* center it on page */
            }

            .invoice-container p {
                font-size: 14pt;
                /* make text a little smaller for print */
            }




            /* Reduce padding inside table cells */
            .table-bordered td,
            .table-bordered th {
                padding: 4px 6px !important;
                /* was 10px */
                line-height: 1.2 !important;
                /* align-items: center; */
                /* tighter rows */
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

            .terms-conditions {
                clear: both !important;
                /* force it below any floated elements */
                margin-top: 20px;
            }

            .terms-conditions table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;

            }

            .terms-conditions table td {
                border: 2px solid #000 !important;
                padding: 6px !important;
                vertical-align: middle !important;
                text-align: left !important;
            }

            .terms-conditions table td.text-end {
                text-align: right !important;
            }

        }

        .terms-conditions h6 {
            line-height: 1.5 !important;
            /* Adjust to 1.6 if you want more spacing */
            font-size: 12pt !important;
        }

        .table-bordered.summary-table tr th:first-child,
        .table-bordered.summary-table tr td:first-child,
        .table-bordered.summary-table tr th:last-child,
        .table-bordered.summary-table tr td:last-child {
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
        }
    </style>
</head>

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
                        <div class="col-md-4 verticalLine p-2 text-center">
                            <img src="http://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130" width="230">
                        </div>

                        <div class="col-md-7  " style=" margin-top:10px;">
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
                    <hr style="height:3px; color:#000; border:none; margin:0 -15px;">


                    <h4 class="mb-0 fw-bold mt-n1 text-center"> PURCHASE ORDER</h4>


                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-3 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">Purchase Order No </div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $poMaster[0]->pur_code }}</div>

                                </div>
                                <div class="info-row">
                                    <div class="label">Purchase Order Date </div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ date('d-m-Y',strtotime($poMaster[0]->pur_date)) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Delivery Date</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ \Carbon\Carbon::parse ($poMaster[0]->delivery_date)->format('d-m-Y') }} </div>
                                </div>
                                @if($poMaster[0]->approveFlag==0)
                                <!--<b>Purchase Order :</b>  Pending for Appoval -->
                                @elseif($poMaster[0]->approveFlag==1)
                                <!--<b> Purchase Order :</b>  Approved -->
                                @elseif($poMaster[0]->approveFlag==2)
                                <!--<b> Purchase Order :</b>  Disappoved -->
                                @endif
                                <div class="info-row">
                                    <div class="label">Sales Order No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> @php $sono=''; foreach($SalesOrderNo as $rows){ $sono=$sono.$rows->sales_order_no.','; } echo rtrim($sono,','); @endphp</div>
                                </div>

                            </div>

                        </div>
                        @php
                        //DB::enableQueryLog();
                        $BillToData = DB::select("
                        SELECT ld.*
                        FROM purchase_order po
                        INNER JOIN ledger_details ld
                        ON ld.sr_no = po.bill_to
                        WHERE po.pur_code = ?
                        ", [$poMaster[0]->pur_code]);

                        //dd(DB::getQueryLog());
                        $ShipToData = DB::select("SELECT ledger_details.* FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.ship_to WHERE purchase_order.pur_code = '".$poMaster[0]->pur_code."'");

                        @endphp
                        <div class="col-md-6 p-2">
                            <p><b>Vendor Name And Address : </b></p>
                            <p><b>{{ $BillToData[0]->trade_name ?? "-" }}</b> <br> {{ $BillToData[0]->addr1 ?? "-" }}</p>

                            <div class="info-row">
                                <div class="label">GST NO</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $BillToData[0]->gst_no ?? "-" }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">PAN NO</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $BillToData[0]->pan_no ?? "-" }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">STATE</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $poMaster[0]->state_name ?? "-" }}</div>
                            </div>

                        </div>
                    </div>
                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b>Billing Address :</b></p>
                                <p><b>{{ $BillToData[0]->trade_name ?? "-" }}</b> <br> {{ $BillToData[0]->addr1 ?? "-" }}</p>

                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $BillToData[0]->gst_no ?? "-" }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">STATE</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $poMaster[0]->state_name ?? "-" }} </div>
                                </div>

                            </div>


                        </div>
                        <div class="col-md-6 p-2">
                            <div class="">
                                <p><b>Delivery Address :</b></p>
                                <p><b>{{ $ShipToData[0]->trade_name ?? "-" }}</b> <br>{{ $ShipToData[0]->addr1 ?? "-" }}</p>
                                <p> </p>

                            </div>

                        </div>

                    </div>

                    <div class="p-2">
                        <p class="">We are pleased to place an order to you, with the reference to above. You are requested to read the terms & specification carefully and Supply Material Accordingly.</p>
                    </div>

                    <table class="table g-0  table-bordered first table-sm text-center" style="margin-top: -15px;">
                        <thead style="align-items: center;">
                            <tr>
                                <th>Classification</th>
                                <th>Item Code</th>
                                <th>Product Description</th>
                                <th>HSN/SAC Code</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Rate</th>
                                <th> Amount</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $detailpurchase =DB::select("SELECT purchaseorder_detail.item_code,
                            u1.unit_name as unit1, u2.unit_name as unit2, u3.unit_name as unit3, u4.unit_name as unit4, conQty, unitIdM, priUnitd, SecConQty,secUnitId, sum(poQty) as poQty, poUnitId, rateM, totalQty,
                            item_master.item_name,item_master.item_image_path, item_master.item_description, item_master.color_name,class_name,
                            item_master.dimension, purchaseorder_detail.hsn_code, purchaseorder_detail.unit_id, sum(item_qty) as item_qty,
                            purchaseorder_detail.item_rate, purchaseorder_detail.disc_per, sum(disc_amount) as disc_amount,purchaseorder_detail.
                            pur_cgst, sum(camt) as camt, purchaseorder_detail.pur_sgst, sum(samt) as samt, purchaseorder_detail.pur_igst,
                            sum(iamt) as iamt, sum(amount) as amount, freight_hsn, sum(freight_amt) as freight_amt,
                            sum(total_amount) as total_amount FROM purchaseorder_detail
                            left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
                            left outer join classification_master on classification_master.class_id=item_master.class_id
                            LEFT outer join unit_master u1 on u1.unit_id=purchaseorder_detail.unit_id
                            LEFT outer join unit_master u2 on u2.unit_id=purchaseorder_detail.priUnitd
                            LEFT outer join unit_master u3 on u3.unit_id=purchaseorder_detail.secUnitId
                            LEFT outer join unit_master u4 on u4.unit_id=purchaseorder_detail.poUnitId
                            WHERE pur_code='".$poMaster[0]->pur_code."'
                            group by purchaseorder_detail.item_code");
                            $no=1;
                            $freight_hsn=0;
                            $freight_amt=0;
                            $pur_cgst=0;
                            $camt=0;
                            $pur_sgst=0;
                            $samt=0;
                            $pur_igst=0;
                            $iamt=0;
                            $Net_Amount=0;
                            $totalGst_Amount = 0;
                            @endphp
                            @foreach($detailpurchase as $rowDetail)
                            @if($rowDetail->item_code != '')
                            <tr>
                                <td class="text-start">{{ $rowDetail->class_name }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>
                                <td class="text-start">

                                    {{ $rowDetail->item_name }}
                                    <br><b>Description:</b> {{ $rowDetail->item_description }}
                                    <br><b>Color:</b> {{ $rowDetail->color_name }}
                                    <br><b>Width:</b> {{ $rowDetail->dimension }}</br>
                                    @if($rowDetail->poQty!=0) <b>1:</b> {{$rowDetail->conQty}} {{$rowDetail->unit1}}/{{$rowDetail->unit2}} @endif


                                </td>
                                <td class="text-end">{{ $rowDetail->hsn_code }}
                                    </br>
                                    @if($rowDetail->poQty!=0) {{$rowDetail->SecConQty}} {{$rowDetail->unit3}} @endif</td>
                                <td class="text-end">{{ money_format('%!i',round($rowDetail->item_qty,2))}} <br>
                                    @if($rowDetail->poQty!=0) {{round($rowDetail->poQty,2)}} {{$rowDetail->unit4}} @endif </td>
                                <td class="text-start">{{ $rowDetail->unit1 }}</td>
                                <td class="text-end">{{ number_format($rowDetail->item_rate,4) }} <br>
                                    @if($rowDetail->poQty!=0) {{money_format('%!i',$rowDetail->rateM)}}/Box @endif</td>
                                <td class="text-end">{{ money_format('%!i', round($rowDetail->amount ?? $rowDetail->iamt,2)) }}</td>

                            </tr>
                            @endif
                            @php
                            $freight_hsn=$rowDetail->freight_hsn;
                            $freight_amt=$rowDetail->freight_amt;
                            $pur_cgst=$rowDetail->pur_cgst + $pur_cgst;
                            $camt=$rowDetail->camt + $camt;
                            $pur_sgst=$rowDetail->pur_sgst + $pur_sgst;
                            $samt=$rowDetail->samt + $samt;
                            $pur_igst=$rowDetail->pur_igst + $pur_igst;
                            $iamt=$rowDetail->iamt + $iamt;
                            $Net_Amount=$Net_Amount+round($rowDetail->total_amount,2);
                            $totalGst_Amount=$totalGst_Amount+round($rowDetail->amount ?? $rowDetail->iamt,2);
                            $no=$no+1; @endphp
                            @endforeach
                            <tr>

                                <td colspan="4" class="text-end"><b>Total:</b></td>
                                <td class="text-end">{{money_format('%!i',round($poMaster[0]->total_qty,2))}}</td>
                                <td></td>
                                <td></td>
                                <td class="text-end">{{money_format('%!i',round($totalGst_Amount,2))}}</td>

                            </tr>
                            @php
                            $number = round($poMaster[0]->Net_amount);
                            $no = $number;
                            $point = round($number - $no, 2) * 100;
                            $hundred = null;
                            $digits_1 = strlen($no);
                            $i = 0;
                            $str = array();
                            $words = array('0' => '', '1' => 'one', '2' => 'two',
                            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                            '7' => 'seven', '8' => 'eight', '9' => 'nine',
                            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                            '13' => 'thirteen', '14' => 'fourteen',
                            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
                            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                            '60' => 'sixty', '70' => 'seventy',
                            '80' => 'eighty', '90' => 'ninety');
                            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
                            while ($i < $digits_1) {
                                $divider=($i==2) ? 10 : 100;
                                $number=floor($no % $divider);
                                $no=floor($no / $divider);
                                $i +=($divider==10) ? 1 : 2;
                                if ($number) {
                                $plural=(($counter=count($str)) && $number> 9) ? 's' : null;
                                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                                    :
                                    $words[floor($number / 10) * 10]
                                    . " " . $words[$number % 10] . " "
                                    . $digits[$counter] . $plural . " " . $hundred;
                                    } else $str[]=null;
                                    }
                                    $str=array_reverse($str);
                                    $result=implode('', $str);
                                    $points=($point) ? "." . $words[$point / 10] . " " .
                                    $words[$point=$point % 10] : '' ;
                                    @endphp
                                    </tbody>
                    </table>

                    <!-- Tax Breakdown -->
                    <table class="table table-bordered table-sm text-center">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">HSN </th>
                                <th rowspan="2">Taxable Value</th>
                                <th colspan="2">CGST</th>
                                <th colspan="2">SGST</th>
                                <th colspan="2">IGST</th>
                                <th rowspan="2">Total Tax Amount</th>
                                <!-- <th rowspan="2">Taxable Amount</th>
                                <th rowspan="2" class="text-end">2,904.00</th> -->
                            </tr>
                            <tr>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $hsnpurchase =DB::select("SELECT item_master.hsn_code , purchaseorder_detail.pur_cgst ,purchaseorder_detail.pur_sgst ,
                            purchaseorder_detail.pur_igst , sum(purchase_order.Gst_amount) as Gst_amount, sum(purchaseorder_detail.`camt`) as camt,
                            sum(purchaseorder_detail.`samt`) as samt,sum(purchaseorder_detail.`iamt`) as iamt,
                            sum(purchaseorder_detail.`amount`) as gross_amount, sum(purchaseorder_detail.`total_amount`) as total_amount
                            FROM `purchaseorder_detail`
                            inner join purchase_order on purchase_order.`pur_code`=purchaseorder_detail.`pur_code`
                            inner join item_master on item_master.item_code=purchaseorder_detail.item_code
                            where purchaseorder_detail.`pur_code`='".$poMaster[0]->pur_code."' group by hsn_code ,
                            purchaseorder_detail.pur_cgst ,purchaseorder_detail.pur_sgst , purchaseorder_detail.pur_igst

                            ");
                            $a=1;$b=0; $c=-1;
                            @endphp
                            @foreach($hsnpurchase as $rowtax)
                            <tr>
                                <td class="text-end">{{ $rowtax->hsn_code}}</td>
                                <td class="text-end">{{ number_format($rowtax->gross_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($rowtax->pur_cgst, 2) }} %</td>
                                <td class="text-end">{{ number_format($rowtax->camt, 2) }}</td>
                                <td class="text-end">{{ number_format($rowtax->pur_sgst,2)}} %</td>
                                <td class="text-end">{{ number_format($rowtax->samt, 2) }}</td>
                                <td class="text-end">{{ number_format($rowtax->pur_igst,2)}} %</td>
                                <td class="text-end">{{ number_format($rowtax->iamt, 2) }}</td>
                                <td class="text-end">{{ number_format($rowtax->camt + $rowtax->samt + $rowtax->iamt, 2) }}</td>
                                <!-- <td class="text-start">TOTAL TAX</td> -->
                                <!-- <td class="text-end">348.48</td> -->
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-end">TOTAL:</td>
                                <td class="text-end">{{ number_format($poMaster[0]->Gross_amount, 2) }}</td>
                                <td></td>
                                <td class="text-end">{{ number_format($camt, 2) }}</td>
                                <td></td>
                                <td class="text-end">{{ number_format($samt, 2) }}</td>
                                <td></td>
                                <td class="text-end">{{ number_format($iamt, 2) }}</td>
                                <td class="text-end">{{ number_format($poMaster[0]->Gst_amount, 2) }}</td>
                                <!-- <td class="text-start">ROUNDOFF</td> -->
                                <!-- <td class="text-end">-0.48</td> -->
                            </tr>

                            <tr>
                                <td colspan="9"><b>AMOUNT IN RUPEES: {{ strtoupper($result . "Rupees  Only")}}</b></td>
                            </tr>
                        </tbody>
                    </table>


                    @php
                    $calculatedTotal = $poMaster[0]->Gross_amount + $poMaster[0]->Gst_amount;
                    $roundoff = $poMaster[0]->Net_amount - $calculatedTotal;
                    @endphp

                    <table class="table table-bordered border  border-dark  ms-auto  summary-table" style="width: 500px;">
                        <tr>
                            <th class="text-start">Total (Before Tax)</th>
                            <td class="text-end">{{ number_format(round($totalGst_Amount, 2), 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">SGST</th>
                            <td class="text-end">{{ number_format($samt, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">CGST</th>
                            <td class="text-end">{{ number_format($camt, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">IGST</th>
                            <td class="text-end">{{ number_format($iamt, 2) }}</td>
                        </tr>

                        <tr>

                            <th class="text-start">Total (After Tax)</th>
                            <td class="text-end">{{ number_format($poMaster[0]->Gross_amount + $poMaster[0]->Gst_amount, 2) }}</td>

                        </tr>

                        <tr>
                            <th class="text-start">Round</th>
                            <td class="text-end">{{ number_format(round($roundoff, 2), 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start">Grand total</th>
                            <td class="text-end"> <b> {{ number_format($poMaster[0]->Net_amount, 2) }}</b> </td>
                        </tr>
                    </table>



                    <div class="terms-conditions ">
                        <h6 class="p-1"> AGREED TERMS & CONDITIONS</h6>
                        <!-- <h6 class="p-1">1. We have right to reject any goods which is rejected by our QC and vendor will be sole responsible for rejection.<br>
                            2. We reserves the right to reject the goods if we find them defective even at the later stage and to recover the
                            cost of material and losses if any from the
                            sellers.<br>
                            3. Payment shall be made for the actual quantity received by us and our records shall be final and conclusive on
                            this point.<br>
                            4. We will be entitled to deduct Discount as mentioned in the order.<br>
                            5. Any dispute arise with respect to this PO shall be subjected to "Ichalkaranji Jurisdiction".<br>
                            6. You will allow our customers & quality person to do visit to your factory to verify the quality of material<br>
                            &nbsp; &nbsp; &nbsp;supplied by you so also to see the system of quality
                            control followed by you.<br>
                            7. Excess of PO qty is +/-2 % acceptable, Payment will be released only as per physical received qty. (PO qty
                            whichever is lower).<br>
                            8. Delivery Address: - as above.<br>
                            9. Goods will be inspected at your factory as per our quality requirements Packing list, Invoice & L.R. copy
                            required on the mail after dispatch.</h6> -->
                        <h6 class="p-1"> @php echo htmlspecialchars_decode($poMaster[0]->terms_and_conditions); @endphp</h6>



                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td rowspan="2" class="fw-bold">COMPANY SEAL:</td>
                                    <td class="text-end fw-bold">
                                        FOR KEN GLOBAL DESIGNS PRIVATE LIMITED<br><br><br><br>
                                        <div class="fw-bold">Authorized Signature</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <p>SUBJECT TO ICHALKARANJI JURISDICTION</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="second-Page"> Remark:</div>

                    </div>
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