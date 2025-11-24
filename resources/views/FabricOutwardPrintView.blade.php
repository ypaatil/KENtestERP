
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fabric Outward (GST Print) | Ken Global Designs Pvt. Ltd.</title>
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
                    font-size: 10pt;
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

            table.second th:nth-child(1),
            table.second td:nth-child(1) {
                width: 53px !important;
            }

            table.second th:nth-child(2),
            table.second td:nth-child(2) {
                width: 70px !important;
            }

            table.second th:nth-child(3),
            table.second td:nth-child(3) {
                width: 200px !important;
            }

            table.second th:nth-child(4),
            table.second td:nth-child(4) {
                width: 70px !important;

            }

            table.second th:nth-child(5),
            table.second td:nth-child(5) {
                width: auto !important;
            }

            table.second th:nth-child(6),
            table.second td:nth-child(6) {
                width: 80px !important;
            }

            table.second th:nth-child(7),
            table.second td:nth-child(7) {
                width: 60px !important;
            }

            table.second th:nth-child(8),
            table.second td:nth-child(8) {
                width: 70px !important;
            }

            table.second th:nth-child(9),
            table.second td:nth-child(9) {
                width: auto !important;
            }

            table.second th:nth-child(10),
            table.second td:nth-child(10) {
                width: 80px !important;
            }

            table.second th:nth-child(11),
            table.second td:nth-child(11) {
                width: 80px !important;
            }

            table.second th:nth-child(12),
            table.second td:nth-child(12) {
                width: 80px !important;
            }

            table.second th:nth-child(13),
            table.second td:nth-child(13) {
                width: 90px !important;
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

                    <h3 class=" fw-bold text-center"> Gate Pass/ Delivery Challan-Fabric</h3>

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-3 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">Delivery Challan No</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $FabricOutwardMaster[0]->fout_code }}</div>

                                </div>
                                <div class="info-row">
                                    <div class="label">Delivery Challan Date</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ \Carbon\Carbon::parse( $FabricOutwardMaster[0]->fout_date)->format('d-m-Y')  }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Sales Order no</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ isset($FabricOutwardMaster[0]->sales_order_no) ? $FabricOutwardMaster[0]->sales_order_no : '' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Job/Cutting PO No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ isset($FabricOutwardMaster[0]->vpo_code) ? $FabricOutwardMaster[0]->vpo_code : '' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $FabricOutwardMaster[0]->fg_name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style No</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{ $FabricOutwardMaster[0]->style_no }}</div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-6 p-2">
                            <p><b>Company Name: </b></p>
                            <p><b>Ken Global Designs Pvt Ltd</b> <br> Gat No.- 298/299, A/P Kondigre Kolhapur Maharashtra - 416101</p>

                            <div class="info-row">
                                <div class="label">PAN NO</div>
                                <div class="colon">:</div>
                                <div class="value"> ABCCS7591Q</div>
                            </div>
                            <div class="info-row">
                                <div class="label">GST NO</div>
                                <div class="colon">:</div>
                                <div class="value"> 27ABCCS7591Q1ZD</div>
                            </div>
                            <div class="info-row">
                                <div class="label">STATE</div>
                                <div class="colon">:</div>
                                <div class="value"> MAHARASHTRA</div>
                            </div>

                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row g-0  border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b>Delivery Challan For :</b></p>
                                <p><b>{{ $FabricOutwardMaster[0]->Ac_name }} </b><br> {{ $FabricOutwardMaster[0]->address }}</p>

                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ $FabricOutwardMaster[0]->gst_no }} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">STATE</div>
                                    <div class="colon">:</div>
                                    <div class="value"> </div>
                                </div>

                            </div>


                        </div>
                        <!-- <div class="col-md-6 p-2">
                            <div class="">
                                <p><b>Shipping To: </b></p>
                                <p><b></b></p>

                            </div>

                        </div> -->
                    </div>

                    <!-- Assortment Table -->
                    <h4 class="text-center fw-bold">Item Details</h4>
                    <table class="table table-bordered table-sm second">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>HSN Code</th>
                                <th>Description / Quality</th>

                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Rate</th>
                                <th>Amount (Before Tax)</th>
                                <th>CGST</th>
                                <th>SGST</th>
                                <th>IGST</th>
                                <th>Amount (After Tax)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $FabricOutwardDetailstables = App\Models\FabricOutwardDetailModel::select(
                            'item_master.color_name',
                            'unit_master.unit_name',
                            'item_master.item_name',
                            'item_master.hsn_code',
                            'fabric_outward_details.width',
                            'fabric_outward_details.item_code',
                            'item_master.item_description',
                            'inward_details.item_rate',
                            'item_master.dimension',
                            'part_master.part_name',
                            DB::raw('sum(fabric_outward_details.meter) as meter')
                            )
                            ->join('item_master','item_master.item_code','=','fabric_outward_details.item_code')
                            ->join('part_master','part_master.part_id','=','fabric_outward_details.part_id')
                            ->join('unit_master','unit_master.unit_id','=','item_master.unit_id')
                            ->join('inward_details','inward_details.track_code','=','fabric_outward_details.track_code')
                            ->where('fabric_outward_details.fout_code','=', $FabricOutwardMaster[0]->fout_code)
                            ->groupBy('fabric_outward_details.item_code')
                            ->get();

                            $no = 1;
                            $amt = 0;
                            $cgst_total = 0;
                            $sgst_total = 0;
                            $igst_total = 0;
                            $after_tax_total = 0;
                            @endphp

                            @foreach($FabricOutwardDetailstables as $rowDetail)
                            @php
                            $beforeTax = $rowDetail->item_rate * $rowDetail->meter;

                            // tax calculation based on state
                            if($FabricOutwardMaster[0]->state_id == 27){
                            $cgst = $beforeTax * 2.5 / 100;
                            $sgst = $beforeTax * 2.5 / 100;
                            $igst = 0;
                            } else {
                            $cgst = 0;
                            $sgst = 0;
                            $igst = $beforeTax * 5 / 100;
                            }

                            $afterTax = $beforeTax + $cgst + $sgst + $igst;

                            $amt += $beforeTax;
                            $cgst_total += $cgst;
                            $sgst_total += $sgst;
                            $igst_total += $igst;
                            $after_tax_total += $afterTax;
                            @endphp



                            <tr>
                                <td class="text-end">{{ $no++ }}</td>
                                <td class="text-center">{{ $rowDetail->item_code }}</td>
                                <td class="text-start">{{ $rowDetail->item_name }}</td>
                                <td class="text-center">{{ $rowDetail->hsn_code }}</td>
                                <td class="text-start">{{ $rowDetail->item_description }}</td>

                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->meter , 2  ) }}</td>
                                <td class="text-center">{{ $rowDetail->unit_name }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($rowDetail->item_rate , 2 ) }}</td>
                                <td class="text-end">{{ indian_number_format_for_value($beforeTax , 2  ) }}</td>
                                @php
                                if ($beforeTax > 0) {
                                $cgst_percent = round(($cgst / $beforeTax) * 100, 2);
                                $sgst_percent = round(($sgst / $beforeTax) * 100, 2);
                                $igst_percent = round(($igst / $beforeTax) * 100, 2);
                                } else {
                                $cgst_percent = $sgst_percent = $igst_percent = 0;
                                }
                                @endphp

                                <td class="text-end">{{ indian_number_format_for_value($cgst , 2  ) }} <br>({{ $cgst_percent }}%)</td>
                                <td class="text-end">{{ indian_number_format_for_value($sgst , 2  ) }} <br>({{ $sgst_percent }}%)</td>
                                <td class="text-end">{{ indian_number_format_for_value($igst , 2  ) }} <br> ({{ $igst_percent }}%)</td>
                                <td class="text-end">{{ indian_number_format_for_value($afterTax , 2  ) }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><b>Total Meter:</b></td>
                                <td class="text-end fw-bold">{{ indian_number_format_for_value($FabricOutwardMaster[0]->total_meter, 2 ) }}</td>
                                <td colspan="2" class="text-end fw-bold">Total Amount <br>(Before Tax):</td>
                                <td class="text-end fw-bold">{{ indian_number_format_for_value($amt, 2 ) }}</td>
                                <td class="text-end fw-bold" colspan="3">Total Amount <br>(After Tax):</td>

                                <td class="text-end fw-bold">{{ indian_number_format_for_value($after_tax_total, 2) }}</td>
                                
                            </tr>
                            <tr>
                                <td colspan="13" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td>
                            </tr>
                        </tfoot>
                    </table>

                    @php
                    $number = round($amt);
                    $no = $number;
                    $point = round($number - $no, 2) * 100;
                    $hundred = null;
                    $digits_1 = strlen($no);
                    $i = 0;
                    $str = [];
                    $words = [
                    '0' => '', '1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four', '5' => 'five',
                    '6' => 'six', '7' => 'seven', '8' => 'eight', '9' => 'nine', '10' => 'ten',
                    '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen', '14' => 'fourteen',
                    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen', '18' => 'eighteen',
                    '19' =>'nineteen', '20' => 'twenty', '30' => 'thirty', '40' => 'forty',
                    '50' => 'fifty', '60' => 'sixty', '70' => 'seventy', '80' => 'eighty', '90' => 'ninety'
                    ];
                    $digits = ['', 'hundred', 'thousand', 'lakh', 'crore'];
                    while ($i < $digits_1) {
                        $divider=($i==2) ? 10 : 100;
                        $number=floor($no % $divider);
                        $no=floor($no / $divider);
                        $i +=($divider==10) ? 1 : 2;
                        if ($number) {
                        $plural=(($counter=count($str)) && $number> 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str[] = ($number < 21)
                            ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                            : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
                            } else $str[]=null;
                            }
                            $str=array_reverse($str);
                            $result=implode('', $str);
                            $points=($point) ? "." . $words[$point / 10] . " " . $words[$point=$point % 10] : '' ;

                            // --- TAX LOGIC ---
                           
                            // --- TAX LOGIC (NO ROUNDING, SAME AS FIRST TABLE) ---
                            if ($FabricOutwardMaster[0]->state_id == 27) {
                            // Intra-state
                            $CGSTTotal = $amt * 2.5 / 100;
                            $SGSTTotal = $amt * 2.5 / 100;
                            $IGSTTotal = 0;
                            $tamt = $amt + $CGSTTotal + $SGSTTotal;
                            } else {
                            // Inter-state
                            $CGSTTotal = 0;
                            $SGSTTotal = 0;
                            $IGSTTotal = $amt * 5 / 100;
                            $tamt = $amt + $IGSTTotal;
                            }

                            // --- ROUND-OFF & GRAND TOTAL ---
                            $roundedTotal = round($tamt); // Grand total
                            $roundOff = $roundedTotal - $tamt; // Exact round-off (no round())
                            @endphp
                            <table class="table table-bordered border border-dark ms-auto summary-table" style="width: 500px;">
                                <tr>
                                    <th class="text-start">Amount (Before Tax)</th>
                                    <td class="text-end">{{ indian_number_format_for_value($amt , 2  ) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">SGST</th>
                                    <td class="text-end">{{ indian_number_format_for_value($SGSTTotal , 2 ) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">CGST</th>
                                    <td class="text-end">{{ indian_number_format_for_value($CGSTTotal , 2 ) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">IGST</th>
                                    <td class="text-end">{{ indian_number_format_for_value($IGSTTotal , 2 ) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Amount (After Tax)</th>
                                    <td class="text-end">{{ indian_number_format_for_value($tamt, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Round Off</th>
                                    <td class="text-end">{{ $roundOff >= 0 ? '+' : '' }}{{ indian_number_format_for_value($roundOff, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Grand Total</th>
                                    <td class="text-end fw-bold">{{ indian_number_format_for_value($roundedTotal, 2) }}</td>
                                </tr>
                            </table>

                            <table class="table" style="margin-top: 100px; ">
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