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
               </style>
               @php 
               $BuyerPurchaseOrderMasterList =  App\Models\BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.*','brand_master.brand_name')
               ->join('brand_master', 'brand_master.brand_id',"=",'buyer_purchse_order_master.brand_id')
               ->where('tr_code',"=",$BOMList[0]->sales_order_no)->get(); 
              
               $washingOutData = explode("-", $BOMList[0]->vpo_code);
              
               @endphp
               <center>
                  <h4 class="mb-0" style="font-weight:bold;">Gate Pass/ Delivery Note</h4>
               </center>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No: </b> <span style="display: inline-block;text-align: right;">WOUT-{{$washingOutData[1]}} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date: </b> <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($BOMList[0]->vpo_date)) }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no: </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sales_order_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Washing PO No: </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vpo_code }} </span></br>
                     </div>
                     <div  class="col-md-4">       
                        <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerPurchaseOrderMasterList[0]->brand_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->mainstyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->style_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->fg_name }}</span></br>
                     </div>
                     <div  class="col-md-4" >
                        <b style="display: inline-block;text-align: left;" class="mt-1">Vendor:</b> <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->vendorName }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b> <span style="display: inline-block;">{{  $BOMList[0]->address }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">GST No: </b> <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->pan_no }}</span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b> <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->gst_no }} </span></br>
                     </div>
                  </div>
               </div>
               @php  
               $SizeDetailList =  App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
               $sizes='';
               $no=1;
               foreach ($SizeDetailList as $sz) 
               {
               $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
               $no=$no+1;
               }
               $sizes=rtrim($sizes,',');
               //  DB::enableQueryLog();  
               $VendorPurchaseOrderDetailList =  App\Models\VendorPurchaseOrderDetailModel::where('vendor_purchase_order_detail.vpo_code','=', $BOMList[0]->vpo_code)
               ->join('color_master','color_master.color_id','=','vendor_purchase_order_detail.color_id')
               ->get(['vendor_purchase_order_detail.*','color_master.color_name']);
               @endphp
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm"  >
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Color</th>
                           @foreach ($SizeDetailList as $sz) 
                           <th>{{$sz->size_name}}</th>
                           @endforeach
                           <th>Total Qty</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php $no=1;  @endphp
                        @foreach($VendorPurchaseOrderDetailList as $rowDataList)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDataList->color_name }}</td>
                           @php 
                           $SizeQtyList=explode(',', $rowDataList->size_qty_array)
                           @endphp
                           @foreach($SizeQtyList  as $szQty)
                           <td class="text-center">{{ $szQty }} </td>
                           @endforeach
                           <td class="text-center">{{ $rowDataList->size_qty_total }}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th></th>
                        <th>Total</th>
                        @php
                        $nox=1;$sizex='';
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'sum(s'.$nox.') as s'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $SizeTotal= DB::select("select ".$sizex." , sum(size_qty_total) as Total from vendor_purchase_order_size_detail where vpo_code='".$BOMList[0]->vpo_code."'");
                        @endphp
                        @php foreach($SizeTotal as $row)
                        {
                        if(isset($row->s1)) { echo '
                        <th class="text-center">'.$row->s1.'</th>
                        ' ; }
                        if(isset($row->s2)) { echo '
                        <th class="text-center">'.$row->s2.'</th>
                        ' ; }
                        if(isset($row->s3)) { echo '
                        <th class="text-center">'.$row->s3.'</th>
                        ' ; }
                        if(isset($row->s4)) {echo  '
                        <th class="text-center">'.$row->s4.'</th>
                        ' ; }
                        if(isset($row->s5)) { echo '
                        <th class="text-center">'.$row->s5.'</th>
                        ' ; }
                        if(isset($row->s6)) { echo '
                        <th class="text-center">'.$row->s6.'</th>
                        ' ; }
                        if(isset($row->s7)) { echo '
                        <th class="text-center">'.$row->s7.'</th>
                        ' ;}
                        if(isset($row->s8)) { echo '
                        <th class="text-center">'.$row->s8.'</th>
                        ' ;}
                        if(isset($row->s9)) { echo '
                        <th class="text-center">'.$row->s9.'</th>
                        ' ;}
                        if(isset($row->s10)) { echo '
                        <th class="text-center">'.$row->s10.'</th>
                        ' ;}
                        if(isset($row->s11)) {echo  '
                        <th class="text-center">'.$row->s11.'</th>
                        ' ;}
                        if(isset($row->s12)) {echo '
                        <th class="text-center">'.$row->s12.'</th>
                        ' ;}
                        if(isset($row->s13)) { echo '
                        <th class="text-center">'.$row->s13.'</th>
                        ' ;}
                        if(isset($row->s14)) { echo '
                        <th class="text-center">'.$row->s14.'</th>
                        ';}
                        if(isset($row->s15)) {echo  '
                        <th class="text-center">'.$row->s15.'</th>
                        ' ;}
                        if(isset($row->s16)) {echo '
                        <th class="text-center">'.$row->s16.'</th>
                        ' ;}
                        if(isset($row->s17)) {echo '
                        <th class="text-center">'.$row->s17.'</th>
                        ' ;}
                        if(isset($row->s18)) { echo '
                        <th class="text-center">'.$row->s18.'</th>
                        ' ;}
                        if(isset($row->s19)) { echo '
                        <th class="text-center">'.$row->s19.'</th>
                        ' ;}
                        if(isset($row->s20)) {echo  '
                        <th class="text-center">'.$row->s20.'</th>
                        ' ;}
                        echo  '
                        <th class="text-center">'.$row->Total.'</th>
                        ' ;
                        }
                        @endphp
                     </tfoot>
                  </table>  
                    <div class="col-md-12 text-center" style="border:1px solid black;">
                        <h6><b>NOT FOR SALE, FOR JOB WORK ONLY</b></h6>
                    </div>
                    <div class="col-md-12 p-0">
                        <h4 class="mt-2" style="font-size:15px;">Comments:{{$BOMList[0]->narration}}</h4>
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