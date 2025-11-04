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
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
          <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
                  <div class="col-md-2">    
                  </div>
               </div>
               <hr>
               <div class="">
                  <h4 class="text-4">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">FG Inward and Outward Stock Report   {{ date('d-m-Y',strtotime($fdate)) }} and {{ date('d-m-Y',strtotime($tdate)) }}				</h4>
               </div>
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
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="text-align:center;">
                        <tr>
                           <th rowspan="2">Date</th> 
                           <th rowspan="2">Opening Qty</th>
                           <th rowspan="2">Opening Value</th>
                           <th rowspan="2">Inward Qty</th>
                           <th rowspan="2">Inward Value</th>
                           <th rowspan="2">Outward Qty</th>
                           <th rowspan="2">Outward Value</th> 
                           <th rowspan="2">Closing Qty</th>
                           <th rowspan="2">Closing Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        
                        $totalPackingQty=0; 
                        $totalPackingValue=0;
                        $totalCartonQty=0; 
                        $totalCartonValue=0;
                        $totalTransferQty=0; 
                        $totalTransferValue=0;
                        $totalOpeningQty=0; 
                        $totalOpeningValue=0; 
                        $totalClosingQty=0; 
                        $totalClosingValue=0; 
                        $opening_stocks = 0;
                        $opening_values = 0;
                        $cntr = 0;
                        $no=0; 
                        
                        $opening_stock = 0;
                        $opening_value = 0;
                     
                        foreach ($period as $dt) 
                        {
                            $closing_stock = 0;
                            $closing_value = 0; 
                            $cDate = date('Y-m-d', strtotime('-1 day', strtotime($dt)));
                           // DB::enableQueryLog();
                           $FinishedGoodsStock = DB::select("
                                                    SELECT  FG.entry_date,
                                                            SUM(CASE WHEN FG.entry_date = '".$dt."' AND FG.data_type_id IN (1) THEN FG.size_qty ELSE 0 END) AS total_packing_qty,
                                                            SUM(CASE WHEN FG.entry_date = '".$dt."' AND FG.data_type_id IN (1) THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END) AS total_packing_value,
                                                            SUM(CASE WHEN FG.invoice_date = '".$dt."' AND FG.data_type_id IN (2) THEN FG.size_qty ELSE 0 END) AS total_carton_qty,
                                                            SUM(CASE WHEN FG.invoice_date = '".$dt."' AND FG.data_type_id IN (2) THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END) AS total_carton_value,
                                                            SUM(CASE WHEN FG.entry_date = '".$dt."' AND FG.data_type_id IN (3) THEN FG.size_qty ELSE 0 END) AS total_transfer_qty,
                                                            SUM(CASE WHEN FG.entry_date = '".$dt."' AND FG.data_type_id IN (3) THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END) AS total_transfer_value,
                                                            SUM(FG.size_qty) AS total_stock,
                                                            SUM(FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) AS total_stock_value,
                                                           
                                                            (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date <= '".$cDate."' THEN FG.size_qty ELSE 0 END) - 
                                                             SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date <= '".$cDate."' THEN FG.size_qty ELSE 0 END) - 
                                                             SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date <= '".$cDate."' THEN FG.size_qty ELSE 0 END)) AS opening_stock,
                                                             
                                                            (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date <= '".$cDate."' THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END) - 
                                                             SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date <= '".$cDate."' THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END) - 
                                                             SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date <= '".$cDate."' THEN 
                                                                (FG.size_qty * IF(sales_order_costing_master.total_cost_value > 0, sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate))
                                                            ELSE 0 END)) AS opening_value
                                                    FROM FGStockDataByTwo AS FG
                                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no   
                                                    LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                                                ");

                            //dd(DB::getQueryLog());
                                $srno = 1;    
                                foreach($FinishedGoodsStock as $row)
                                {  
                                
                                    if($no == 0)
                                    {
                                        $opening_stocks = $row->opening_stock;
                                        $opening_values = $row->opening_value;
                                    } 
                                    
                                    $closing_stock = $opening_stocks + ($row->total_packing_qty - $row->total_carton_qty - $row->total_transfer_qty);
                                    $closing_value = $opening_values + ($row->total_packing_value -  $row->total_carton_value - $row->total_transfer_value);  
                            @endphp
                            <tr>
                               <td style="text-align:center;">{{ date('d-m-Y',strtotime($dt)) }}  </td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($opening_stocks)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($opening_values)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($row->total_packing_qty)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($row->total_packing_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($row->total_carton_qty)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($row->total_carton_value)) }}</td> 
                               <td style="text-align:right;">{{ money_format('%!.0n',round($closing_stock)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0n',round($closing_value,0)) }}</td> 
                            </tr>
                            @php 
                            
                             
                            $totalOpeningQty += $opening_stocks;
                            $totalOpeningValue += $opening_values;
                            $totalPackingQty += $row->total_packing_qty;
                            $totalPackingValue += $row->total_packing_value;
                            $totalCartonQty += $row->total_carton_qty;
                            $totalCartonValue += $row->total_carton_value;
                            $totalTransferQty += $row->total_transfer_qty;
                            $totalTransferValue += $row->total_transfer_value;
                            $totalClosingQty += $closing_stock;
                            $totalClosingValue += $closing_value;
                               
                            $no=$no+1;
                            
                            $opening_stocks = $closing_stock;
                            $opening_values = $closing_value;

                            }
                        }
                        @endphp
                     </tbody>
                     <tfoot>
                        <tr>
                           <td style="font-weight:bold;">  <b>Total :   </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>-</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>-</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>{{money_format('%!.0n',round($totalPackingQty))}}</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>{{money_format('%!.0n',round($totalPackingValue))}}</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>{{money_format('%!.0n',round($totalCartonQty))}}</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>{{money_format('%!.0n',round($totalCartonValue))}}</b></td>      
                           <td style="font-weight:bold;text-align:right;"> <b>-</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>-</b></td>  
                        </tr>
                        <tr>
                           <td>   </td>
                           <td style="font-weight:bold;">  <b>Average :   </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b>-</b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0n',round($totalPackingQty/count($period)))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0n',round($totalPackingValue/count($period)))}}</td> 
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0n',round($totalCartonQty/count($period)))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0n',round($totalCartonValue/count($period)))}}</td>
                           <td>-</td>
                           <td>-</td>
                        </tr>
                     </tfoot>
                     </tbody>
                  </table>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/GetFGInOutStockReportForm">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
        
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'FG Inward and Outward Stock Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
      $('#printInvoice').click(function()
      {
          Popup($('.invoice')[0].outerHTML);
          function Popup(data) 
          {
              window.print();
              return true;
          }
      });
      
      
   </script>
</html>