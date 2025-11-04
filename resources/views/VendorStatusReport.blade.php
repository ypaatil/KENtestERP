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
                     <h4 class="mb-0" style="font-weight:bold; text-center">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
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
                  background-image: url('http://kenerp.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style=" text-align: left;" class="mt-1"><b>Vendor Name:</b>  <span style="display: inline-block;text-align: right;">{{$LedgerList[0]->ac_name}}</span></br>
                        @php 
                        if(isset($fdate) && isset($tdate))
                        {
                        @endphp
                        <b style=" ;text-align: left;" class="mt-1"> From: </b> {{ date('d-m-Y',strtotime($fdate)) }} <b> To:</b> {{ date('d-m-Y',strtotime($tdate)) }} </br>     
                        @php 
                        } 
                        @endphp  
                     </div>
                     <div  class="col-md-3" >
                     </div>
                     <div  class="col-md-5">         
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Vendor Status Report</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="text-align:center;">
                           <th>Department</th>
                           <th>Line</th>
                           <th>Line Eff %</th>
                           <th>Qty</th>
                           <th>Produced Min</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td colspan="3"><b>Cutting:</b></td>
                        </tr>
                        <tr>
                           @php
                           
                           $totalQty=0;
                           $totalLineEff = 0;
                           $totalProducedMin= 0;
                           
                           $CuttingGRN=DB::select("select sum(size_qty_total) as  qty from cut_panel_grn_detail where vendorId='".$vendorId."' 
                           and cpg_date between '".$fdate."' and '".$tdate."'");
                           
                           @endphp
                           <td>-</td>
                           <td>N/A</td>
                           <td>-</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$CuttingGRN[0]->qty)}}</td>
                           <td>-</td>
                        </tr>
                        <tr>
                           <td colspan="3"><b>Production:</b></td>
                        </tr>
                        @foreach($LineList as $line)
                        <tr>
                           @php
                           
                           $Stitching=DB::select("select sum(size_qty_total) as  qty, sum(total_workers) as total_workers ,stitching_inhouse_master.sales_order_no from stitching_inhouse_detail
                            INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                            where stitching_inhouse_master.vendorId='".$vendorId."' and 
                            stitching_inhouse_master.sti_date between '".$fdate."' and '".$tdate."' and 
                            stitching_inhouse_master.line_id='".$line->line_id."'");
                           
                            $SAM=DB::select("select sam from sales_order_costing_master where sales_order_no='".$Stitching[0]->sales_order_no."'");
                            
                            $totalWorkers=$Stitching[0]->total_workers ? $Stitching[0]->total_workers : 0;
                        
                            
                            if(count($Stitching) > 0)
                            {
                                $stichingQty = $Stitching[0]->qty;
                            }
                            else
                            {
                                $stichingQty = 0;
                            }
                            
                            if(count($SAM) > 0)
                            {
                                $SAM = $SAM[0]->sam;
                            }
                            else
                            {
                                $SAM = 0;
                            }
                            
                            if($totalWorkers > 0)
                            {
                                $TotalOperator = (($stichingQty * $SAM)/($totalWorkers * 480))*100;
                            }
                            else
                            {
                                $TotalOperator = 0;
                            }
                           @endphp
                           <td>-</td>
                           <td>{{$line->line_name}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$TotalOperator)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$stichingQty)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$stichingQty * $SAM)}}</td>
                           @php 
                           $totalQty=$totalQty + $stichingQty;
                           $totalLineEff = $totalLineEff + $TotalOperator;
                           $totalProducedMin = $totalProducedMin + $stichingQty * $SAM;
                           @endphp 
                        </tr>
                        @endforeach
                        <tr>
                           <td>-</td>
                           <td><b>Total</b></td>
                           <td  style="text-align:right;">{{money_format('%!.0n',$totalLineEff,)}}</td>
                           <td style="text-align:right;"><b>{{money_format('%!.0n',$totalQty)}}</b></td>
                           <td  style="text-align:right;">{{money_format('%!.0n',$totalProducedMin,)}}</td>
                        </tr>
                        <tr>
                           <td colspan="3"><b>QC-Rejection:</b></td>
                        </tr>
                        <tr>
                           @php
                           $QCStitching=DB::select("select sum(size_qty_total) as  qty from qcstitching_inhouse_reject_detail
                           INNER JOIN qcstitching_inhouse_master on qcstitching_inhouse_master.qcsti_code=qcstitching_inhouse_reject_detail.qcsti_code
                           where qcstitching_inhouse_master.vendorId='".$vendorId."' and 
                           qcstitching_inhouse_master.qcsti_date between '".$fdate."' and '".$tdate."'");
                           @endphp
                           <td>-</td>
                           <td>N/A</td>
                           <td>-</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$QCStitching[0]->qty)}}</td>
                           <td>-</td>
                        </tr>
                        <tr>
                           <td colspan="3"><b>Finishing:</b></td>
                        </tr>
                        <tr>
                           @php
                           $Finishing=DB::select("select sum(size_qty_total) as  qty from finishing_inhouse_detail
                           where finishing_inhouse_detail.vendorId='".$vendorId."' and 
                           finishing_inhouse_detail.fns_date between '".$fdate."' and '".$tdate."'");
                           @endphp
                           <td>-</td>
                           <td>N/A</td>
                           <td>-</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$Finishing[0]->qty)}}</td>
                           <td>-</td>
                        </tr>
                        <tr>
                           <td colspan="3"><b>Packing:</b></td>
                        </tr>
                        <tr>
                           @php
                           $Packing=DB::select("select sum(size_qty_total) as  qty from packing_inhouse_detail
                           where packing_inhouse_detail.vendorId='".$vendorId."' and 
                           packing_inhouse_detail.pki_date between '".$fdate."' and '".$tdate."'");
                           @endphp
                           <td>-</td>
                           <td>N/A</td>
                           <td>-</td>
                           <td style="text-align:right;"> {{money_format('%!.0n',$Packing[0]->qty)}}</td>
                           <td>-</td>
                        </tr>
                     </tbody>
                     </tbody>
                  </table>
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
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
    
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Vendor Status Report.' + type);
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