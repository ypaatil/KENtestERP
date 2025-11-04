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
          table{
          display: table;
          width:100%;
          border-collapse:collapse;
          }
          tr {
          display: table-row;
          padding: 2px;
          }
          tr p {
          margin: 0px !important; 
          }
          td,th {
          display: table-cell;
          padding: 8px;
          width: 410px;
          border: #000000 solid 1px;
          font-size:14px !important;
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
          
          .invoice-container{
                  border: none;
          }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <a  href="javascript:void(0)" id="printPage" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Work Order Deviation Report</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; " id="recTbl">
                         <thead>
                              <tr>
                                 <th colspan="5"></th>  					
                                 <th class="text-center" colspan="{{count($sizeData) + 1}}" style="background: #d3d2ef;">Cutting Issue To Line</th>
                                 <th class="text-center" colspan="{{count($sizeData) + 1}}" style="background: #d78d59c9;">Stiching Qty</th>  
                                 <th class="text-center" colspan="{{count($sizeData) + 1}}" style="background: #eaefa2;">Production (Pass Pcs)</th>  					
                                 <th class="text-center" colspan="{{count($sizeData) + 1}}" style="background: #9be17e;"> Production (Rejection Pcs)</th>					
                                 <th class="text-center" colspan="{{count($sizeData) + 1}}" style="background: #efa2a2;"> Deviation</th>
                                 <th></th>
                              <tr>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Sales Order No.</th>
                                   <th nowrap>Work Order No</th>
                                   <th nowrap>Vendor Name</th>
                                   <th nowrap>Garment Color</th>
                                   <th class="text-center" nowrap>Line No.</th>
                                   @foreach($sizeData as $size1)
                                   @php
                              
                                   @endphp
                                      <th class="text-center" nowrap  style="background: #d3d2ef;">{{$size1->size_name}}</th>
                                   @php
                                   @endphp
                                   @endforeach
                                   <th class="text-right" nowrap style="background: #d3d2ef;">Grand Total</th>
                                   @foreach($sizeData as $size5)
                                   @php
                              
                                   @endphp
                                      <th class="text-center" nowrap  style="background: #d78d59c9;">{{$size5->size_name}}</th>
                                   @php
                                   @endphp
                                   @endforeach
                                   <th class="text-right" nowrap style="background: #d78d59c9;">Grand Total</th>
                                   @foreach($sizeData as $size2)
                                   @php
                              
                                   @endphp
                                      <th class="text-center" nowrap  style="background: #eaefa2;">{{$size2->size_name}}</th>
                                   @php
                                   @endphp
                                   @endforeach
                                   <th class="text-right" nowrap  style="background: #eaefa2;">Grand Total</th>
                                   @foreach($sizeData as $size3)
                                   @php
                              
                                   @endphp
                                      <th class="text-right" nowrap style="background: #9be17e;">{{$size3->size_name}}</th>
                                   @php
                                   @endphp
                                   @endforeach
                                   <th class="text-right" nowrap style="background: #9be17e;">Grand Total</th>
                                   @foreach($sizeData as $size4)
                                   @php
                              
                                   @endphp
                                      <th class="text-right" nowrap style="background: #efa2a2;">{{$size4->size_name}}</th>
                                   @php
                                   @endphp
                                   @endforeach 
                                   <th class="text-right" nowrap style="background: #efa2a2;">Grand Total</th>
                                   <th class="text-right" nowrap>Cut To Stitch %</th>
                              </tr>
                         </thead>
                         <tbody>
                            @foreach($CutPanelIssueDetails as $row)
                            <tr>
                               <td nowrap>{{$row->sales_order_no}}</td>
                               <td nowrap>{{$row->vw_code}}</td>
                               <td nowrap>{{$row->vendor_Name}}</td>
                               <td nowrap>{{$row->color_name}} -{{$row->color_id}}</td>
                               <td class="text-center" nowrap>{{$row->line_name}}</td>
                               @php
                                    $totalQty = 0;
                                    $passTotalQty = 0;
                                    $rejectTotalQty = 0;
                                    $deviationTotalQty = 0;
                                    $stichingTotalQty = 0;
                                    $sizeArr1 = array();
                                    $sizeArr2 = array();
                                    $sizeArr3 = array();
                               @endphp
                               @foreach($sizeData as $size1)
                               @php
                        
                                 $sizeList = DB::select("SELECT sum(cut_panel_issue_size_detail2.size_qty) as size_qty FROM `cut_panel_issue_size_detail2` 
                                            WHERE  vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."'
                                            AND size_id=".$size1->size_id." AND color_id=".$row->color_id." 
                                            GROUP BY cut_panel_issue_size_detail2.size_id");
                                            
                                if(count($sizeList) > 0)
                                {
                                    $qty = $sizeList[0]->size_qty;
                                }
                                else
                                {
                                    $qty = "0";
                                }
                                $sizeArr1[] = $qty;
                                $totalQty += $qty;
                               @endphp
                                  <td class="text-right" nowrap style="background: #d3d2ef;">{{money_format('%!.0n',$qty)}}</td>
                               @php
                               @endphp
                               @endforeach
                               <td class="text-right" nowrap style="background: #d3d2ef;">{{money_format('%!.0n',$totalQty)}}</td>
                               
                               @foreach($sizeData as $size5)
                               @php
                                        
                                $StitchingDetails = DB::select("SELECT ifnull(sum(stitching_inhouse_size_detail2.size_qty),0) as 'qty'
                                FROM stitching_inhouse_size_detail2 WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                                AND sales_order_no = '".$row->sales_order_no."' AND size_id=".$size5->size_id." AND color_id=".$row->color_id." 
                                GROUP BY stitching_inhouse_size_detail2.vendorId");
                               
                                if(count($StitchingDetails) > 0)
                                {
                                    $qty5 = $StitchingDetails[0]->qty;
                                }
                                else
                                {
                                    $qty5 = "0";
                                }
                                $stichingTotalQty += $qty5;
                               @endphp
                                     <td class="text-right" nowrap style="background: #d78d59c9;">{{money_format('%!.0n',$qty5)}}</td>
                               @endforeach
                               
                               <td class="text-right" nowrap style="background: #d78d59c9;">{{money_format('%!.0n',$stichingTotalQty)}}</td>
                               
                               @foreach($sizeData as $size1)
                               @php
                                //DB::enableQueryLog();            
                                $QCStitchingDetails = DB::select("SELECT ifnull(sum(qcstitching_inhouse_size_detail2.size_qty),0) as 'qty'
                                FROM qcstitching_inhouse_size_detail2 WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                                AND sales_order_no = '".$row->sales_order_no."' AND size_id=".$size1->size_id." AND color_id=".$row->color_id." 
                                GROUP BY qcstitching_inhouse_size_detail2.size_id");
                               
                                if(count($QCStitchingDetails) > 0)
                                {
                                    $qty1 = $QCStitchingDetails[0]->qty;
                                }
                                else
                                {
                                    $qty1 = "0";
                                }
                                $sizeArr2[] = $qty1;
                                $passTotalQty += $qty1;
                               @endphp
                                     <td class="text-right" nowrap style="background: #eaefa2;">{{money_format('%!.0n',$qty1)}}</td>
                               @endforeach
                               
                               <td class="text-right" nowrap style="background: #eaefa2;">{{money_format('%!.0n',$passTotalQty)}}</td>
                               
                                @foreach($sizeData as $size2)
                                @php
                               
                                //DB::enableQueryLog();             
                                
                                $QCStitchingDetails1 = DB::select("SELECT ifnull(sum(qcstitching_inhouse_size_reject_detail2.size_qty),0) as 'rejectQty'
                                    FROM qcstitching_inhouse_size_reject_detail2 WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                                    AND sales_order_no = '".$row->sales_order_no."' AND size_id=".$size2->size_id." AND color_id=".$row->color_id." 
                                    GROUP BY qcstitching_inhouse_size_reject_detail2.size_id");
                                
                                //$QCStitchingDetails1 = DB::select("SELECT ifnull((select sum (qcstitching_inhouse_size_reject_detail2.size_qty) from 
                                //qcstitching_inhouse_size_reject_detail2 where qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code
                               // and qcstitching_inhouse_size_reject_detail2.color_id=qcstitching_inhouse_size_detail2.color_id  
                               // and qcstitching_inhouse_size_reject_detail2.size_id=qcstitching_inhouse_size_detail2.size_id),0) as rejectQty
                               // FROM qcstitching_inhouse_size_detail2 WHERE  vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                               // AND sales_order_no = '".$row->sales_order_no."' AND size_id=".$size2->size_id);
                                //  dd(DB::getQueryLog());
                                if(count($QCStitchingDetails1) > 0)
                                {
                                    $qty2 = $QCStitchingDetails1[0]->rejectQty;
                                }
                                else
                                {
                                    $qty2 = "0";
                                }
                                $sizeArr3[] = $qty2;
                                $rejectTotalQty += $qty2;
                               @endphp
                                     <td class="text-right" nowrap style="background: #9be17e;">{{money_format('%!.0n',$qty2)}}</td>
                               @endforeach
                               <td class="text-right" nowrap style="background: #9be17e;">{{money_format('%!.0n',$rejectTotalQty)}}</td>
                                @foreach($sizeData as $key=>$val)
                                @php
                                
                                    $qty3 = $sizeArr1[$key] - $sizeArr2[$key] - $sizeArr3[$key];
                                    $deviationTotalQty += $qty3;
                               @endphp
                                     <td class="text-right" nowrap style="background: #efa2a2;">{{money_format('%!.0n',$qty3)}}</td>
                               @endforeach
                               <td class="text-right" nowrap style="background: #efa2a2;">{{money_format('%!.0n',$deviationTotalQty)}}</td>
                               <td class="text-right" nowrap>@if($rejectTotalQty!=0 && $passTotalQty!=0){{round($rejectTotalQty/$passTotalQty,2)}} @else 0 @endif</td>
                            </tr>
                            @php
                                $qty3 = 0;
                            @endphp
                            @endforeach
                         </tbody>
                      </table>
                  </div>
                   <div class="col-md-2"></div>
                   <div class="col-md-8">
                       <center>
                          <h4 class="mb-0">Summary</h4>
                       </center>
                      <table class="table" style="height:10vh;" id="summaryTbl">
                         <thead>
                              <tr>					
                                 <th class="text-center">Garment Color</th>
                                 <th class="text-center" >Cutting Issue To Line</th>  					
                                 <th class="text-center">Production(Pass Pcs) </th>					
                                 <th class="text-center">Production (Rejection Pcs) </th>			
                                 <th class="text-center">Total Production (Pass + Reject)</th>
                                 <th> Deviation </th>
                                 <th> Cut To Stitch %  </th>
                              <tr>
                         </thead>
                         <tbody>
                            @php
                                 $totalRej = 0;
                                 $rejectQty = 0;
                            @endphp
                            @foreach($CutPanelIssueDetails as $row)
                            @php
                             $sizeList = DB::select("SELECT ifnull(sum(cut_panel_issue_size_detail2.size_qty),0) as cut_qty FROM `cut_panel_issue_size_detail2` 
                                            WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' AND color_id=".$row->color_id."
                                            GROUP BY cut_panel_issue_size_detail2.color_id");
                                            
                                if(count($sizeList) > 0)
                                {
                                    $qty = $sizeList[0]->cut_qty;
                                }
                                else
                                {
                                    $qty = "0";
                                }
                                
                                $QCStitchingDetails = DB::select("SELECT ifnull(sum(qcstitching_inhouse_size_detail2.size_qty),0) as pass_qty
                                    FROM qcstitching_inhouse_size_detail2 WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                                    AND sales_order_no = '".$row->sales_order_no."' AND color_id=".$row->color_id."
                                    GROUP BY qcstitching_inhouse_size_detail2.color_id");
                               
                                if(count($QCStitchingDetails) > 0)
                                {
                                    $passQty = $QCStitchingDetails[0]->pass_qty;
                                }
                                else
                                {
                                    $passQty = "0";
                                }
                                
                                $QCStitchingDetails1 = DB::select("select sum(qcstitching_inhouse_size_reject_detail2.size_qty) as rejectQty from 
                                    qcstitching_inhouse_size_reject_detail2 WHERE vendorId = ".$row->vendorId." AND vw_code = '".$row->vw_code."' 
                                    AND sales_order_no = '".$row->sales_order_no."' AND color_id=".$row->color_id."
                                    GROUP BY qcstitching_inhouse_size_reject_detail2.color_id");
                                
                               if(count($QCStitchingDetails1) > 0)
                                {
                                    $rejectQty = $QCStitchingDetails1[0]->rejectQty;
                                }
                                else
                                {
                                    $rejectQty = "0";
                                }
                            @endphp
                            <tr>
                               <td nowrap>{{$row->color_name}}</td>
                               <td class="text-right" nowrap>{{money_format('%!.0n',$qty)}}</td>
                               <td class="text-right" nowrap>{{money_format('%!.0n',$passQty)}}</td>
                               <td class="text-right" nowrap>{{money_format('%!.0n',$rejectQty)}}</td>
                               <td class="text-right" nowrap>{{money_format('%!.0n',$passQty + $rejectQty)}}</td>
                               <td class="text-right" nowrap>{{money_format('%!.0n',$qty - ($passQty + $rejectQty))}}</td>
                               <td class="text-right" nowrap>@if($qty!=0 &&  ($passQty + $rejectQty)!=0)  {{number_format(($qty/($passQty + $rejectQty)),2)}} @else 0 @endif</td>
                            </tr>
                            @endforeach
                         </tbody>
                      </table>
                  </div>
                  <div class="col-md-2"></div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Work Order Deviation Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   
   
  $('#printPage').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Work Order Deviation Report.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     $(document).ready(function(){
            var result = [];
            $('#recTbl tr').each(function(){
              $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
              });
            });
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            $('#recTbl').append('<tr><td colspan="5" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
              $('#recTbl tr').last().append('<td class="text-right"><strong>'+res1+'</strong></td>')
            });
      });
            
     $(document).ready(function(){
            var result = [];
            $('#summaryTbl tr').each(function(){
              $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
              });
            });
            result.shift();
            $('#summaryTbl').append('<tr><td colspan="1" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
              $('#summaryTbl tr').last().append('<td class="text-right"><strong>'+res1+'</strong></td>')
            });
      });
   </script>
</html>