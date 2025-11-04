<!DOCTYPE html>
<html lang="en">
   <head>
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
         <!-- Header -->
         <div class="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="100" width="200"> </p>
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
               </div>
               <hr>
               <div class="">
                  <h4 class="text-4">
                     <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Gate Pass/ Delivery Note</h4>
                     <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Maintenance</h4>
                  </h4>
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
                  
                  .text-right
                  {
                     text-align: right; 
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($MaterialTransferList[0]->materialTransferFromCode) ? $MaterialTransferList[0]->materialTransferFromCode :"" }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y", strtotime($MaterialTransferList[0]->materialTransferFromDate)) }} </span></br>
                     </div>
                     <div class="col-md-4">         
                        <b style="display: inline-block;text-align: left;" class="mt-1">From: </b>  <span style="display: inline-block;text-align: right;">{{  isset($MaterialTransferList[0]->from_loc) ? $MaterialTransferList[0]->from_loc :"" }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">To: </b>  <span style="display: inline-block;text-align: right;">{{  isset($MaterialTransferList[0]->to_loc) ? $MaterialTransferList[0]->to_loc :"" }} </span></br>
                     </div>
                     <div class="col-md-4">         
                        <b style="display: inline-block;text-align: left;" class="mt-1">Driver Name : </b>  <span style="display: inline-block;text-align: right;">{{  isset($MaterialTransferList[0]->driver_name) ? $MaterialTransferList[0]->driver_name :"" }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Vehicle No:</b>  <span style="display: inline-block;text-align: right;">{{  isset($MaterialTransferList[0]->vehical_no) ? $MaterialTransferList[0]->vehical_no :"" }}</span></br> 
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Maintenance Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr>
                           <th rowspan="2">SrNo</th>
                           <th rowspan="2">Classification</th>
                           <th rowspan="2">Item Code</th>
                           <th rowspan="2">Item Name</th>
                           <th rowspan="2">GRN No.</th>
                           <th rowspan="2">Qty</th>
                           <th rowspan="2">UOM</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $materialTransferFromDetailsstables = App\Models\MaterialTransferFromDetailModel::
                        select('unit_master.unit_name','classification_master.class_name','spare_item_master.item_name','machine_type_master.machinetype_name', 'machine_make_master.machine_make_name', 'machine_model_master.mc_model_name',
                        'materialTransferFromDetails.spare_item_code','spare_item_master.item_description','spare_item_master.cgst_per','spare_item_master.sgst_per','spare_item_master.igst_per','materialTransferFromDetails.materiralInwardCode',
                        'spare_item_master.hsn_code','spare_item_master.dimension', DB::raw('sum(materialTransferFromDetails.item_qty) as item_qty'))
                        ->join('spare_item_master','spare_item_master.spare_item_code', '=', 'materialTransferFromDetails.spare_item_code')
                        ->join('machine_type_master','machine_type_master.machinetype_id', '=', 'spare_item_master.machinetype_id')
                        ->join('machine_make_master','machine_make_master.mc_make_Id', '=', 'spare_item_master.mc_make_Id')
                        ->join('machine_model_master','machine_model_master.mc_model_id', '=', 'spare_item_master.mc_model_id')
                        ->join('classification_master','classification_master.class_id', '=', 'spare_item_master.class_id')
                        ->join('unit_master','unit_master.unit_id', '=', 'spare_item_master.unit_id')
                        ->where('materialTransferFromDetails.materialTransferFromCode','=', $MaterialTransferList[0]->materialTransferFromCode)
                        ->groupby('materialTransferFromDetails.spare_item_code')
                        ->get();
                        $no=1; $amt=0;$tamt=0; $total = 0; @endphp
                        @foreach($materialTransferFromDetailsstables as $rowDetail)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->class_name }} </td>
                           <td>{{ $rowDetail->spare_item_code }}</td>
                           <td nowrap>
                                <div class="row">
                                     <div class="col-md-8">{{ $rowDetail->item_name }} </br>
                                        <b>Machine Type : </b>{{$rowDetail->machinetype_name}}</br>
                                        <b>Machine Make : </b>{{$rowDetail->machine_make_name}}</br>
                                        <b>Machine Model : </b>{{$rowDetail->mc_model_name}}</br>
                                        <b>Part Name : </b>{{$rowDetail->dimension}}
                                     </div>
                                </div>
                           </td>
                           <td>{{ $rowDetail->materiralInwardCode }}</td>
                           <td class="text-right">{{ round($rowDetail->item_qty,2) }}</td>
                           <td>{{ $rowDetail->unit_name }} </td>
                        </tr>
                        @php 
                            $no=$no+1; $total += round($rowDetail->item_qty,2);
                        @endphp
                        @endforeach
                        <tr>
                           <td class="text-right" colspan="5"><b>Total : </b></td> 
                           <td class="text-right"><b>{{ round($total,2) }}</b></td> 
                           <td></td> 
                        </tr>
                        <td colspan="7" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td>
                     </tbody>
                     <tfoot>
                         <tr><th colspan="7">Remark :  {{ isset($MaterialTransferList[0]->remark) ? $MaterialTransferList[0]->remark :"" }} </th></tr>
                     </tfoot>
                  </table><br/><br/>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-3">
                        <h4 class="text-4 mt-2"><b>Prepared By:</b></h4>
                     </div>
                     <div class="col-md-2">
                        <h4 class="text-4 mt-2"><b>Checked By:</b></h4>
                     </div>
                     <div class="col-md-2">
                        <h4 class="text-4 mt-2"><b>Approved By:</b></h4>
                     </div>
                     <div class="col-md-2">
                        <h4 class="text-4 mt-2"><b>Authorized By:</b></h4>
                     </div>
                     <div class="col-md-3">
                        <h4 class="text-4 mt-2"><b>Receiver Name & Signature:</b></h4>
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
      <p class="text-center d-print-none"><a href="/TrimsOutward">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
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