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
                  background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Vendor Name:  </b> <span style="display: inline-block;text-align: right;"> {{$LedgerList[0]->ac_name}}  </span></br>     
                     </div>
                     <div  class="col-md-3" >
                     </div>
                     <div  class="col-md-5">       
                        <b style="display: inline-block;text-align: left;" class="mt-1">
                        @php 
                        if(isset($fdate) && isset($tdate))
                        {
                        @endphp     
                             From:  </b>  <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($fdate)) }}  <b>To: </b> {{ date('d-m-Y',strtotime($tdate)) }}  </span></br>     
                        @php 
                        } 
                            $totalQty = 0;
                            $totalSAM = 0;
                            $totalCountSAM = 0;
                            $totalProducedMin = 0;
                            $totalProducedMin1 = 0;
                            $totalPCS1 = 0;
                            $totalMonthPcs = 0;
                            $totalMinMonth = 0;
                            $totalLineEff= 0;
                            $totalEffMonth = 0;
                            $allTotalWorkers = 0;
                            $totalAvaliableMins = 0;
                            $totalWorkers = 0;  
                        @endphp
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Vendor Production Status Report</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr>
                           <th colspan="8"  > </th>
                           <th colspan="2"  style="text-align:center;">Cumulative</th>
                        </tr>
                        <tr style="text-align:center; border: 1px  solid;">
                           <th rowspan="2">Department</th>
                           <th rowspan="2">Line</th>
                           <th rowspan="2">Total O/P</th>
                           <th rowspan="2">SAM</th>
                           <th rowspan="2">Total Operator</th>
                           <th rowspan="2">Produced Min</th>
                           <th rowspan="2">Total min available</th>
                           <th rowspan="2">Line Eff %</th>
                        </tr>
                        <tr>
                           <th>Pcs</th>
                           <th>Produced Min  </th>
                           
                        </tr>
                     </thead>
                     <tbody>
                        @php
                       
                        if($line_id != "")
                        {
                            $lineData = " AND stitching_inhouse_master.line_id=".$line_id;
                        }
                        else
                        {
                            $lineData = "";
                        }
                         //echo $lineData;exit;
                       // DB::enableQueryLog();
                        $Stitching=DB::select("select stitching_inhouse_master.sti_date,stitching_inhouse_master.line_id,stitching_inhouse_master.vendorId, 
                        sum(total_qty) as  qty, stitching_inhouse_master.sales_order_no,line_master.line_name, sum(total_workers)  as total_workers,
                        
                        (select sum(size_qty_total) from stitching_inhouse_detail 
                        INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                        inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                        where stitching_inhouse_master.vendorId='".$vendorId."'  ".$lineData." and 
                        stitching_inhouse_master.sti_date ='".date('Y-m-d')."') as TodaysPcs ,
                        
                        (select sum(total_workers) from stitching_inhouse_detail 
                        INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                        inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                        where stitching_inhouse_master.vendorId='".$vendorId."' ".$lineData." and 
                        stitching_inhouse_master.sti_date ='".date('Y-m-d')."') as Todaysworkers
                        
                        from stitching_inhouse_master
                        inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                        where stitching_inhouse_master.vendorId='".$vendorId."' ".$lineData." and 
                        stitching_inhouse_master.sti_date between '".$fdate."' and '".$tdate."'  
                        group by stitching_inhouse_master.sti_date,stitching_inhouse_master.line_id 
                        order by stitching_inhouse_master.sti_date,stitching_inhouse_master.line_id
                        ");
                        $no=1;
                      //  dd(DB::getQueryLog());
                        @endphp
                        @foreach($Stitching as $line)
                        @php  
                           
                        $SAM=DB::select("select sam from buyer_purchse_order_master where tr_code='".$line->sales_order_no."'"); 
                        $totalWorkers=$line->total_workers ? $line->total_workers : 0;
                        //echo $totalWorkers;
                        $TodaysPcs= $line->TodaysPcs;
                        
                        $firstDate = date('Y-m-01', strtotime( $line->sti_date));
                        
                        $QtyData = DB::select("select stitching_inhouse_master.sti_date, 
                            sum(size_qty_total) as  qty from stitching_inhouse_detail
                            INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                            inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                            where stitching_inhouse_master.vendorId='".$line->vendorId."' and stitching_inhouse_master.line_id='".$line->line_id."' and 
                            stitching_inhouse_master.sti_date between '".$firstDate."' and '".$line->sti_date."'");
                 
                    
                    //DB::enableQueryLog();

                        $StichingData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                            from stitching_inhouse_size_detail2
                            INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                            where stitching_inhouse_size_detail2.vendorId='".$line->vendorId."' and stitching_inhouse_size_detail2.line_id='".$line->line_id."' and 
                            stitching_inhouse_size_detail2.sti_date = '".$line->sti_date."'");
                       // dd(DB::getQueryLog());
                        if(count($StichingData) > 0)
                        {
                            $totalPMin = $StichingData[0]->total_min;
                            
                        }
                        else
                        {
                            $totalPMin = 0;
                            
                        }
                        if($totalPMin > 0 && $line->qty > 0)
                        {
                            $avgSAM = $totalPMin/$line->qty;
                        }
                        else
                        {
                            $avgSAM = 0;
                        }
                                   
                        if($totalWorkers > 0)
                        {
                            $TotalOperator = money_format('%!.0n', round((($totalPMin)/($totalWorkers * 480)),2)*100);
                            $EffMonth = money_format('%!.0n',round((($QtyData[0]->qty * $SAM[0]->sam)/($totalWorkers * 480)),2)*100);
                            $totalLEff = (round((($line->qty * $SAM[0]->sam)/($totalWorkers * 480)),2)*100);
                            $totalEMonth = round((($QtyData[0]->qty * $SAM[0]->sam)/($totalWorkers * 480)),2)*100;
                        }
                        else
                        {
                            $TotalOperator = 0;
                            $EffMonth = 0;
                            $totalLEff = 0;
                            $totalEMonth= 0;
                        }
                        $totalProducedMin1 = $totalProducedMin1 + ($totalPMin);
                        $totalQty = $totalQty + $line->qty;
                        @endphp
                        <tr style="  border: 1px  solid;">
                           <td>{{date('d-m-Y',strtotime($line->sti_date))}}</td>
                           <td nowrap>{{$line->line_name}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$line->qty)}}</td>
                           <td style="text-align:right;">{{round($avgSAM,2)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$totalWorkers)}}</td>
                    
                           <td style="text-align:right;">{{money_format('%!.0n',$totalPMin)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$totalWorkers * 480)}}</td>
                           <td style="text-align:right;">{{$TotalOperator}}</td>
                           @if($TodaysPcs!=0 && $SAM[0]->sam !=0 && $line->Todaysworkers!=0)
                           <td style="text-align:right;">{{money_format('%!.0n',$TodaysPcs)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$line->qty)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$TodaysPcs * $SAM[0]->sam)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$line->qty * $SAM[0]->sam)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',round((($TodaysPcs * $SAM[0]->sam)/($line->Todaysworkers * 480)),2)*100)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',round((($line->qty * $SAM[0]->sam)/($totalWorkers * 480)),2)*100)}}</td>
                           @else
                           <td style="text-align:right;">{{money_format('%!.0n',$totalQty)}}</td>
                           <td style="text-align:right;">{{money_format('%!.0n',$totalProducedMin1)}}</td>
                            
                           @endif
                           @php 
                           $totalSAM = $totalSAM + $SAM[0]->sam;
                           $totalProducedMin = $totalProducedMin + $totalPMin;
                           $totalMonthPcs = $totalMonthPcs + $QtyData[0]->qty;
                           $totalMinMonth = $totalMinMonth + ($QtyData[0]->qty * $SAM[0]->sam);
                           $totalLineEff = $totalLineEff + $totalLEff;
                           $totalEffMonth = $totalEffMonth + $totalEMonth;
                           $allTotalWorkers = $allTotalWorkers + $totalWorkers;
                           $totalAvaliableMins = $totalAvaliableMins + $totalWorkers * 480;
                           $totalCountSAM = count($Stitching);
                           
                           $totalWorkers = 0;  
                           $no=$no+1;
                           @endphp 
                        </tr>
                        @endforeach
                     </tbody>
                     <tfoot>  
                        <tr>    
                            <th colspan="2" class="text-right">Total : </th>
                            <th class="text-right">{{money_format('%!.0n',$totalQty)}}</th>
                            @php
                                if($totalSAM > 0 && $totalCountSAM > 0)
                                {
                                    $totalOp = number_format(($totalSAM/$totalCountSAM),2);
                                }
                                else
                                {
                                    $totalOp = 0;
                                }
                                
                                if($totalProducedMin > 0 && $totalAvaliableMins > 0)
                                {
                                    $tlf = number_format(($totalProducedMin/$totalAvaliableMins) * 100,2);
                                }
                                else
                                {
                                    $tlf = 0;
                                }
                            @endphp
                            <th class="text-right">{{$totalOp}}</th>
                            <th class="text-right">{{money_format('%!.0n',$allTotalWorkers)}}</th>
                             <th class="text-right">{{money_format('%!.0n',$totalProducedMin)}}</th>
                            <th class="text-right">{{money_format('%!.0n',$totalAvaliableMins)}}</th>
                            <th class="text-right">{{round($tlf,2)}}</th>
                            <th class="text-right">-</th>
                            <th class="text-right">-</th>
                             
                        </tr>
                     </tfoot>
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
      <p class="text-center d-print-none"><a href="GetDailyProductionReport">	Back To Filter </a>></p>
   </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Vendor Production Status Report.' + type);
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