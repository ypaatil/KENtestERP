<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ken Enterprises Pvt. Ltd.</title>
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
            background-color: #CDE2F7;
            text-align: center;
        }

        tfoot tr {
            background-color: #CDE2F7;
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
                border: 2px solid #000 !important;
                /* font-weight: bold; */
                padding: 10px !important;


            }

            .table-bordered tr th:first-child,
            .table-bordered tr td:first-child {
                border-left: none !important;
            }

            .table-bordered tr th:last-child,
            .table-bordered tr td:last-child {
                border-right: none !important;
            }

            thead tr,
            tfoot tr {
                background-color: #CDE2F7 !important;
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
            @page {
                size: A4;
                margin: 20px;
                border: 1px solid #000;
                /* some browsers support this */
            }

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

          

            /* .terms-conditions {
                 margin: 0 auto;
            background: white;
            padding: 30px;
                border: 2px solid #000 !important;
               
               
                max-width: 100%;
                height: 100vh;
               
            } */
            /* 
                 .second-Page
                 {
                      background: #fff !important;
                       border: 2px solid #000 !important;
                       max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;

                 } */
            .terms-conditions {
                clear: both !important;
                /* force it below any floated elements */
                margin-top: 20px;
            }

            .terms-conditions table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
                font-size: 12pt !important;
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
                            <img src="./ken_logo.png" alt="Ken Enterprise Pvt. Ltd." height="130" width="230">
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


                    <h4 class="mb-0 fw-bold text-center"> PURCHASE ORDER</h4>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-3 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">Purchase Order No </div>
                                    <div class="colon">:</div>
                                    <div class="value"> PO/25-26/T7702</div>

                                </div>
                                <div class="info-row">
                                    <div class="label">Purchase Order Date </div>
                                    <div class="colon">:</div>
                                    <div class="value"> 23-09-2025</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Delivery Date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> 23-09-2025</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Sales Order No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> KDPL-2618,KDPL-2619,KDPL-2621,KDPL-2620,KDPL-2623,KDPL-2622</div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-6 p-2">
                            <p><b>Vendor Name And Address : </b></p>
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

                        </div>
                    </div>
                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b>Billing Address :</b></p>
                                <p><b>DHAGA GHAR THREADS PVT LTD</b></p>
                                <p>TRUCTURE NO-4,SUN MILL COMPOUND,,
                                    BETWEEN ASHOKA CAR CARE AND HANUMAN
                                    TEMPLE,, LOWER PAREL ( W),MUMBAI-400013</p>
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

                            </div>


                        </div>
                        <div class="col-md-6 p-2">
                            <div class="">
                                <p><b>Delivery Address :</b></p>
                                <p><b>KEN GLOBAL DESIGNS - UNIT - 1 (KONDIGRE)</b></p>
                                <p>TRUCTURE NO-4,SUN MILL COMPOUND,,
                                    BETWEEN ASHOKA CAR CARE AND HANUMAN
                                    TEMPLE, LOWER PAREL ( W),MUMBAI-400013</p>
                               
                            </div>

                        </div>

                    </div>

                    <div class="p-2">
                        <p class="">We are pleased to place an order to you, with the reference to above. You are requested to read the terms & specifcation
                            carefuly and Supply Material Accordingly.</p>
                    </div>

                    <table class="table g-0  table-bordered table-sm text-center" style="margin-top: -15px;">
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
                            <tr>
                                <td class="text-start">Textiles</td>
                                <td class="text-start">ITM001</td>
                                <td class="text-start">Kids Shirt<br><b>Description:</b> Cotton Shirt<br><b>Color:</b> Blue<br><b>Width:</b> 40cm</td>
                                <td class="text-end">6205</td>
                                <td class="text-end">100 </td>
                                <td class="text-start">PCS</td>
                                <td class="text-end">250.00</td>
                                <td class="text-end">25,000.00</td>

                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-end">100</td>
                                <td></td>
                                <td></td>
                                <td class="text-end">25,000.00</td>

                            </tr>
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
                            <tr>
                                <td class="text-end">6205</td>
                                <td class="text-end">25,000.00</td>
                                <td class="text-end">6%</td>
                                <td class="text-end">1,500.00</td>
                                <td class="text-end">6%</td>
                                <td class="text-end">1,500.00</td>
                                <td class="text-end">0%</td>
                                <td class="text-end">0.00</td>
                                <td class="text-end">3,000.00</td>
                                <!-- <td class="text-start">TOTAL TAX</td> -->
                                <!-- <td class="text-end">348.48</td> -->
                            </tr>
                            <tr>
                                <td class="text-start">TOTAL</td>
                                <td class="text-end">2904</td>
                                <td></td>
                                <td class="text-end">174.24</td>
                                <td></td>
                                <td class="text-end">174.24</td>
                                <td></td>
                                <td class="text-end">0</td>
                                <td class="text-end">348.48</td>
                                <!-- <td class="text-start">ROUNDOFF</td> -->
                                <!-- <td class="text-end">-0.48</td> -->
                            </tr>

                            <tr>
                                <td colspan="9"><b>AMOUNT IN RUPEES: THREE THOUSAND TWO HUNDRED AND FIFTY TWO RUPEES ONLY</b></td>
                            </tr>
                        </tbody>
                    </table>



                    <table class="table table-bordered border  border-dark  ms-auto  summary-table" style="width: 500px;">
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
                    </table>



                    <div class="terms-conditions ">
                        <h6 class="p-1"> AGREED TERMS & CONDITIONS</h6>
                        <h6 class="p-1">1. We have right to reject any goods which is rejected by our QC and vendor will be sole responsible for rejection.<br>
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
                            required on the mail after dispatch.</h6>



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

         <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
     <footer>
          <h6 class="text-center">This is a computer-generated invoice.</h6>
  </footer>


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
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
</body>

</html>