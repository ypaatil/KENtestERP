<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stitching | Ken Global Designs Pvt. Ltd. </title>
    <!-- Web Fonts -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
        type='text/css'>
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
                            <img src="http://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130"
                                width="230">
                        </div>
                        @php
                        $data= getCompanyAddress();
                        @endphp
                        <div class="col-md-7" style="margin-top:10px;">
                            <h4 class="mb-2 fw-bold">{{$data['heading']}}</h4>
                            <p>{!!$data['address']!!}</p>
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
                                <div class="label">Stitching No.</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->sti_code }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Stitching Date</div>
                                <div class="colon">: </div>
                                <div class="value">
                                    {{ \Carbon\Carbon::parse($StitchingInhouseMaster[0]->sti_date)->format('d-m-Y') }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Sales Order No.</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $StitchingInhouseMaster[0]->sales_order_no }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Work Order No.</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $StitchingInhouseMaster[0]->vw_code }} </div>
                            </div>

                        </div>
                        <div class="col-md-6 p-2 ">
                            <div class="info-row">
                                <div class="label">Main style Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->mainstyle_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Sub Style Name</div>
                                <div class="colon">: </div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->substyle_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style No.</div>
                                <div class="colon">:</div>
                                <div class="value"> {{ $StitchingInhouseMaster[0]->style_no }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Style Name</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->fg_name }} </div>
                            </div>

                        </div>



                    </div>
                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">
                        <div class="col-md-6 p-2 border-end">
                            <div class="info-row">
                                <div class="label">Vendor</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->ac_short_name }} </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Address</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->address }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">GST NO</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->gst_no }}</div>
                            </div>
                            <div class="info-row">
                                <div class="label">PAN NO</div>
                                <div class="colon">:</div>
                                <div class="value">{{ $StitchingInhouseMaster[0]->pan_no }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">

                        </div>
                    </div>


                    <h4 class="text-center mt-6 fw-bold">Assortment Details</h4>

                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Garment Color</th>
                                @foreach ($SizeDetailList as $sz)
                                <th>{{$sz->size_name}}</th>
                                @endforeach
                                <th>Total Qty</th>
                            </tr>
                        </thead>

                        @php
                        $no=1;
                        $totalQty=0;

                        // size totals
                        $s1Total=$s2Total=$s3Total=$s4Total=$s5Total=0;
                        $s6Total=$s7Total=$s8Total=$s9Total=$s10Total=0;
                        $s11Total=$s12Total=$s13Total=$s14Total=$s15Total=0;
                        $s16Total=$s17Total=$s18Total=$s19Total=$s20Total=0;
                        @endphp

                        <tbody>
                            @php $no=1; $totalAmt=0; $totalQty=0; @endphp
                            @foreach ($StitchingGRNList as $row)
                            <tr>
                                <td class="text-end">{{number_format($no)}}</td>
                                <td class="text-start">{{$row->color_name}}</td>

                                @if(isset($row->s1)) <td class="text-end">{{number_format($row->s1)}}</td> @php $s1Total += $row->s1;
                                @endphp @endif
                                @if(isset($row->s2)) <td class="text-end">{{number_format($row->s2)}}</td> @php $s2Total += $row->s2;
                                @endphp @endif
                                @if(isset($row->s3)) <td class="text-end">{{number_format($row->s3)}}</td> @php $s3Total += $row->s3;
                                @endphp @endif
                                @if(isset($row->s4)) <td class="text-end">{{number_format($row->s4)}}</td> @php $s4Total += $row->s4;
                                @endphp @endif
                                @if(isset($row->s5)) <td class="text-end">{{number_format($row->s5)}}</td> @php $s5Total += $row->s5;
                                @endphp @endif
                                @if(isset($row->s6)) <td class="text-end">{{number_format($row->s6)}}</td> @php $s6Total += $row->s6;
                                @endphp @endif
                                @if(isset($row->s7)) <td class="text-end">{{number_format($row->s7)}}</td> @php $s7Total += $row->s7;
                                @endphp @endif
                                @if(isset($row->s8)) <td class="text-end">{{number_format($row->s8)}}</td> @php $s8Total += $row->s8;
                                @endphp @endif
                                @if(isset($row->s9)) <td class="text-end">{{number_format($row->s9)}}</td> @php $s9Total += $row->s9;
                                @endphp @endif
                                @if(isset($row->s10)) <td class="text-end">{{number_format($row->s10)}}</td> @php $s10Total +=
                                $row->s10; @endphp @endif
                                @if(isset($row->s11)) <td class="text-end">{{number_format($row->s11)}}</td> @php $s11Total +=
                                $row->s11; @endphp @endif
                                @if(isset($row->s12)) <td class="text-end">{{number_format($row->s12)}}</td> @php $s12Total +=
                                $row->s12; @endphp @endif
                                @if(isset($row->s13)) <td class="text-end">{{number_format($row->s13)}}</td> @php $s13Total +=
                                $row->s13; @endphp @endif
                                @if(isset($row->s14)) <td class="text-end">{{number_format($row->s14)}}</td> @php $s14Total +=
                                $row->s14; @endphp @endif
                                @if(isset($row->s15)) <td class="text-end">{{number_format($row->s15)}}</td> @php $s15Total +=
                                $row->s15; @endphp @endif
                                @if(isset($row->s16)) <td class="text-end">{{number_format($row->s16)}}</td> @php $s16Total +=
                                $row->s16; @endphp @endif
                                @if(isset($row->s17)) <td class="text-end">{{number_format($row->s17)}}</td> @php $s17Total +=
                                $row->s17; @endphp @endif
                                @if(isset($row->s18)) <td class="text-end">{{number_format($row->s18)}}</td> @php $s18Total +=
                                $row->s18; @endphp @endif
                                @if(isset($row->s19)) <td class="text-end">{{number_format($row->s19)}}</td> @php $s19Total +=
                                $row->s19; @endphp @endif
                                @if(isset($row->s20)) <td class="text-end">{{number_format($row->s20)}}</td> @php $s20Total +=
                                $row->s20; @endphp @endif

                                <td class="text-end">{{$row->size_qty_total}}</td>
                            </tr>

                            @php
                            $no++;
                            $totalQty += $row->size_qty_total;
                            @endphp

                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>

                                <td colspan="2" class="text-end fw-bold">Total :</td>

                                @php $i = 1; @endphp
                                @foreach ($SizeDetailList as $sz)
                                @php
                                $var = "s".$i."Total";
                                @endphp
                                <td class="text-end fw-bold">{{ $$var }}</td>
                                @php $i++; @endphp
                                @endforeach

                                <td class="text-end fw-bold">{{ $totalQty }}</td>
                            </tr>
                        </tfoot>


                    </table>


                    <table class="table" style="margin-top: 100px;">
                        <tr>
                            <th>Prepared By:</th>
                            <th>Checked By:</th>
                            <th>Approved By:</th>
                            <th>Authorized By:</th>
                        </tr>

                    </table>
                    <!-- Footer -->
                    <footer>
                        <div class="row">
                            <div class="col-md-6"><b>Order Remark:</b> </div>
                        </div>
                    </footer>

                </div>
            </main>
        </div>
    </div>

   <p class="text-center d-print-none"><a href="/StitchingInhouse">&laquo; Back to List</a></p>


    <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
    



</body>

</html>