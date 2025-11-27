<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DC Print Sale Transaction | Ken Global Designs Pvt. Ltd. </title>
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
                        @php
                        $data= getCompanyAddress();
                        @endphp
                        <div class="col-md-7" style="margin-top:10px;">
                            <h4 class="mb-2 fw-bold">{{$data['heading']}}</h4>
                            <p>{!!$data['address']!!}
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

                    <h4 class=" fw-bold mt-6 text-center">Stitching</h4>

                    <!-- Sales Info -->
                    <div class="row  border-top border-bottom  g-0">
                        <div class="col-md-6 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Challan No</div>
                                <div class="colon">:</div>
                                <div class="value"> {{$BuyerDetail->sale_code}}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">Date</div>
                                <div class="colon">: </div>
                                <div class="value"> {{\Carbon\Carbon::parse($BuyerDetail->sale_date)->format('d-m-Y')}} </div>
                            </div>
                        </div>
                        @php
                        $company=getCompanyInfo();
                        @endphp
                        <div class="col-md-6 p-2 ">

                            <p><b>Company Name: </b></p>
                            <p> <b>{{ $company['name'] }}</b><br>{{ $company['address'] }}</p>

                            <div class="info-row">
                                <div class="label">PAN NO</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $company['pan'] }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">GST NO</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $company['gst'] }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">STATE</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $company['state'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row   border-bottom  g-0">
                        <div class="col-md-6 p-2 border-end">
                            <p><b>TO: </b></p>
                            <b>{{$BuyerDetail->sent_through}}</b><br>

                            <p><b>Address: </b></p>
                            <b>{{$BuyerDetail->sent_address}}</b><br>
                        </div>
                    </div>



                    <h4 class="text-center mt-6 fw-bold">We would like to place and confirm you following below order</h4>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Main Style Category</th>
                                <th>Style Description</th>
                                <th>Sales Order No.</th>
                                <th>HSN/SAC Code</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Rate Per Item</th>
                                <th>Total Material Value</th>
                                <th>Taxable Value</th>
                                <th>C & SGST/IGST</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $srno = 1;
                            $totalQty = 0;
                            $total_material_value = 0;
                            $total_value = 0;
                            $totalAmount = 0;
                            $result = "";
                            @endphp
                            @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                            @php

                            if($buyerData->tax_type_id == 1)
                            {
                            $taxAmt = $buyerData->samt;
                            }
                            else
                            {
                            $taxAmt = $buyerData->iamt;
                            }
                            @endphp
                            <tr>
                                <td class="text-end">{{$srno++}}</td>
                                <td class="text-start">{{$buyerData->mainstyle_name}}</td>
                                <td class="text-start">{{$buyerData->style_description}}</td>
                                <td class="text-center">{{$buyerData->sales_order_no}}</td>
                                <td class="text-end">{{$buyerData->hsn_code}}</td>
                                <td class="text-center">{{$buyerData->unit_name}}</td>
                                <td class="text-end">{{money_format("%!.0n",round($buyerData->order_qty,2))}}</td>
                                <td class="text-end">{{money_format("%!.0n",round($buyerData->order_rate,2))}}</td>
                                <td class="text-end">{{money_format("%!.0n",round(($buyerData->order_qty * $buyerData->order_rate),2))}}</td>
                                <td class="text-end">{{money_format("%!.0n",round(($buyerData->order_qty * $buyerData->order_rate),2))}}</td>
                                <td class="text-end">{{money_format("%!.0n",round($taxAmt,2))}}</td>
                                <td class="text-end">{{money_format("%!.0n",round(round(($buyerData->order_qty * $buyerData->order_rate) + $taxAmt),2))}}</td>

                            </tr>
                            @php
                            $totalQty = $totalQty + $buyerData->order_qty;
                            $total_material_value = $total_material_value + ($buyerData->order_qty * $buyerData->order_rate);
                            $total_value = $total_value + ($buyerData->order_qty * $buyerData->order_rate) + $taxAmt;

                            $number = round($total_value);
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
                            while ($i < $digits_1)
                                {
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
                                    @endforeach
                                    <tr>
                                  
                                    <th colspan="6" class="text-end"> Total:</th>
                                    <th class="text-end">{{indian_number_format_for_value($totalQty,2)}}</th>
                                    <th> </th>
                                    <th class="text-end">{{ indian_number_format_for_value($total_material_value,2)}}</th>
                                    <th class="text-end">{{ indian_number_format_for_value($total_material_value,2)}}</th>
                                    <th> </th>
                                    <th class="text-end">{{ indian_number_format_for_value($total_value,2)}}</th>
                                    </tr>

                        </tbody>

                    </table>

                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th rowspan="2">HSN NO</th>
                                <th rowspan="2">TAXABLE VALUE</th>
                                <th colspan="2">CENTRAL TAX</th>
                                <th colspan="2">STATE TAX</th>
                                <th colspan="2">INTEGRATED TAX</th>
                                <th rowspan="2">TOTAL TAX AMOUNT</th>
                                <th rowspan="2" class="text-">GROSS AMOUNT </th>
                                <th rowspan="2" >{{indian_number_format_for_value($total_material_value,2)}}</th>
                            </tr>
                            <tr>
                                <th>RATE</th>
                                <th>AMT</th>
                                <th>RATE</th>
                                <th>AMT</th>
                                <th>RATE</th>
                                <th>AMT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalTaxAmt = 0;
                            $totalCTaxAmt = 0;
                            $totalSTaxAmt = 0;
                            $totalITaxAmt = 0;
                            $totalATaxAmt = 0;
                            @endphp
                            @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                            <tr>
                                <td class="text-end">{{$buyerData->hsn_code ? $buyerData->hsn_code : "-"}}</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->order_qty * $buyerData->order_rate,2)}}</td>
                                <td class="text-end">{{$buyerData->sale_cgst}}</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->camt,2)}}</td>
                                <td class="text-end">{{$buyerData->sale_sgst}}</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->samt,2)}}</td>
                                <td class="text-end">{{$buyerData->sale_igst}}</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->iamt,2)}}</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                                <td class="text-start">TOTAL TAX :</td>
                                <td class="text-end">{{indian_number_format_for_value($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                            </tr>
                            @php
                            $totalAmount = $totalAmount + ($buyerData->camt + $buyerData->samt + $buyerData->iamt);
                            $totalTaxAmt = $totalTaxAmt + ($buyerData->order_qty * $buyerData->order_rate);
                            $totalCTaxAmt = $totalCTaxAmt + $buyerData->camt;
                            $totalSTaxAmt = $totalSTaxAmt + $buyerData->samt;
                            $totalITaxAmt = $totalITaxAmt + $buyerData->iamt;
                            $totalATaxAmt = $totalATaxAmt + ($buyerData->camt + $buyerData->samt + $buyerData->iamt);
                            @endphp
                            @endforeach
                            <tr>
                                <th class="text-end">TOTAL :</th>
                                <th class="text-end">{{indian_number_format_for_value($totalTaxAmt,2)}}</th>
                                <th></th>
                                <th class="text-end">{{indian_number_format_for_value($totalCTaxAmt,2)}}</th>
                                <th></th>
                                <th class="text-end">{{indian_number_format_for_value($totalSTaxAmt,2)}}</th>
                                <th> </th>
                                <th class="text-end">{{indian_number_format_for_value($totalITaxAmt,2)}}</th>
                                <th class="text-end">{{indian_number_format_for_value($totalATaxAmt,2)}}</th>
                                <td colspan="1" class="text-start">ROUNDOFF : </td>
                                <td class="text-end">0</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="4"></td>
                                <td colspan="1" class="text-start">AMOUNT :</td>
                                <td class="text-end">{{indian_number_format_for_value($totalAmount + $total_material_value,2)}}</td>
                            </tr>
                            <tr>
                                <td colspan="11" style="text-transform: uppercase;" class="text-center"><span><b>Amount In Rupees: </span>{{ $result . "Rupees  Only"}}</b> </td>
                            </tr>
                        </tbody>
                    </table>

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

    <p class="text-center d-print-none"><a href="/SaleTransaction">&laquo; Back to List</a></p>

    <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
    <script>

    </script>



</body>

</html>