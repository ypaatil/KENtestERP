<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Work Order Merged Print | Ken Global Designs Pvt. Ltd.</title>
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


        .table-bordered.summary-table tr th:first-child,
        .table-bordered.summary-table tr td:first-child,
        .table-bordered.summary-table tr th:last-child,
        .table-bordered.summary-table tr td:last-child {
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
        }

           table.second {
        table-layout: fixed !important;
        width: 100% !important;
        border-collapse: collapse !important;
        }

    
            table.second th,
            table.second td {
                font-size: 11pt !important;
                padding: 2px 4px !important;
                word-break: break-all !important;
            }
            table.second th:first-child,
            table.second td:first-child {
                width: 56px !important;      
            }

            table.second th:nth-child(2),
            table.second td:nth-child(2) {
                width: 250px !important;     
            }

        
            table.second th:nth-child(n+3):nth-child(-n+22),
            table.second td:nth-child(n+3):nth-child(-n+22) {
                width: auto !important;   
            }

            table.second th:last-child,
            table.second td:last-child {
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


        #printInvoice {
            width: 100% !important;
            max-width: 100% !important;
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
                            <p>
                        </div>

                        <div class="col-md-2">
                            <!-- <h6 class="fw-bold">Date: 15-09-2023</h6> -->
                        </div>
                    </div>

                    <div class="double-color-hr">
                        <div class="blue-part"></div>
                        <div class="green-part"></div>
                    </div>

                    <h3 class=" fw-bold  text-center">Gate Pass/ Delivery Note -  {{$VendorPurchaseOrderList[0]->process_name}}</h3>
                     @php
                    
                    $VendorPurchaseOrderMaster = DB::table('vendor_purchase_order_master')->join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId')
                                 ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
                                 ->whereIn('vendor_purchase_order_master.vpo_code', $vpoCodes)
                                 ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','ledger_master.address',
                                 'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id']);
                                 
                    $ledgerData = DB::table('ledger_master')->join('usermaster', 'usermaster.vendorId', '=', 'ledger_master.ac_code')->where('usermaster.userId', '=',  Session::get('userId'))->get(['ledger_master.ac_short_name as ac_short_name','ledger_master.address as to_address']);
                     
                    $from_name = isset($ledgerData[0]->ac_short_name) ? $ledgerData[0]->ac_short_name : "-";                         
                    $from_address = isset($ledgerData[0]->to_address) ? $ledgerData[0]->to_address : "-"; 
                    
                   @endphp

                    <!-- Sales Info -->
                    <div class="row g-0  border-top border-bottom ">

                        <div class="col-md-6 p-2 border-end">
                            <div class="">

                                <div class="info-row">
                                    <div class="label">Delivery No.</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$vpoCodes1}}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Delivery Date</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{ date("d-m-Y", strtotime($VendorPurchaseOrderMaster[0]->vpo_date)) }}</div>
                                </div>
                                 <div class="info-row">
                                    <div class="label">Process Type</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$VendorPurchaseOrderList[0]->process_name}}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">{{$VendorPurchaseOrderList[0]->process_name}} PO No.</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{$VendorPurchaseOrderList[0]->vpo_code}} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$VendorPurchaseOrderList[0]->fg_name}} </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Style No.</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$VendorPurchaseOrderList[0]->style_no}}</div>
                                </div>

                                <!-- <div class="info-row">
                                    <div class="label">From</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$from_name}}</div>
                                </div>

                                <div class="info-row">
                                    <div class="label">Address</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{$from_address}}</div>
                                </div> -->
                            </div>

                        </div>
                         @php
                        $company=getCompanyInfo();
                        @endphp
                        <div class="col-md-6 p-2">
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
                        <!-- <div class="col-md-6 p-2">
                            <div class="info-row">
                                    <div class="label">To</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$VendorPurchaseOrderMaster[0]->Ac_name}}</div>
                                </div>
                            <div class="info-row">
                                <div class="label"> Address </div>
                                <div class="colon">:</div>
                                <div class="value"> {{$VendorPurchaseOrderMaster[0]->address}} </div>
                            </div>
                           
                        </div> -->
                    </div>

                     <!-- Second Row -->
                    <div class="row g-0  border-bottom ">


                        <div class="col-md-6 p-2 border-end">
                            <div class="">
                                <p><b> Delivery Challan For :</b></p>
                                <p><b> {{$VendorPurchaseOrderMaster[0]->Ac_name}}</b> </br>{{$VendorPurchaseOrderMaster[0]->address}}  </p>
                                <div class="info-row">
                                    <div class="label">GST NO</div>
                                    <div class="colon">:</div>
                                    <div class="value">{{$VendorPurchaseOrderList[0]->gst_no}}  </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">PAN NO</div>
                                    <div class="colon">:</div>
                                    <div class="value"> {{$VendorPurchaseOrderList[0]->pan_no}} </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">

                        </div>
                    </div>

                       
                    <!-- Assortment Table -->
                    <h4 class="text-center fw-bold">Assortment Details</h4>
                    <hr/>
                     @php
               
                     foreach($vpoCodes as $key=>$details)
                     {
                //   DB::enableQueryLog();
                   
                     $VendorPurchaseOrderMaster = DB::table('vendor_purchase_order_master')->join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId')
                     ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
                     ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id')
                     ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id')
                     ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id') 
                     ->where('vendor_purchase_order_master.vpo_code', $vpoCodes[$key])
                     ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','vendor_purchase_order_master.sales_order_no',
                     'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address','mainstyle_name','substyle_name','fg_name']);
                   
                    
                    $BuyerPurchaseOrderMasterList =  DB::table('buyer_purchse_order_master')->where('tr_code',$VendorPurchaseOrderMaster[0]->sales_order_no)->get();
                               
                    
                    $SizeDetailList = DB::table('size_detail')->where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
                    $sizes='';
                    $no=1;
                    foreach ($SizeDetailList as $sz) 
                    {
                        $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                        $no=$no+1;
                    }
                    $sizes=rtrim($sizes,',');
                    //  DB::enableQueryLog();  
                    $OutwardForPackingList = DB::select("SELECT item_master.item_name,	vendor_purchase_order_size_detail.color_id, color_master.color_name, ".$sizes.", 
                    sum(size_qty_total) as size_qty_total  from	vendor_purchase_order_size_detail 
                    inner join item_master on item_master.item_code=vendor_purchase_order_size_detail.item_code 
                    inner join color_master on color_master.color_id=vendor_purchase_order_size_detail.color_id 
                    where vpo_code='".$VendorPurchaseOrderMaster[0]->vpo_code."' group by vendor_purchase_order_size_detail.color_id");
                         //     $query = DB::getQueryLog();
                    //   $query = end($query);
                    //   dd($query);
                    @endphp
                    <table class="table table-bordered table-sm second">
                        <thead>
                           <div class="text-start fw-bold mb-1" style="margin-left:10px;">
                                D. No. : {{$vpoCodes[$key]}},  
                                Sales Order No : {{$VendorPurchaseOrderMaster[0]->sales_order_no}},  
                                Style : {{$VendorPurchaseOrderMaster[0]->mainstyle_name}}
                           </div>
                        <tr class="text-center">
                           <th>Sr.No.</th>
                           <th>Garment Color</th>
                           @foreach ($SizeDetailList as $sz) 
                           <th>{{$sz->size_name}}</th>
                           @endforeach
                           <th>Total Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php   
                        $no=1; $totalAmt=0; $totalQty=0;@endphp
                        @foreach ($OutwardForPackingList as $row) 
                            <tr>
                           <td class="text-end">{{$no}}</td>
                           <td class="text-start">{{$row->color_name}}</td>
                           @if(isset($row->s1))  
                           <td class="text-end">{{number_format($row->s1)}}</td>
                           @endif
                           @if(isset($row->s2)) 
                           <td class="text-end">{{number_format($row->s2)}}</td>
                           @endif
                           @if(isset($row->s3)) 
                           <td class="text-end">{{number_format($row->s3)}}</td>
                           @endif
                           @if(isset($row->s4)) 
                           <td class="text-end">{{number_format($row->s4)}}</td>
                           @endif
                           @if(isset($row->s5)) 
                           <td class="text-end">{{number_format($row->s5)}}</td>
                           @endif
                           @if(isset($row->s6)) 
                           <td class="text-end">{{number_format($row->s6)}}</td>
                           @endif
                           @if(isset($row->s7)) 
                           <td class="text-end">{{number_format($row->s7)}}</td>
                           @endif
                           @if(isset($row->s8)) 
                           <td class="text-end">{{number_format($row->s8)}}</td>
                           @endif
                           @if(isset($row->s9)) 
                           <td class="text-end">{{number_format($row->s9)}}</td>
                           @endif
                           @if(isset($row->s10)) 
                           <td class="text-end">{{number_format($row->s10)}}</td>
                           @endif
                           @if(isset($row->s11)) 
                           <td class="text-end">{{number_format($row->s11)}}</td>
                           @endif
                           @if(isset($row->s12)) 
                           <td class="text-end">{{number_format($row->s12)}}</td>
                           @endif
                           @if(isset($row->s13)) 
                           <td class="text-end">{{number_format($row->s13)}}</td>
                           @endif
                           @if(isset($row->s14)) 
                           <td class="text-end">{{number_format($row->s14)}}</td>
                           @endif
                           @if(isset($row->s15)) 
                           <td class="text-end">{{number_format($row->s15)}}</td>
                           @endif
                           @if(isset($row->s16)) 
                           <td class="text-end">{{number_format($row->s16)}}</td>
                           @endif
                           @if(isset($row->s17)) 
                           <td class="text-end">{{number_format($row->s17)}}</td>
                           @endif
                           @if(isset($row->s18)) 
                           <td class="text-end">{{number_format($row->s18)}}</td>
                           @endif
                           @if(isset($row->s19)) 
                           <td class="text-end">{{number_format($row->s19)}}</td>
                           @endif
                           @if(isset($row->s20))  
                           <td class="text-end">{{number_format($row->s20)}}</td>
                           @endif
                           <td class="text-end">{{number_format($row->size_qty_total)}}</td>
                        </tr>
                           @php $no=$no+1; 
                        $totalQty = $totalQty + $row->size_qty_total;
                        @endphp
                        @endforeach
                    </tbody>
                    </table>

                     <div class="col-md-12">
                      <span style="margin-left:9px;"><b>Remark : </b>{{$VendorPurchaseOrderMaster[0]->narration}}</span>
                  </div> 
                    <hr/>  
                    @php
                        }
                    @endphp
                  <div class="col-md-12 text-end">
                      <h4><b>Grand Total : <span id="grand_total"></span></b><h4> 
                  </div><br/>
                  <div class="col-md-12 text-center" style="border:1px solid black;">
                        <h6><b>NOT FOR SALE, FOR JOB WORK ONLY</b></h6>
                  </div>

                   

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

    <p class="text-center d-print-none"><a href="/OutwardForPacking">&laquo; Back to List</a></p>
   
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> 
      
    $(document).ready(function () 
    {
        // Function to format numbers in Indian currency format
        function formatIndianCurrency(value) {
            return value.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
    
        $(".data-table").each(function () {
            var $table = $(this);
            var columnSums = [];
    
            // Iterate over each row in the tbody
            $table.find("tbody tr").each(function () {
                $(this).find("td").each(function (index) {
                    // Skip only the first two columns
                    if (index > 1) {
                        var value = parseFloat($(this).text().replace(/,/g, '')) || 0; // Remove commas before parsing
                        columnSums[index] = (columnSums[index] || 0) + value;
                    }
                });
            });
    
            // Create the sum row
            var $sumRow = $("<tr class='sum-row'></tr>");
            $table.find("thead tr th").each(function (index) {
                if (index === 1) {
                    // Set "Total" in the second column
                    $sumRow.append("<td class='text-right'><b>Total</b></td>");
                } else if (index > 1) {
                    // Add the sum values for relevant columns, formatted as Indian currency
                    var formattedSum = formatIndianCurrency(columnSums[index] || 0);
    
                    // Check if it's the last column
                    var isLastColumn = index === $table.find("thead tr th").length - 1;
                    var cellClass = isLastColumn ? "subtotal" : "text-center";
    
                    $sumRow.append("<td class=' text-center " + cellClass + "'><b>" + formattedSum + "</b></td>");
                } else {
                    // Empty cells for other columns
                    $sumRow.append("<td></td>");
                }
            });
    
            // Append the sum row to the table
            $table.append($sumRow);
        });
    
        // Calculate grand total from all subtotals
        var grand_total = 0;
        $(".subtotal").each(function () {
            var value = parseFloat($(this).text().replace(/,/g, '')) || 0; // Remove commas before parsing
            grand_total += value;
        });
    
        // Format the grand total and display it
        var formattedGrandTotal = formatIndianCurrency(grand_total);
        $("#grand_total").html(formattedGrandTotal);
    });

    

   </script>

</body>

</html>