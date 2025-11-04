<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Enterprises Pvt. Ltd.</title>
      <meta name="author" content="">
      <!-- Web Fonts
         ======================= -->
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
      <!-- Stylesheet
         ======================= -->
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/bootstrap.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/all.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/style.css') }}"/>
      <style>
         .table-bordered td, .table-bordered th {
         border: 1px solid #0c0c0c;
         body{
         font-family: "Times New Roman", Times, serif;
         }
         td{
         text-align: right;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div style="margin:10px;">
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                     @php $FirmDetail =  DB::table('firm_master')->first(); @endphp
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold; text-center">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;"> </h6>
                  </div>
               </div>
               <h4 class="text-4"></h4>
               <div class=""></div>
               <style>  
                  .table{
                  display: table;
                  width:100%;
                  border-collapse:collapse;
                  }
                  .tr {
                  display: table-row;
                  padding: 2px;
                  }
                  .tr p {
                  margin: 0px !important; 
                  }
                  .td {
                  display: table-cell;
                  padding: 8px;
                  width: 410px;
                  border: #000000 solid 1px;
                  }
                  @page{
                  margin: 5px !important;
                  }
                  .merged{
                  width:25%;
                  height:25%;
                  padding: 8px;
                  display: table-cell;
                  background-image: url('http://ken.korbofx.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Order Vs Shipment Report</h4>
               <div >
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; " id="Sales">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Order Recd.Date</th>
                           <th>Buyer's PO No</th>
                           <th>Sales Order No</th>
                           <th>SAM</th>
                           <th>Buyer</th>
                           <th>Main Category</th>
                           <th>FOB Rate</th>
                           <th>Order Qty</th>
                           <th>Invoice Date</th>
                           <th>Invoice No/Narration</th>
                           <th>ERP Invoice No</th>
                           <th>Dispatch  Qty</th>
                           <th>Minutes</th>
                           <th>Invoice Rate</th>
                           <th>Taxable Amount</th>
                           <th>Freight  Amt</th>
                           <th>GST</th>
                           <th>Total Amount</th>
                           <th>Balance Qty</th>
                           <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $no=1; $BalQty=0;
                        
                        if($DFilter == 'd')
                        {
                            $filterDate = " AND buyer_purchse_order_master.order_received_date = date('Y-m-d')";
                            $filterDate1 = " AND sale_transaction_master.sale_date = date('Y-m-d')";
                        }
                        else if($DFilter == 'm')
                        {
                            $filterDate = ' AND MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE())';
                            $filterDate1 = ' AND MONTH(sale_transaction_master.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE())';
                      
                        }
                        else if($DFilter == 'y')
                        {
                            $filterDate = ' AND buyer_purchse_order_master.order_received_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                            and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
                        
                            $filterDate1 = ' AND sale_transaction_master.sale_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                            and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
                       
                        }
                        else
                        {
                            $filterDate = "";
                            $filterDate1 = "";
                        }
                       
                         //DB::enableQueryLog();  
                        $Buyer_Purchase_Order_List = DB::select("SELECT buyer_purchse_order_master.*,usermaster.username,
                            ledger_master.Ac_name,fg_master.fg_name,merchant_master.merchant_name,
                            job_status_master.job_status_name,main_style_master.mainstyle_name FROM buyer_purchse_order_master 
                            INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId
                            LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
                            LEFT JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
                            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                            LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
                            LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                            WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4".$filterDate);
                        //dd(DB::getQueryLog());  
                        //$Buyer_Purchase_Order_List = App\Models\BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
                        //->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                       // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
                       // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                       // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
                       //->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                       // ->where('buyer_purchse_order_master.delflag','=', '0')
                       // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name' ]);
                        
                        
                        $Balance=0;
                        foreach($Buyer_Purchase_Order_List as $order)
                        {
                        $BalQty=$order->total_qty;  
                        $OrderQty=0; $DispQty=0; $Amount=0; $TotalAmount=0; 
                        //  DB::enableQueryLog();
                        
                        //$SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select(
                         //   'buyer_purchse_order_master.*',  'fg_master.fg_name','merchant_master.merchant_name',
                         //   'job_status_master.job_status_name','main_style_master.mainstyle_name',
                        //    'lm1.Ac_name', 'sale_transaction_master.freight_charges',
                        //    'sale_transaction_master.sale_code','sale_transaction_detail.order_rate as sale_rate',  'sale_transaction_master.sale_date',
                         //   'sale_transaction_detail.Ac_code', 'sales_order_costing_master.sales_order_no', 'hsn_code',
                         //   DB::raw('sum(order_qty) as order_qty'), 'buyer_purchse_order_master.order_rate', 'disc_per', DB::raw('sum(disc_amount) as disc_amount'), 'sale_cgst',
                         //   DB::raw('sum(camt) as camt'), 'sale_sgst', DB::raw('sum(samt) as samt'), 'sale_igst',  DB::raw('sum(iamt) as iamt'),  DB::raw('sum(amount) as amount'),
                         //   DB::raw('sum(total_amount) as total_amount'),'sale_transaction_master.narration as Narr2','sales_order_costing_master.sam'
                         //   )
                         //   ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                         //   ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                         //   ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                         //   ->leftJoin('ledger_master as lm1','lm1.Ac_code', '=', 'sale_transaction_detail.Ac_code')
                        //    ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                         //   ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                         //   ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
                         //   ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                         //   ->where('sale_transaction_detail.sales_order_no','=', $order->tr_code)
                         //   ->groupBy('sale_transaction_master.sale_code')
                         //   ->get();
                         // DB::enableQueryLog();  
                           $SaleTransactionDetails = DB::select("SELECT buyer_purchse_order_master.*, fg_master.fg_name,merchant_master.merchant_name,
                            job_status_master.job_status_name,main_style_master.mainstyle_name, lm1.Ac_name, sale_transaction_master.freight_charges,
                            sale_transaction_master.sale_code,sale_transaction_detail.order_rate as sale_rate,sale_transaction_master.sale_date,
                            sale_transaction_detail.Ac_code, sales_order_costing_master.sales_order_no, hsn_code,
                            sum(order_qty) as order_qty, buyer_purchse_order_master.order_rate, disc_per, sum(disc_amount) as disc_amount, sale_cgst,
                            sum(camt) as camt,sale_sgst, sum(samt) as samt,sale_igst,sum(iamt) as iamt, sum(amount) as amount,
                            sum(total_amount) as total_amount,sale_transaction_master.narration as Narr2,sales_order_costing_master.sam FROM sale_transaction_detail 
                            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                            LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                            LEFT JOIN ledger_master as lm1 ON lm1.Ac_code = sale_transaction_detail.Ac_code
                            LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
                            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                            LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
                            LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                            WHERE sale_transaction_detail.sales_order_no = '".$order->tr_code."'".$filterDate1." GROUP BY sale_transaction_master.sale_code");
                         // dd(DB::getQueryLog());  
                        //$query = DB::getQueryLog();
                        // $query = end($query);
                        // dd($query);
                        $start=0;  
                        if(count($SaleTransactionDetails)>0)
                        {
                        @endphp
                        @foreach($SaleTransactionDetails as $sales)
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $sales->order_received_date }}</td>
                           <td>{{ $sales->po_code }}</td>
                           <td>{{ $sales->tr_code }}</td>
                           <td>{{ $sales->sam }}</td>
                           <td>{{ $sales->Ac_name }}</td>
                           <td>{{ $sales->mainstyle_name }}</td>
                           <td>@if($start==0) {{ $sales->order_rate }} @endif</td>
                           <td> @if($start==0) {{ $sales->total_qty }}  @endif</td>
                           <td>{{ $sales->sale_date  }}</td>
                           <td>{{ $sales->Narr2  }}</td>
                           <td>{{ $sales->sale_code }}</td>
                           <td>{{ $sales->order_qty }}</td>
                           <td>{{ $sales->order_qty *  $sales->sam }}</td>
                           <td>{{ $sales->sale_rate }}</td>
                           <td>{{ $sales->amount }}</td>
                           <td>{{ $sales->freight_charges }}</td>
                           <td>{{ round(($sales->camt + $sales->samt + $sales->iamt),2)  }}</td>
                           <td>{{ $sales->total_amount }}</td>
                           <td>{{ $BalQty=$BalQty - $sales->order_qty }}</td>
                           <td>{{$sales->job_status_name}}</td>
                        </tr>
                        @php
                        if($start==0){ $OrderQty=$OrderQty + $sales->total_qty  ; }
                        $DispQty=$DispQty + $sales->order_qty; 
                        $Amount=$Amount + $sales->amount; 
                        $TotalAmount=$TotalAmount + $sales->total_amount; 
                        $start=$start+1;
                        $no=$no+1;
                        @endphp
                        @endforeach
                        <tr style="color:red;">
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th>
                              {{$OrderQty}} 
                           </th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th>{{ $DispQty }}</th>
                           <th></th>
                           <th></th>
                           <th>{{ $Amount }}</th>
                           <th></th>
                           <th></th>
                           <th>{{ $TotalAmount }}</th>
                           <th>{{$OrderQty - $DispQty }}</th>
                           <th></th>
                        </tr>
                        <tr>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <td></td>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                        </tr>
                        @php
                        }
                        $BalQty=0;
                        }
                        @endphp
                     </tbody>
                  </table>
               </div>
               <div class="row">
                  <!-- Fare Details -->
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Prepared By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Checked By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Approved By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Authorized By:</h4>
                  </div>
               </div>
               <br>
         </div>
         </main>
      </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="./assets/js/exporttoexcel.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"
      integrity="sha256-c9vxcXyAG4paArQG3xk6DjyW/9aHxai2ef9RpMWO44A=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
   <script>
      function html_table_to_excel(type)
       {
          var data = document.getElementById('Sales');
      
          var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
      
          XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
      
          XLSX.writeFile(file, 'Order Vs Shipment Report.' + type);
       }
      
       const export_button = document.getElementById('export_button');
      
       export_button.addEventListener('click', () =>  {
          html_table_to_excel('xlsx');
       });
      
      
      
      $('#printInvoice').click(function(){
                  Popup($('.invoice')[0].outerHTML);
                  function Popup(data) 
                  {
                      window.print();
                      return true;
                  }
              });
      		
      		
      		
      		
   </script>
</html>