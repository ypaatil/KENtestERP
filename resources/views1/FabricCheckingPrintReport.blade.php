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
          <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice" >
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($fabricChekingMaster[0]->chk_date)) }}</h6>
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
                  background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               @if(count($fabricChekingMaster)>0)
               @foreach($fabricChekingMaster as $rowMaster)
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Chk No:  </b> <span style="display: inline-block;text-align: right;"> {{ $rowMaster->chk_code }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Chk Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $rowMaster->chk_date }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">GRN No:  </b> <span style="display: inline-block;text-align: right;"> {{ $rowMaster->in_code }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Invoice No:  </b> <span style="display: inline-block;text-align: right;"> {{ $rowMaster->invoice_no }} </span></br>  
                        <b style="display: inline-block;text-align: left;" class="mt-1">Invoice Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $rowMaster->invoice_date }} </span></br>  
                     </div>
                     <div  class="col-md-3" >
                     </div>
                     <div  class="col-md-5">         
                        <b style="display: inline-block;text-align: left;" class="mt-1">Supplier: </b>  <span style="display: inline-block;text-align: right;">{{  $rowMaster->Ac_name }} </span></br>
                        <!--<b style="display: inline-block;text-align: left;" class="mt-1">PO No:</b>  <span style="display: inline-block;text-align: right;"></span></br>-->
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Checking Details</h4>
               <div class="">
                  <hr>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr>
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Roll No</th>
                           <th>Fabric Color Code</th>
                        
                           <th>Actual Width</th> 
                           <th>Supplier Roll No</th>
                           <th>Quality</th>
                           <th>GRN Meter</th>
                           <th>QC Meter</th>
                           <th>Short</th>
                           <th>Excess</th>
                           <th>Kg</th>
                           <th>Shade</th>
                           <th>Status</th>
                           <th>Defect</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::
                        leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                        ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                        ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                        ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                        ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                        ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                        ->whereIn('fabric_checking_details.status_id',   [1,2])
                        ->get(['fabric_checking_details.*','fabric_check_status_master.fcs_name',
                        'item_master.item_description','item_master.item_name','item_master.item_description',
                        'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name']);
                        $totalPassed=0; $totalExtra=0; $totalshort=0;
                        $no=1; @endphp
                        @foreach($FabricChekingdetailslists as $rowDetail)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td>{{ $rowDetail->track_code }}</td>
                           <td>{{ $rowDetail->item_name }}</td>
                           <td>{{ $rowDetail->width }}</td>
                           <td>{{ $rowDetail->roll_no }}</td>
                           <td>{{ $rowDetail->item_description }}</td>
                           <td>{{ $rowDetail->old_meter }}</td>
                           <td>{{ $rowDetail->meter }}</td>
                           <td>{{ $rowDetail->reject_short_meter }}</td>
                           <td>{{ $rowDetail->extra_meter }}</td>
                           <td>{{ $rowDetail->kg }}</td>
                           <td>{{ $rowDetail->shade_name }}</td>
                           <td>{{ $rowDetail->fcs_name }}</td>
                           <td>{{ $rowDetail->fabricdefect_name }}</td>
                        </tr>
                        @php $no=$no+1; 
                        $totalPassed=$totalPassed+$rowDetail->meter;
                         $totalExtra=$totalExtra + $rowDetail->extra_meter;
                         $totalshort= $totalshort + $rowDetail->reject_short_meter;
                        
                        @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <td colspan="6"><b>Checker Name:{{ $rowMaster->in_narration }} </b></td>
                           <td  ><b>Total</b></td>
                           <td>{{ $rowMaster->total_meter }}</td>
                           <td> {{$totalPassed}}</td>
                            <td>{{$totalshort}} </td> 
                            <td>{{$totalExtra}} </td>
                           <td>{{ $rowMaster->total_kg }}</td>
                           <td colspan="5"></td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Checking Details: (Rejected)</h4>
               <div class="">
                  <hr>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr>
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Roll No</th>
                           <th>Fabric Color Code</th>
                           <th>Actual Width</th>
                           <th>Quality</th>
                           <th>GRN Meter</th>
                           <th>QC Meter</th>
                           <th>Short</th>
                           <th>Excess</th>
                           <th>Kg</th>
                           <th>Shade</th>
                           <th>Status</th>
                           <th>Defect</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::
                        leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                        ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                        ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                        ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                        ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                        ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                        ->where('fabric_checking_details.status_id','=',2)
                        ->get(['fabric_checking_details.*','fabric_check_status_master.fcs_name',
                        'item_master.item_description','item_master.item_name','item_master.item_description',
                        'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name']);
                        $totalReject=0;
                        $no=1; @endphp
                        @foreach($FabricChekingdetailslists as $rowDetail)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td>{{ $rowDetail->track_code }}</td>
                           <td>{{ $rowDetail->item_name }}</td>
                           <td>{{ $rowDetail->width }}</td>
                           <td>{{ $rowDetail->item_description }}</td>
                           <td>{{ $rowDetail->old_meter }}</td>
                           <td>{{ $rowDetail->meter }}</td>
                           <td>{{ $rowDetail->reject_short_meter }}</td>
                           <td>{{ $rowDetail->extra_meter }}</td>
                           <td>{{ $rowDetail->kg }}</td>
                           <td>{{ $rowDetail->shade_name }}</td>
                           <td>{{ $rowDetail->fcs_name }}</td>
                           <td>{{ $rowDetail->fabricdefect_name }}</td>
                        </tr>
                        @php 
                        $no=$no+1;
                        $totalReject=$totalReject+$rowDetail->meter;
                        @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <td colspan="8"><b>Checker Name:{{ $rowMaster->in_narration }} </b></td>
                           <td >Received Total: {{$rowMaster->total_meter}}</td>
                           <td >Passed Meter: {{$totalPassed - $totalReject}}</td>
                           <td >Rejected Meter: {{$totalReject}}</td>
                           <td colspan="2" ><b>Total Pass  </b>: {{round((($totalPassed/$rowMaster->total_meter)*100),2) - round((($totalReject/$rowMaster->total_meter)*100),2)}}%</td>
                           <td  colspan="2"><b>Total Reject  </b>: {{round((($totalReject/$rowMaster->total_meter)*100),2)}}%</td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <!-- Details -->
               <div class="col-md-12">
                 <div class="row">
                   <div class="col-md-6">
                      <h4 class="text-center  " style="color: #000;font-size:20px;font-weight:bold;">Shade Summary</h4>
                      <hr>
                      <table class="table table-bordered text-1 table-sm" style="height:10vh;  ">
                         <thead>
                            <tr>
                               <th>SrNo</th>
                               <th>Shade</th>
                               <th>Meter</th>
                               <th>Roll No</th>
                            </tr>
                         </thead>
                         <tbody style="word-break: break-word;">
                            @php
                            
                            $FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::select('fabric_checking_details.*','fabric_check_status_master.fcs_name',
                                DB::raw('sum(fabric_checking_details.meter) as summeter') ,'fabric_checking_details.shade_id',  DB::raw('GROUP_CONCAT(fabric_checking_details.track_code SEPARATOR "  ") as track_codes'),
                                'item_master.item_description','item_master.item_name','item_master.item_description',
                                'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name','fabric_defect_master.fabricdefect_name')
                                ->leftJoin('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
                                ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
                                ->leftJoin('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
                                ->leftJoin('fabric_defect_master','fabric_defect_master.fdef_id', '=', 'fabric_checking_details.defect_id')
                                ->leftJoin('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
                                ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                                ->whereIn('fabric_checking_details.status_id',   [1,2])
                                ->groupby('fabric_checking_details.shade_id')
                                ->get();
                            
                            $totalsum=0;
                            $no=1; @endphp
                            @foreach($FabricChekingdetailslists as $rowDetail)  
                           
                            <tr>
                               <td style="vertical-align: middle;">{{ $no }}</td>
                               <td style="vertical-align: middle;padding-left: 8%;">{{ $rowDetail->shade_name }}</td>
                               <td style="vertical-align: middle;">{{ $rowDetail->summeter }}</td>
                               <td style="vertical-align: middle;">{{ $rowDetail->track_codes}}</td>
                            </tr>
                            @php 
                                $no=$no+1; 
                                $totalsum=$totalsum+$rowDetail->summeter;
                            @endphp
                            @endforeach
                         </tbody>
                         <tfoot>
                            <tr>
                               <td ></td>
                               <td nowrap><b>Total Meter</b></td>
                               <td > {{$totalsum}}</td>
                               <td ></td>
                            </tr>
                         </tfoot>
                      </table>
                   </div>
                   @endforeach
                   @endif
                    <div class="col-md-6">
                      <h4 class="text-center  " style="color: #000;font-size:20px;font-weight:bold;">Width Summary</h4>
                      <hr>
                      <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="width_Summary">
                         <thead>
                            <tr>
                               <th>SrNo</th>
                               <th nowrap>Actual Width</th>
                               <th>Meter</th>
                               <th>No. Of Rolls</th>
                            </tr>
                         </thead>
                         <tbody >
                            @php
                              $no=1; 
                              $totalsum=0;
                              $FabricActual_Width = App\Models\FabricCheckingDetailModel::select('fabric_checking_details.*',
                                DB::raw('GROUP_CONCAT(fabric_checking_details.track_code SEPARATOR "  ") as track_codes'), 
                                DB::raw('sum(fabric_checking_details.meter) as totalMeter'))
                                ->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
                                ->groupby('fabric_checking_details.width')
                                ->get();
                            @endphp
                            @foreach($FabricActual_Width as $rowDetail)  
                            <tr>
                               <td style="vertical-align: middle;">{{ $no }}</td>
                               <td style="vertical-align: middle;">{{ $rowDetail->width }}</td>
                               <td style="vertical-align: middle;"  class="text-right">{{$rowDetail->totalMeter}}</td>
                               <td style="vertical-align: middle;">{{$rowDetail->track_codes}}</td>
                            </tr>
                            @php 
                                $no=$no+1; 
                                $totalsum=$totalsum+$rowDetail->totalMeter;
                            @endphp
                            @endforeach
                            <tfoot>
                                <th></th>
                                <th class="text-right">Total : </th>
                                <th class="text-right">{{$totalsum}}</th>
                                <th></th>
                            </tfoot>
                         </tbody>
                      </table>
                   </div>
                </div>
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
         </main>
         </div>
      </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   <script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js')}}"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
//   $(document).ready(function(){
//             var result = [];
//             $('#width_Summary tr').each(function(){
//               $('td', this).each(function(index, val)
//               {
//                   if(!result[index]) result[index] = 0;
//                   result[index] += parseFloat($(val).text());
//               });
//             });
//             result.shift();
//             result.shift();
//             $('#width_Summary').append('<tr><td colspan="2" class="text-right"><strong>Total : </strong></td></tr>');
//             $(result).each(function(){
                
//               $('#width_Summary tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
//             });
//       });
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Fabric Checking Details Report.' + type);
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
   </body>
</html>