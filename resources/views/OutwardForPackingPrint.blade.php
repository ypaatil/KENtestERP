<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>KEN GLOBAL DESIGNS PRIVATE LIMITED</title>
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
               </style>
               <h3 class="text-center mt-2" style="color: #000;font-weight:bold;">Gate Pass/ Delivery Note</h3>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-2">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->ofp_code }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y", strtotime($OutwardForPackingMaster[0]->ofp_date)) }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->sales_order_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Work Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->vw_code }} </span></br>  
                     </div>
                     <div  class="col-md-3" >  
                        <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->mainstyle_name }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->substyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style No:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->fg_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $OutwardForPackingMaster[0]->style_no }} </span></br>  
                     </div>
                     <div  class="col-md-3">         
                        <b style="text-align: left;" class="mt-1"><h4>FROM Address</h4>Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->ac_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: left;">{{  $OutwardForPackingMaster[0]->address }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->gst_no }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->pan_no }}</span></br>
                     </div>
                     <div  class="col-md-3">         
                        <b style="text-align: left;" class="mt-1"><h4>To Address</h4> Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->sent_location }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: left;">{{  $OutwardForPackingMaster[0]->sent_loc_inc }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->sent_gst_no }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->sent_pan_no }}</span></br>
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Outward For Packing Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr class="text-center">
                           <th>SrNo</th> 
                           <th>Garment Color</th>
                           @foreach ($SizeDetailList as $sz) 
                           <th>{{$sz->size_name}}</th>
                           @endforeach
                           <th>Total Qty</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php   $no=1; $totalAmt=0; $totalQty=0;@endphp
                        @foreach ($OutwardForPackingList as $row) 
                        <tr>
                           <td class="text-center">{{$no}}</td> 
                           <td>{{$row->color_name}}</td>
                           @if(isset($row->s1))  
                           <td class="text-center">{{$row->s1}}</td>
                           @endif
                           @if(isset($row->s2)) 
                           <td class="text-center">{{$row->s2}}</td>
                           @endif
                           @if(isset($row->s3)) 
                           <td class="text-center">{{$row->s3}}</td>
                           @endif
                           @if(isset($row->s4)) 
                           <td class="text-center">{{$row->s4}}</td>
                           @endif
                           @if(isset($row->s5)) 
                           <td class="text-center">{{$row->s5}}</td>
                           @endif
                           @if(isset($row->s6)) 
                           <td class="text-center">{{$row->s6}}</td>
                           @endif
                           @if(isset($row->s7)) 
                           <td class="text-center">{{$row->s7}}</td>
                           @endif
                           @if(isset($row->s8)) 
                           <td class="text-center">{{$row->s8}}</td>
                           @endif
                           @if(isset($row->s9)) 
                           <td class="text-center">{{$row->s9}}</td>
                           @endif
                           @if(isset($row->s10)) 
                           <td class="text-center">{{$row->s10}}</td>
                           @endif
                           @if(isset($row->s11)) 
                           <td class="text-center">{{$row->s11}}</td>
                           @endif
                           @if(isset($row->s12)) 
                           <td class="text-center">{{$row->s12}}</td>
                           @endif
                           @if(isset($row->s13)) 
                           <td class="text-center">{{$row->s13}}</td>
                           @endif
                           @if(isset($row->s14)) 
                           <td class="text-center">{{$row->s14}}</td>
                           @endif
                           @if(isset($row->s15)) 
                           <td class="text-center">{{$row->s15}}</td>
                           @endif
                           @if(isset($row->s16)) 
                           <td class="text-center">{{$row->s16}}</td>
                           @endif
                           @if(isset($row->s17)) 
                           <td class="text-center">{{$row->s17}}</td>
                           @endif
                           @if(isset($row->s18)) 
                           <td class="text-center">{{$row->s18}}</td>
                           @endif
                           @if(isset($row->s19)) 
                           <td class="text-center">{{$row->s19}}</td>
                           @endif
                           @if(isset($row->s20))  
                           <td class="text-center">{{$row->s20}}</td>
                           @endif
                           <td class="text-center">{{$row->size_qty_total}}</td>
                        </tr>
                        @php $no=$no+1; 
                        $totalQty = $totalQty + $row->size_qty_total;
                        @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <tr> 
                           <th></th>
                           <th colspan="{{count($SizeDetailList) + 1}}">Total</thd>
                           <th class="text-center">{{$totalQty}}</th>
                        </tr>
                     </tfoot>
                  </table>  
                  <div class="col-md-12 text-center" style="border:1px solid black;">
                        <h6><b>NOT FOR SALE, FOR JOB WORK ONLY</b></h6>
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
                  <!-- Footer -->
                  <footer  >
                     <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/OutwardForPacking">&laquo; Back to List</a></p>
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