@php   
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Global Designs Pvt. Ltd.</title>
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
         <!-- Header -->
         <div class="invoice">
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
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
                  .sizes{
                      font-size:20px;
                  }
                  .sizes1{
                      font-size:18px;
                  }
               </style>
               <center>
                  <h4 class="mb-0" style="font-weight:bold;">Fabric Cutting</h4>
               </center>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Cu Code: </b> <span style="display: inline-block;text-align: right;"> {{ $CuttingMasterList->cu_code }}</span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Cu Date: </b> <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($CuttingMasterList->cu_date)) }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order No: </b> <span style="display: inline-block;text-align: right;"> {{ $CuttingMasterList->sales_order_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">CPO Code: </b> <span style="display: inline-block;text-align: right;"> {{ $CuttingMasterList->vpo_code }} </span></br>
                     </div> 
                     <div  class="col-md-4">       
                        <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b> <span style="display: inline-block;text-align: right;"> {{ $CuttingMasterList->vendor_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->mainstyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->substyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->fg_name }}</span></br>
                     </div>
                     <div  class="col-md-4">       
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->style_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Description:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->style_description }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Table Task:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->table_task_code }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Table Average:</b>    <span style="display: inline-block;text-align: right;">{{ $CuttingMasterList->table_avg }} </span></br>
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm"  >
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Track Code</th>
                           <th>Item</th>
                           <th>Width</th>
                           <th>Meter</th>
                           <th>Shade</th>
                           <th>Layers</th>
                           <th>Used Meter</th>
                           <th>Balance</th>
                           <th>Cut Piece Meter</th>
                           <th>Actual Balance</th>
                           <th>Damage Meter</th>
                           <th>Short Meter</th>
                           <th>Extra Meter</th> 
                        </tr>
                     </thead>
                     <tbody>
                         @php
                            $no = 1;
                            $total_meter = 0;
                            $total_layers = 0;
                            $total_used_meter = 0;
                            $total_balance = 0;
                            $total_cut_piece_meter = 0;
                            $total_actual_balance = 0;
                            $total_damage_meter = 0;
                            $total_short_meter = 0;
                            $total_extra_meter = 0;
                         @endphp
                         @foreach($CuttingBalanceDetailList as $row)
                         <tr>
                             <td>{{$no++}}</td>
                             <td>{{$row->track_code}}</td>
                             <td>{{$row->item_name}}</td>
                             <td>{{money_format('%!i',$row->width)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->meter)}}</td>
                             <td class="text-center">{{$row->shade_name}}</td>
                             <td class="text-right">{{money_format('%!i',$row->layers)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->used_meter)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->balance_meter)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->cpiece_meter)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->actual_balance)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->dpiece_meter)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->short_meter)}}</td>
                             <td class="text-right">{{money_format('%!i',$row->extra_meter)}}</td>
                         </tr>
                         @php
                            $total_meter += $row->meter;
                            $total_layers += $row->layers;
                            $total_used_meter += $row->used_meter;
                            $total_balance += $row->balance_meter;
                            $total_cut_piece_meter += $row->cpiece_meter;
                            $total_actual_balance += $row->actual_balance;
                            $total_damage_meter += $row->dpiece_meter;
                            $total_short_meter += $row->short_meter;
                            $total_extra_meter += $row->extra_meter; 
                         @endphp
                         @endforeach
                     </tbody>
                     <tfoot>
                         <tr> 
                             <th colspan="4" class="text-right">Total : </th>
                             <th class="text-right">{{money_format('%!i',$total_meter)}}</th>
                             <th class="text-center">-</th>
                             <th class="text-right">{{money_format('%!i',$total_layers)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_used_meter)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_balance)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_cut_piece_meter)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_actual_balance)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_damage_meter)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_short_meter)}}</th>
                             <th class="text-right">{{money_format('%!i',$total_extra_meter)}}</th>
                         </tr>
                     </tfoot>
                  </table>  
                  <div class="row">
                  <div class="col-md-4 p-0">
                      <table class="table table-bordered text-1 table-sm"  >
                         <thead>
                            <tr  style="background-color:#eee; text-align:center;">
                               <th>Ratio</th>
                               <th>Size</th>
                               <th>Cut Qty</th> 
                            </tr>
                         </thead>
                         <tbody> 
                             @php
                                $total_Cut_Qty = 0;
                             @endphp
                             @foreach($CuttingDetailList as $row)
                             <tr>
                                 <td class="text-center">{{$row->ratio}}</td>
                                 <td class="text-center">{{$row->size_name}}</td>
                                 <td class="text-right">{{$row->cut_qty}}</td> 
                             </tr> 
                             @php
                                $total_Cut_Qty += $row->cut_qty;
                             @endphp
                             @endforeach
                         </tbody> 
                         <tfoot>
                             <tr> 
                                 <th colspan="2" class="text-right">Total : </th>  
                                 <th class="text-right">{{$total_Cut_Qty}}</th> 
                             </tr>
                         </tfoot>
                      </table>   
                      </div>  
                      <div class="col-md-5">
                         <div class="mb-3">
                            <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                               <tbody>
                                    <tr>
                                         <th></th>
                                         <th class="text-center sizes">Date</th>
                                         <th class="text-center sizes">Start Time</th>
                                         <th class="text-center sizes">End Time</th> 
                                    </tr>
                                    <tr>
                                         <td class="sizes"><b>Layer</b></td>
                                         <td class="sizes1">{{date("d-m-Y", strtotime($CuttingMasterList->layer_date))}}</td> 
                                         <td class="sizes1">{{date("h:i A", strtotime($CuttingMasterList->layer_start_time))}}</td> 
                                         <td class="sizes1">{{date("h:i A", strtotime($CuttingMasterList->layer_end_time))}}</td> 
                                    </tr>
                                    <tr>
                                         <td class="sizes"><b>Cutting</b></td>
                                         <td class="sizes1">{{date("d-m-Y", strtotime($CuttingMasterList->cutting_date))}}</td> 
                                         <td class="sizes1">{{date("h:i A", strtotime($CuttingMasterList->cutting_start_time))}}</td> 
                                         <td class="sizes1">{{date("h:i A", strtotime($CuttingMasterList->cutting_end_time))}}</td> 
                                    </tr>
                               </tbody>
                            </table>
                         </div>
                      </div>
                      </div>
                      <div class="row">
                         <div  class="col-md-3">
                            <h5><b>Total Cut Qty: </b>{{money_format('%!i',$CuttingMasterList->total_pieces)}}</h5></br>     
                         </div> 
                         <div  class="col-md-4">       
                            <h5><b>Calculated Fabric Balance: </b>{{ money_format('%!i',($total_balance + $total_cut_piece_meter)) }}</h5></br>
                         </div>
                         <div  class="col-md-3">       
                            <h5><b>Actual Fabric Balance: </b>{{ money_format('%!i',$total_actual_balance) }}</h5></br> 
                         </div>
                         <div  class="col-md-2">        
                            <h5><b>Difference : </b>{{ money_format('%!i',(($total_balance + $total_cut_piece_meter) - $total_actual_balance))}}</h5></br>
                         </div>
                      </div>
                   </div>
                    <div class="col-md-12 p-0 mt-4">
                        <h4 class="mt-2" style="font-size:15px;">Comments:{{$CuttingMasterList->narration}}</h4>
                    </div>
                  <br>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-3">
                        <h4 class="mt-2" style="font-size:15px;">PREPARED BY:</h4>
                     </div>
                     <div class="col-md-3">
                        <h4 class="mt-2" style="font-size:15px;">CHECKED BY:</h4>
                     </div>
                     <div class="col-md-3">
                        <h4 class="mt-2" style="font-size:15px;">APPROVED BY:</h4>
                     </div>
                     <div class="col-md-3">
                        <h4 class="mt-2" style="font-size:15px;">AUTHORIZED BY:</h4>
                     </div>
                  </div>
                  <br>
                  <!-- Footer -->
                  <footer  >
                     <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js'"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
      
   </script>
</html>