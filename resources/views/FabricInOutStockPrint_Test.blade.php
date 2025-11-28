<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Global Pvt. Ltd.</title>
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
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Inward and Outward Stock Report   {{ date('d-m-Y',strtotime($fdate)) }} and {{ date('d-m-Y',strtotime($tdate)) }}				</h4>
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
                           <th rowspan="2">Opening Qty<br/>Meter</th>
                           <th rowspan="2">Opening Value<br/>Rs.</th>
                           <th rowspan="2">Inward Qty<br/>Meter</th>
                           <th rowspan="2">Inward Value<br/>Rs.</th>
                           <th rowspan="2">Outward Qty<br/>Meter</th>
                           <th rowspan="2">Outward Value<br/>Rs.</th>
                           <th rowspan="2">Closing Qty<br/>Meter</th>
                           <th rowspan="2">Closing Value<br/>Rs.</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php

                        $minDate = min($period);
                        $maxDate = max($period);
                        
                        $totalOpening = 0; 
                        $totalInward=0; 
                        $totalOutward=0; 
                        $totalOpening_value = 0;
                        $totalInwardValue = 0;
                        $totalOutwardValue = 0;
                        $totalOutwardClosingQty = 0;
                        $totalOutwardClosingValue = 0;                    
                        $no=0;

                        // New Code                      
                      $AllRows = DB::table('dump_fabric_stock_data')->get();
                     
                    $total_inward_qtyArray = [];  $total_inward_valueArray = [];
              
                    $results = DB::select("WITH RECURSIVE dates AS ( SELECT DATE('$minDate') AS dt UNION ALL SELECT DATE_ADD(dt, INTERVAL 1 DAY) FROM dates WHERE dt < '$maxDate' ) 
SELECT dates.dt AS in_date, COALESCE(SUM(dump.grn_qty), 0) AS total_day_grn_qty, 
COALESCE(SUM(dump.rate * dump.grn_qty), 0) AS total_amount 
FROM dates LEFT JOIN dump_fabric_stock_data AS dump ON dump.in_date = dates.dt GROUP BY dates.dt ORDER BY dates.dt");
 
                    foreach ($results as $row) {
                        $key = $row->in_date;
                     
                        if (!in_array($key, $period)) {
                           continue;
                        }
                     
                        if (!isset($total_inward_qtyArray[$key])) {
                           $total_inward_qtyArray[$key] = 0;
                        }                     
                        $total_inward_qtyArray[$key] = $row->total_day_grn_qty;
                        $total_inward_valueArray[$key] = $row->total_amount;
                     } 
                     

                     $outwardIndex = [];
                     foreach ($AllRows as $row) {
                        
                         

                        $entries = explode(",", $row->ind_outward_qty);

                        foreach ($entries as $entry) {
                           $parts = explode("=>", $entry);

                           $date  = $parts[0] ?? null;
                           $qty   = $parts[1] ?? 0;

                           if (!$date) continue;

                           if (!isset($outwardIndex[$date])) {
                                 $outwardIndex[$date] = [];
                           }

                           // add outward qty by date + row id
                           $outwardIndex[$date][] = [
                                 'qty'  => (float)$qty,
                                 'rate' => (float)$row->rate,
                           ];
                        }
                     }
                     
  
                     $allData = DB::table('dump_fabric_stock_data')
                     ->select(
                        'track_name',
                        'in_date',
                        'fout_date',
                        'grn_qty',
                        'ind_outward_qty',
                        'rate'
                     )
                     ->orderBy('in_date')
                     ->get();   
                     $grnMap = [];
                     foreach ($allData as $row) {
 
                        $key = $row->track_name . '|' . $row->in_date;
                        if (!isset($grnMap[$key])) $grnMap[$key] = 0;
                        $grnMap[$key] += $row->grn_qty;
                     }                      
                     //  End New Code
                         
                    

                        foreach ($period as $dt) 
                        {  
                        
 
                                // New Code
                           
                                $dtC = Carbon\Carbon::parse($dt);
                                $total_inward_qty    = 0;
                                 $total_inward_value  = 0;
                                 $total_outward_qty   = 0;
                                 $total_outward_value = 0;
                                 
                              if (isset($outwardIndex[$dt])) {
                           
                                 foreach ($outwardIndex[$dt] as $outData) {                                     
                                       $total_outward_qty   += $outData['qty'];
                                       $total_outward_value += $outData['qty'] * $outData['rate'];
                                 }
                              }  
                              // New Code End
                     
                        
                                   $total_stock = 0;
                                    $total_value = 0;
                                  
                                    foreach ($allData as $row1) {
                                        
                                       if ($row1->in_date >= $dt) continue;

                                       $key = $row1->track_name . '|' . $row1->in_date;
                                       $grn_qty1 = $grnMap[$key] ?? 0;
                                 
                                       // Calculate outward qty
                                       $out_qty = 0;
                                       $outList = explode(",", $row1->ind_outward_qty);

                                       foreach ($outList as $out) {
                                             $parts = explode("=>", $out);
                                             $out_date = $parts[0] ?? null;
                                             $qty = $parts[1] ?? 0;

                                             if ($out_date < $dt) {
                                                $out_qty += (float)$qty;
                                             }
                                       }                                      
                                       $stocks = $grn_qty1 - $out_qty;
                                       $total_stock += $stocks;
                                       
                                       $total_value += $stocks * $row1->rate;                                      
                                    }  
                          
                            if($no == 0)
                            {
                                $totalOpening = $totalOpening + $total_stock;
                                $totalOpening_value= $totalOpening_value + $total_value;
                            } 
                          
                           $totalOutwardClosingQty= $totalOpening + $total_inward_qtyArray[$dt] - $total_outward_qty; 
                           $totalOutwardClosingValue=$total_value + $total_inward_valueArray[$dt] - $total_outward_value;
                           
                           ?>
                            <tr>
                               <td style="text-align:center;" nowrap>{{ date('d-m-Y',strtotime($dt)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($totalOpening)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($totalOpening_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_inward_qtyArray[$dt])) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_inward_valueArray[$dt])) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_outward_qty)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_outward_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round(($totalOutwardClosingQty)))  }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round(($totalOutwardClosingValue)))  }}</td>
                            </tr>
                        <?php
                            $totalInward=$totalInward + $total_inward_qtyArray[$dt];
                            $totalOutward=$totalOutward + $total_outward_qty;
                            $totalInwardValue=$totalInwardValue + $total_inward_valueArray[$dt];
                            $totalOutwardValue=$totalOutwardValue + $total_outward_value;
                            $totalOpening = $totalOutwardClosingQty;
                            $totalOpening_value= $totalOutwardClosingValue;
                            $no=$no+1;     
                            } // End Foreach

                          
                        ?>
                     <tfoot>
                        <tr>
                           <td style="font-weight:bold;">  <b>Total :   </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> - </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> - </b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round($totalInward))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round($totalInwardValue))}}</td> 
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round($totalOutward))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round($totalOutwardValue))}}</td> 
                           <td style="font-weight:bold;text-align:right;">-</td>
                           <td style="font-weight:bold;text-align:right;">-</td> 
                        </tr>
                        <tr>
                           <td style="font-weight:bold;">  <b>Average : </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> -  </b></td>
                           <td style="font-weight:bold;text-align:right;">-</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round(($totalInward/$no)))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round(($totalInwardValue/$no)))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round(($totalOutward/$no)))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!.0i',round(($totalOutwardValue/$no)))}}</td>
                           <td style="font-weight:bold;text-align:right;">-</td>
                           <td style="font-weight:bold;text-align:right;">-</td>
                        </tr>
                     </tfoot>
                     </tbody>
                     </tbody>
                  </table>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/GetFabricInOutStockReportForm">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
      <script src="/assets/js/dtexport.js"></script>
   <script>  
        
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Fabric Inward and Outward Stock Report.' + type);
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


      table_values_indian_format();

   </script>
   
   

</html>