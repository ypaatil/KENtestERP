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
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;">Date: {{ date("d-m-Y", strtotime($SampleList[0]->sample_cust_feed_date)) }}</h6>
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
                  
                  #checklist_table 
                  {
                    page-break-before: always;
                  }
               </style>
               <center>
                  <h6 class="mb-0" style="font-weight:bold;">Sample Customer Feedback</h6>
               </center>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sample Indent Date : </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y", strtotime($SampleList[0]->sample_cust_feed_date)) }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Indent No : </b> <span style="display: inline-block;text-align: right;"> {{ $SampleList[0]->sample_indent_code }} </span></br> 
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Name: </b> <span style="display: inline-block;text-align: right;"> {{ $SampleList[0]->Ac_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Brand : </b> <span style="display: inline-block;text-align: right;"> {{ $SampleList[0]->brand_name }} </span></br>
                     </div>
                     <div  class="col-md-4">         
                        <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Category : </b> <span style="display: inline-block;text-align: right;">{{ $SampleList[0]->mainstyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Category : </b> <span style="display: inline-block;text-align: right;">{{ $SampleList[0]->substyle_name }} </span></br> 
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Description : </b> <span style="display: inline-block;text-align: right;">{{ $SampleList[0]->style_description }} </span></br> 
                        <b style="display: inline-block;text-align: left;" class="mt-1">SAM : </b> <span style="display: inline-block;text-align: right;"> {{ $SampleList[0]->sam }} </span></br>
                     </div>
                     <div  class="col-md-4" >
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sample Type : </b><span style="display: inline-block;">{{  $SampleList[0]->sample_type_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Department Type : </b><span style="display: inline-block;">{{  $SampleList[0]->dept_type_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sample Required Date : </b><span style="display: inline-block;">{{  date("d-m-Y", strtotime($SampleList[0]->sample_required_date)) }} </span></br>
                     </div>
                  </div>
               </div>
               @php  
               
               $SizeDetailList =  App\Models\SizeDetailModel::where('size_detail.sz_code','=', $SampleList[0]->sz_code)->get();
               
               $sizes='';
               $no=1;
               foreach ($SizeDetailList as $sz) 
               {
               $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
               $no=$no+1;
               }
               $sizes=rtrim($sizes,',');
              // DB::enableQueryLog();  
               $SampleIndentDetailList = DB::table('sample_indent_order')
               ->where('sample_indent_order.sample_indent_code','=', $SampleList[0]->sample_indent_code)
               ->get(['sample_indent_order.*']);
              // dd(DB::getQueryLog());
               @endphp
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Order Qty</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; " id="assortDetail">
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
                        @foreach($SampleIndentDetailList as $rowDataList)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDataList->color }}</td>
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
                  </table>
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Stitching Qty</h4>
                 
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; " id="assortDetail">
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
                        @php $no1=1;  @endphp
                        @foreach($SampleStitchingDetailList as $rowDataList1)  
                        <tr>
                           <td>{{ $no1 }}</td>
                           <td>{{ $rowDataList1->color }}</td>
                           @php 
                           $SizeQtyList=explode(',', $rowDataList1->size_qty_array)
                           @endphp
                           @foreach($SizeQtyList  as $szQty)
                           <td class="text-center">{{ $szQty }} </td>
                           @endforeach
                           <td class="text-center">{{ $rowDataList1->size_qty_total }}</td>
                        </tr>
                        @php
                        $no1=$no1+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table> 
                  <table id="footable_4" class="table table-bordered table-striped m-b-0 footable_4">
                    <thead>
                        <tr>
                            <th>Bom Type</th>
                            <th>Material Received Status</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr> 
                               <td>
                                   Fabric
                               </td>
                               <td>
                                    @php
                                        $detail1 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 1 AND sample_indent_code='".$sample_indent_code."'");
                                        $m1 = isset($detail1[0]->material_received_status_id) ? $detail1[0]->material_received_status_id : 0;
                                        
                                        $MaterialReceivedList = DB::table('material_received_status')->where('material_received_status_id','=', $m1)->where('delflag','=', '0')->get();
                                    @endphp
                                    {{$MaterialReceivedList[0]->material_received_status_name}}
                               </td>
                            </tr>
                            <tr> 
                               <td>
                                   Sewing Trims 
                               </td>
                               <td>
                                    @php
                                        $detail2 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 2 AND sample_indent_code='".$sample_indent_code."'");
                                        $m2 = isset($detail2[0]->material_received_status_id) ? $detail2[0]->material_received_status_id : 0;
                                        
                                        $MaterialReceivedList2 = DB::table('material_received_status')->where('material_received_status_id','=', $m2)->where('delflag','=', '0')->get();
                                    @endphp
                                    {{$MaterialReceivedList2[0]->material_received_status_name}}
                               </td>
                            </tr>
                            <tr> 
                               <td>
                                   Packing Trims
                                   <input type="hidden" name="bom_type_id[]" value="3" id="bom_type_id" style="width:50px;"/>
                               </td>
                               <td>
                                    @php
                                        $detail3 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 3 AND sample_indent_code='".$sample_indent_code."'");
                                        $m3 = isset($detail3[0]->material_received_status_id) ? $detail3[0]->material_received_status_id : 0;
                                        
                                        $MaterialReceivedList1 = DB::table('material_received_status')->where('material_received_status_id','=', $m3)->where('delflag','=', '0')->get();
                                    @endphp
                                    {{$MaterialReceivedList1[0]->material_received_status_name}}
                               </td>
                            </tr>
                        </tbody>
                  </table>
                  <table id="footable_4" class="table table-bordered table-striped m-b-0 footable_4">
                    <thead>
                        <tr>
                            <th>Customer Feedback</th>
                            <th>Customer Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr> 
                               <td>
                                    @php 
                                        $customer_feedback_status = DB::table('customer_feedback_status')->where('delflag','=', '0')->where('cust_feed_status_id','=', $SampleList[0]->cust_feed_status_id)->get();
                                        $status = isset($customer_feedback_status[0]->cust_feed_status_name) ? $customer_feedback_status[0]->cust_feed_status_name : ""; 
                                    @endphp
                                    {{$status}}
                               </td>
                               <td> 
                                   {{ $SampleList[0]->cust_comments  }}
                               </td>
                            </tr> 
                        </tbody>
                  </table>
                  <div class="row">
                     <div class="col-md-16">
                        <h4 class="mt-2" style="font-size:15px;">Comments:{{isset($SampleList[0]->remark) ? $SampleList[0]->remark : ""}}</h4>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-4">
                        <h4 class="mt-2" style="font-size:15px;">PREPARED BY:</h4>
                     </div>
                     <div class="col-md-4">
                        <h4 class="mt-2" style="font-size:15px;">CHECKED BY:</h4>
                     </div>
                     <div class="col-md-4">
                        <h4 class="mt-2" style="font-size:15px;">APPROVED BY:</h4>
                     </div>
                  </div>
                  <br>
                  <hr/>
                  <!-- Footer -->
                  <footer  >
                     <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/SampleCustomerFeedback">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  
//   $('#printInvoice').click(function(){
    //   Popup($('.invoice')[0].outerHTML);
    //   function Popup(data) 
    //   {
    //       window.print();
    //       return true;
    //   }
    //   });
        // $(document).ready(function(){
        //     var result = [];
        //     $('#assortDetail tr').each(function(){
        //       $('td', this).each(function(index, val){
        //           if(!result[index]) result[index] = 0;
        //           result[index] += parseFloat($(val).text());
        //       });
        //     });
        //     result.shift();
        //     result.shift();
        //     $('#assortDetail').append('<tr><td colspan="2" class="text-center"><strong>Total : </strong></td></tr>');
        //     $(result).each(function(){
        //       $('#assortDetail tr').last().append('<td class="text-center"><strong>'+this+'</strong></td>')
        //     });
        // });
      
   </script>
</html>