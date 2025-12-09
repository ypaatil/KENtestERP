<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ken Enterprises Pvt. Ltd.</title>
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

        @page {
            size: A4 portrait;
            margin: 5mm;
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
            margin: 20px;
            border: 1px solid #000;

        }

        .outer-border {
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }

        .invoice-container img {
            max-width: 2000px;

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

            padding: 6px !important;

        }

        @media print {
            .invoice-container h4 {
                font-size: 18pt;

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
    }
    </style>
</head>

<body>

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
                            <img src="http://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130"
                                width="230">
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

                    <h3 class=" fw-bold text-center"> Trims Gate Entry</h3>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-3 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">PO No.</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>

                                </div>
                                <div class="info-row">
                                    <div class="label"> Date </div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">DC No.</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">DC date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Invoice No.</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">DC date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> Invoice Date</div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-6 p-2">
                            <p><b>Goods Received By: </b></p>
                            <p><b>Ken Global Designs Pvt Ltd</b></p>
                            <p> Gat No.- 298/299, A/P Kondigre Kolhapur Mahrashtra - 416101</p>
                            <div class="info-row">
                                <div class="label">PAN NO</div>
                                <div class="colon">:</div>
                                <div class="value"> 7591Q1ZD</div>
                            </div>
                            <div class="info-row">
                                <div class="label">GST NO</div>
                                <div class="colon">:</div>
                                <div class="value"> 27ABCCS7591Q1ZD</div>
                            </div>
                            <div class="info-row">
                                <div class="label">State</div>
                                <div class="colon">:</div>
                                <div class="value"> MAHARASTRA</div>
                            </div>
                            <div class="info-row">
                                <div class="label">LR No.</div>
                                <div class="colon">:</div>
                                <div class="value"> </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Transport Name</div>
                                <div class="colon">:</div>
                                <div class="value"> </div>
                            </div>
                            <div class="info-row">
                                <div class="label">Vehicle No.</div>
                                <div class="colon">:</div>
                                <div class="value"> </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b>Goods Sent By :</b></p>
                                <p><b></b></p>
                                <p></p>
                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">State</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <div class="">
                                <p><b>Shipped To :</b></p>
                                <p><b></b></p>
                                <p></p>
                            </div>
                        </div>
                    </div>

                    <!-- Assortment Table -->
                    <h4 class="text-center mt-2 fw-bold">Goods Receipt Note</h4>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Item Description</th>

                                <th>UOM</th>
                                <th>Challan Qty</th>
                                <th>Received Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>

                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end"></td>
                                <td class="text-end"></td>
                                <td class="text-start"></td>
                                <td class="text-end"></td>
                                <td class="text-start"></td>
                                <td class="text-end"></td>
                                <td class="text-end"></td>
                                <td class="text-end"></td>
                                <td class="text-end"></td>
                                <td class="text-end"></td>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="text-start"></th>
                                <th class="text-end"></th>
                                <th></th>
                                <th class="text-start"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>

                            </tr>
                            <tr>
                                <th colspan="10" class="text-center">
                                    AMOUNT (INR):
                                </th>
                            </tr>
                        </tfoot>
                    </table>



                    <!-- <table class="table table-bordered border  border-dark  ms-auto  summary-table" style="width: 500px;">
                        <tr>
                            <th class="text-start">Total (Before Tax)</th>
                            <td class="text-end">68,493.60</td>
                        </tr>
                        <tr>
                            <th class="text-start">SGST</th>
                            <td class="text-end">1,712.34</td>
                        </tr>
                        <tr>
                            <th class="text-start">CGST</th>
                            <td class="text-end">1,712.34</td>
                        </tr>
                        <tr>
                            <th class="text-start">IGST</th>
                            <td class="text-end">3,424.68</td>
                        </tr>
                        <tr>
                            <th class="text-start">Total (After Tax)</th>
                            <td class="text-end">68,493.60</td>
                        </tr>
                        <tr>
                            <th class="text-start">Round</th>
                            <td class="text-end">-0.24</td>
                        </tr>
                        <tr>
                            <th class="text-start">Grand total</th>
                            <td class="text-end">3,424.68</td>
                        </tr>
                    </table> -->

                    <br>

                    <table class="table">
                        <tr>
                            <th>Prepared By:</th>
                            <th>Checked By:</th>
                            <th>Approved By:</th>
                            <th>Authorized By:</th>
                        </tr>

                    </table>
                    <!-- 
                    <div class="row col-md-12">
                        <div class="col-md-3">Prepared By:</div>
                        <div class="col-md-3">Checked By: </div>
                        <div class="col-md-3">Approved By:</div>
                        <div class="col-md-3">Authorized By:</div>
                        <br>

                    </div>
                    -->



                </div>

            </main>
        </div>
    </div>

    <p class="text-center d-print-none"><a href="#">&laquo; Back to List</a></p>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</body>

</html>