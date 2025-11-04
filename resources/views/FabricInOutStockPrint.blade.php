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
                        @php 
                        
                        $totalOpening = 0; 
                        $totalInward=0; 
                        $totalOutward=0; 
                        $totalOpening_value = 0;
                        $totalInwardValue = 0;
                        $totalOutwardValue = 0;
                        $totalOutwardClosingQty = 0;
                        $totalOutwardClosingValue = 0;
                        $temp = 0;
                        $no=0;
                        
                        foreach ($period as $dt) 
                        {  
                                                    
                            $FabricInwardDetails =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name 
                                AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date = '".$dt."' ) as gq
                                FROM dump_fabric_stock_data WHERE in_date <='".$dt."' OR fout_date <='".$dt."'");
                                
                            $FabricInwardDetails1 =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name 
                                                    AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date < '".$dt."' ) as gq
                                                    FROM dump_fabric_stock_data WHERE in_date < '".$dt."'");
                                    
                            $total_inward_qty = 0;
                            $total_outward_qty = 0;
                            $total_inward_value = 0;
                            $total_outward_value = 0;
                            foreach($FabricInwardDetails as $row)
                            { 
                                $q_qty = 0;  
                                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                                $ind_outward1 = (explode(",",$row->ind_outward_qty)); 
                                
                                foreach($ind_outward1 as $indu)
                                {
                                     $ind_outward4 = (explode("=>",$indu));
                                     $q_qty1 = isset($ind_outward4[1]) ? $ind_outward4[1] : 0;
                                     if($ind_outward4[0] == $dt)
                                     {
                                         $q_qty += $q_qty1;
                                     } 
                                }
                                     
                                $total_outward_qty += $q_qty;
                                $total_outward_value += $q_qty * $row->rate;
                                $total_inward_qty += $grn_qty;
                                $total_inward_value += $grn_qty * $row->rate; 
                            }
                        
                            $total_value = 0;
                            $total_stock = 0; 
                            $total_inward_qty1 = 0;
                            $total_outward_qty1 = 0;
                            $total_inward_value1 = 0;
                            $total_outward_value1 = 0;
                            foreach($FabricInwardDetails1 as $row1)
                            {
                                $grn_qty1 = isset($row1->gq) ? $row1->gq : 0; 
                                $ind_outward2 = (explode(",",$row1->ind_outward_qty));
                                $q_qty3 = 0; 
                               
                                foreach($ind_outward2 as $indu1)
                                {
                                     $ind_outward3 = (explode("=>",$indu1));
                                     $q_qty2 = isset($ind_outward3[1]) ? $ind_outward3[1] : 0;
                                     if($ind_outward3[0] < $dt)
                                     {
                                         $q_qty3 += $q_qty2;
                                     }
                                     
                                }
                               
                                $stocks =  $grn_qty1 - $q_qty3;
                                $total_stock += $stocks;  
                                $total_value += $stocks * $row1->rate;  
                               
                            }
                          
                            if($no == 0)
                            {
                                $totalOpening = $totalOpening + $total_stock;
                                $totalOpening_value= $totalOpening_value + $total_value;
                            } 
                            
                            $totalOutwardClosingQty= $totalOpening + $total_inward_qty - $total_outward_qty; 
                            $totalOutwardClosingValue=$total_value + $total_inward_value - $total_outward_value;
                            @endphp
                            <tr>
                               <td style="text-align:center;" nowrap>{{ date('d-m-Y',strtotime($dt)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($totalOpening)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($totalOpening_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_inward_qty)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_inward_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_outward_qty)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round($total_outward_value)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round(($totalOutwardClosingQty)))  }}</td>
                               <td style="text-align:right;">{{ money_format('%!.0i',round(($totalOutwardClosingValue)))  }}</td>
                            </tr>
                            @php
                            $totalInward=$totalInward + $total_inward_qty;
                            $totalOutward=$totalOutward + $total_outward_qty;
                            $totalInwardValue=$totalInwardValue + $total_inward_value;
                            $totalOutwardValue=$totalOutwardValue + $total_outward_value;
                            $totalOpening = $totalOutwardClosingQty;
                            $totalOpening_value= $totalOutwardClosingValue;
                            $no=$no+1;     
                            }
                            @endphp
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
      
      
   </script>
</html>