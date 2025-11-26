<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Outward For Packing</title>
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
            --label-width: 150px;
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


            .table-bordered td,
            .table-bordered th {
                border: 2px solid #000 !important;

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

            table.second th:nth-child(1),
            table.second td:nth-child(1) {
                width: 55px !important;
            }

            table.second th:nth-child(2),
            table.second td:nth-child(2) {
                width: 390px !important;
            }
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

                    <h3 class=" fw-bold  text-center"> Gate Pass/ Delivery Note</h3>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">Delivery No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->ofp_code }} </div>

                                </div>
                                <div class="info-row">
                                    <div class="label">Delivery Date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ date("d-m-Y", strtotime($OutwardForPackingMaster[0]->ofp_date)) }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Sales Order no</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $OutwardForPackingMaster[0]->sales_order_no }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Work Order No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->vw_code }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 p-2">

                            <div class="info-row">
                                <div class="label">Main Style Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $OutwardForPackingMaster[0]->mainstyle_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Sub Style Name</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $OutwardForPackingMaster[0]->substyle_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style No</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $OutwardForPackingMaster[0]->fg_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style Name</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $OutwardForPackingMaster[0]->style_no }}</div>
                            </div>


                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b>FROM Address :</b></p>
                                <!-- <p><b>Vendor:</b> <br>KEN GLOBAL DESIGNS - UNIT - 1 (KONDIGRE)</p>
                                <p><b>Address:</b> <br>GAT NO 298/299,A/P Kondigre, Kolhapur, Maharashtra, 416101 </p> -->
                                <div class="info-row">
                                    <div class="label">Vendor</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->ac_name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Address</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->address }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $OutwardForPackingMaster[0]->gst_no }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PAN No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->pan_no }}</div>
                                </div>

                            </div>


                        </div>
                        <div class="col-md-6 p-2"> 
                            <div class="">
                                <p><b>TO Address :</b></p>
                                <!-- <p><b>Vendor:</b> <br>Anita - Finish Goods Warehouse </p>
                                <p><b>Address:</b> <br>PARVTI INDUSTRIES, YADRAV </p> -->
                                <div class="info-row">
                                    <div class="label">Vendor</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $OutwardForPackingMaster[0]->sent_location }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Address</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $OutwardForPackingMaster[0]->sent_loc_inc }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $OutwardForPackingMaster[0]->sent_gst_no }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PAN No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $OutwardForPackingMaster[0]->sent_pan_no }} </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Assortment Table -->
                    <h4 class="text-start  fw-bold">Outward For Packing Details:</h4>
                    <table class="table table-bordered table-sm second">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th> Garment Color</th>
                                @foreach ($SizeDetailList as $sz)
                                <th>{{$sz->size_name}}</th>
                                @endforeach
                                <th> Total Qty </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; $totalAmt=0; $totalQty=0; $sizeTotals = [];
                            for ($i = 1; $i <= 20; $i++) {
                                $sizeTotals["s$i"]=0;
                                }

                                @endphp
                                @foreach ($OutwardForPackingList as $row)
                                <tr>
                                <td class="text-end">{{$no}}</td>
                                <td class="text-start">{{$row->color_name}}</td>

                                @for ($i = 1; $i <= count($SizeDetailList); $i++)
                                    @php
                                    $sizeValue=$row->{'s'.$i} ?? 0;
                                    $sizeTotals["s$i"] += $sizeValue;
                                    @endphp
                                    <td class="text-end">{{$sizeValue}}</td>
                                    @endfor

                                    <td class="text-end">{{$row->size_qty_total}}</td>
                                    </tr>

                                    @php
                                    $no++;
                                    $totalQty += $row->size_qty_total;
                                    @endphp
                                    @endforeach

                                    <!-- Row for per-size totals -->
                                    <tr>
                                        <td class="text-end fw-bold" colspan="2"> Total:</td>
                                        @for ($i = 1; $i <= count($SizeDetailList); $i++)
                                            <td class="text-end fw-bold">{{$sizeTotals["s$i"]}}</td>
                                            @endfor
                                            <td class="text-end fw-bold">{{$totalQty}}</td>
                                    </tr>
                        </tbody>

                    </table>

                    <table class="table border">
                        <thead>
                            <th colspan="10" class="text-center">NOT FOR SALE, FOR JOB WORK ONLY </th>
                        </thead>
                    </table>
                    <br>

                    <table class="table">
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