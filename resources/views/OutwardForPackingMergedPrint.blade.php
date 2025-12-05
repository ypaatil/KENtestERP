<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Outward For Packing</title>
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
                  <div class="col-md-2">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="80" width="120"> </p>
                  </div>
                  <div class="col-md-8 text-center">
                     <h5 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h5>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
                  <div class="col-md-2">    
                  </div>
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
               <h3 class="text-center" style="color: #000;font-weight:bold;">Gate Pass/ Delivery Note</h3>
               <div id="printInvoice">
                   @php
                    
                    $OutwardForPackingMaster = DB::table('outward_for_packing_master')->join('usermaster', 'usermaster.userId', '=', 'outward_for_packing_master.userId')
                                 ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'outward_for_packing_master.sent_to')
                                 ->leftJoin('ledger_master as LM1', 'LM1.Ac_code', '=', 'outward_for_packing_master.vendorId')
                                 ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'outward_for_packing_master.mainstyle_id')
                                 ->leftJoin('location_master', 'location_master.loc_id', '=', 'outward_for_packing_master.sent_to')
                                 ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'outward_for_packing_master.fg_id')
                                 ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','outward_for_packing_master.vw_code')
                                 ->whereIn('outward_for_packing_master.ofp_code', $ofpCodes)
                                 ->get(['outward_for_packing_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','LM1.ac_short_name as vendor_name','LM1.address as vendor_address', 'outward_for_packing_master.sales_order_no',
                                 'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address','mainstyle_name','location_master.location as sent_to','location_master.loc_inc as loc_address','fg_name']);
                     
                   @endphp
                  <div class="col-md-12" style="border: #000000 solid 1px;">
                      <div class="row">
                         <div  class="col-md-7">
                            <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{$ofpCodes1}} </span></br>     
                            <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y", strtotime($OutwardForPackingMaster[0]->ofp_date)) }} </span></br>
                            <b style="display: inline-block;text-align: left;" class="mt-1">Form Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->vendor_name }} </span></br>
                            <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: left;">{{  $OutwardForPackingMaster[0]->vendor_address }}</span></br>
                         </div>
                         <div  class="col-md-5">         
                            <b style="display: inline-block;text-align: left;" class="mt-1">To Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $OutwardForPackingMaster[0]->sent_to }} </span></br>
                            <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: left;">{{  $OutwardForPackingMaster[0]->loc_address }}</span></br>
                         </div>
                      </div>
                  </div>
               </div>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Outward For Packing Details:</h4>
               @php
               
                     foreach($ofpCodes as $key=>$details)
                     {
                //   DB::enableQueryLog();
                   
                     $OutwardForPackingMaster = DB::table('outward_for_packing_master')->join('usermaster', 'usermaster.userId', '=', 'outward_for_packing_master.userId')
                     ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'outward_for_packing_master.sent_to')
                     ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'outward_for_packing_master.mainstyle_id')
                     ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'outward_for_packing_master.sent_to')
                     ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'outward_for_packing_master.fg_id')
                     ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','outward_for_packing_master.vw_code')
                     ->where('outward_for_packing_master.ofp_code', $ofpCodes[$key])
                     ->get(['outward_for_packing_master.*','usermaster.username','ledger_master.Ac_name','outward_for_packing_master.sales_order_no',
                     'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address','mainstyle_name','substyle_name','fg_name']);
                   
                    
                    $BuyerPurchaseOrderMasterList =  DB::table('buyer_purchse_order_master')->where('tr_code',$OutwardForPackingMaster[0]->sales_order_no)->get();
                               
                    
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
                    $OutwardForPackingList = DB::select("SELECT outward_for_packing_size_detail.color_id, color_master.color_name, ".$sizes.", 
                    sum(size_qty_total) as size_qty_total  from	outward_for_packing_size_detail  
                    inner join color_master on color_master.color_id=outward_for_packing_size_detail.color_id 
                    where ofp_code='".$OutwardForPackingMaster[0]->ofp_code."' group by outward_for_packing_size_detail.color_id");
                         //     $query = DB::getQueryLog();
                    //   $query = end($query);
                    //   dd($query);
               @endphp
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm data-table" style="height:10vh; ">
                     <thead>
                        <tr>
                           <td colspan="{{count($SizeDetailList) + 3}}"><b>D. No. : </b> {{$ofpCodes[$key]}},     <b>Sales Order No : </b> {{$OutwardForPackingMaster[0]->sales_order_no}},     <b>Style : </b> {{$OutwardForPackingMaster[0]->mainstyle_name}}</td> 
                            
                        </tr>
                        <tr class="text-center">
                           <th>Sr No</th>
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
                  </table> 
                   <div class="col-md-12">
                      <span style="font-size: 18px !important;"><b>Remark : </b>{{$OutwardForPackingMaster[0]->narration}}</span>
                  </div> 
                  <hr/>  
                    @php
                        }
                    @endphp
                  <div class="col-md-12 text-right">
                      <h4><b>Grand Total : <span id="grand_total"></span></b><h4> 
                  </div><br/>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>  $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
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
</html>