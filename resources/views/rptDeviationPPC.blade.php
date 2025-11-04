<!DOCTYPE html>
<html lang="en">
   <head>
       @php setlocale(LC_MONETARY, 'en_IN');  @endphp
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
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">DEVIATION PPC REPORT</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="row">
                @php
                    $tbl_no = 1;
                    $tbl_cnt = 0;
                    $planArr = array();
                    $actArr = array();
                    $devArr = array();
                    $totalActualArr1 = array();
                    $totalDeviationArr1 = array();
                    $totalPlanArr1 = array();
                    $minPlanArr1 = array();
                    $sumMinPlanArr1 = array();
                    $holidayCnt = 0;   
                    $noOfMC = 0;
                    $efficiency = 0;
                    $temp = "";
                    $single_line_id = 0;
                @endphp
                @foreach($deviationList as $row)
                @php 
                
                   $fdate = $year."-0".$monthId."-01";
                   $tdate = $year."-0".$monthId."-".$days;

                   $Stitching=DB::select("select sum(size_qty_total) as  qty, sales_order_no
                            from stitching_inhouse_detail WHERE sti_date between '".$fdate."' and '".$tdate."' AND vendorId=".$vendorId." AND line_id='".$row->line_id."'");
                            
                    $SAM1=DB::select("select sam from sales_order_costing_master where sales_order_no='".$Stitching[0]->sales_order_no."'"); 
                    if(count($SAM1) > 0)
                    {
                        $sam1 = $SAM1[0]->sam;
                    }
                    else
                    {
                        $sam1 = 0;
                    }
                @endphp
                <div class="col-md-4">
                  <table id="table_{{$tbl_no}}" class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead> 
                        <tr style="background-color:#eee;">
                           <th colspan="6" class="text-center">{{$row->line_name}}</th>
                        </tr>
                        <tr style="background-color:#eee;">
                           <th>Date</th>
                           <th>No of m/cs</th>
                           <th>Efficiency</th>
                           <th>PLANNED</th>
                           <th>ACTUAL</th>
                           <th>DEVIATION</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                       
                            $no = 0;
                            $cnt = 0;
                            $cnt1 = 0; 
                            $totalMC = 0;
                            $totalEffi = 0;
                            $totalActual = 0;
                            $totalDev = 0;  
                            $totalPlan = 0;
                            $actualArr = array(); 
                            $actualArr1[] = array();
                            $TotalMinPlanPerDay = ""; 
                            for($f=$days; $f>=1; $f--)
                            {
                                $fdate = $year."-0".$monthId."-".$f;
                                
                                $Stitching=DB::select("select sum(size_qty_total) as  qty, sales_order_no
                                    from stitching_inhouse_detail WHERE sti_date ='".$fdate."' AND vendorId=".$vendorId." AND line_id='".$row->line_id."'");
                                    
                                $SAM=DB::select("select sam from sales_order_costing_master where sales_order_no='".$Stitching[0]->sales_order_no."'");
                                
                                if(count($SAM) > 0)
                                {
                                    $sam = $SAM[0]->sam;
                                }
                                else
                                {
                                    $sam = 0;
                                }
                                
                                if(($Stitching[0]->qty * $sam) > 0)
                                {
                                    $no++;
                                }
                                
                                if(round($Stitching[0]->qty * $sam,0) == 0)
                                {
                                    $cnt++;
                                }
                                else
                                {
                                    break;                                
                                }
                                
                                
                            }
                             
                           
                            for($i=1; $i<=$days; $i++)
                            {
                             
                                
                                $fdate = $year."-".$monthId."-".$i;
                                
                                $Stitching=DB::select("select sum(size_qty_total) as  qty  from stitching_inhouse_detail WHERE sti_date ='".$fdate."' AND vendorId=".$vendorId." AND line_id='".$row->line_id."'");
                                    
                                $Stitching2=DB::select("select sales_order_no  from stitching_inhouse_detail WHERE sti_date ='".$fdate."' AND vendorId=".$vendorId." AND line_id='".$row->line_id."'");
                                    
                                $sales_order_no1 = isset($Stitching2[0]->sales_order_no) ? $Stitching2[0]->sales_order_no : "";  
                                $SAM=DB::select("select sam from sales_order_costing_master where sales_order_no='".$sales_order_no1."'"); 
                                if(count($SAM) > 0)
                                {
                                    $sam = $SAM[0]->sam;
                                }
                                else
                                {
                                    $sam = 0;
                                }
                                
                                $monthName = substr($monthName, 0, 3);
                                
                                if(($Stitching[0]->qty * $sam) > 0)
                                {
                                    $no++;
                                }
                                 
                            if($i < 10)
                            {
                                $ib = '0'.$i;
                            }
                            else
                            {
                                $ib = $i;    
                            } 
                            $fullDate = $year."-".$monthId."-".$ib;  
                            //DB::enableQueryLog();
                            $DeviationDetail = DB::select("select * from deviation_ppc_date_wise_mc where noOfMC != '' AND  monthDate = '".$fullDate."' AND line_id='".$row->line_id."' AND vendorId=".$vendorId);
                            //dd(DB::getQueryLog());  
                            $noOfMC = isset($DeviationDetail[0]->noOfMC) ? $DeviationDetail[0]->noOfMC : 0;  
                            $efficiency = isset($DeviationDetail[0]->efficiency) ? $DeviationDetail[0]->efficiency : 0;  
                            $actualArr[] =  $Stitching[0]->qty * $sam;
                            $planned = 0;
                            //if(in_array( $ib ,$dateSun ) )
                           // {  
                        @endphp
                        <!--<tr style="background-color:yellow">-->
                        <!--   <td>{{$i.'/'.$monthName}}</td>-->
                        <!--   <td>-</td>-->
                        <!--   <td>-</td>-->
                        <!--   <td class="text-right 1">-</td>-->
                        <!--   <td class="text-right 1">-</td>-->
                        <!--   <td class="text-right 1">-</td>-->
                        <!--</tr>-->
                         @php 
                          //}
                          //else
                          //{ 
                          
                            if((($noOfMC * $efficiency) * 480) > 0)
                            {
                                $cnt1++;
                                $planned = ($noOfMC * $efficiency) * 480;
                            }
                            
                            $single_line_id = $row->line_id;
                            $lsd = new DateTime($fullDate); 
                            $month_lstDate =  $lsd->format('Y-m-t');  
                            ///DB::enableQueryLog();
                            $DeviationDetail1 = DB::select("SELECT count(*) as total_count FROM deviation_ppc_date_wise_mc WHERE vendorId = ".$vendorId." AND line_id = ".$single_line_id." AND noOfMC = '0.00' AND monthDate BETWEEN '2023-".$monthId."-01' AND '".$month_lstDate."'");
                            //dd(DB::getQueryLog()); 
                            $holidayCnt = isset($DeviationDetail1[0]->total_count) ? $DeviationDetail1[0]->total_count : 0; 
                     
                        
                           
                            $actual11 = $Stitching[0]->qty * $sam;
                         @endphp
                         <tr>
                           <td>{{$i.'/'.$monthName}}</td>
                           <td>{{$noOfMC}}</td>
                           <td>{{$efficiency}}</td>
                           <td class="text-right 1">{{number_format(round($planned,0))}}</td>
                           <td class="text-right 1">{{number_format(round($actual11,0))}}</td>
                           <td class="text-right 1">{{number_format(round((($planned) - ($actual11)),0))}}</td>
                        </tr>
                        @php
                            //$noOfMC = 0;
                          //}
                         
                            $totalMC += $noOfMC;
                            $totalEffi += $efficiency;
                            $totalPlan += $planned;
                            $totalActual += round($actual11);
                            $totalDev += (($noOfMC * $efficiency) * 480) - (round($actual11));
                            $sumMinPlanArr1[] = $totalPlan; 
                         } 
                        @endphp
                        <tr>
                            <td></td>
                            <td><b>{{money_format('%!.0n',round($totalMC))}}</b></td>
                            <td><b>{{money_format('%!.0n',round($totalEffi))}}</b></td>
                            <td class="text-right"><b>{{money_format('%!.0n',round($totalPlan))}}</b></td>
                            <td class="text-right"><b>{{ isset($totalActual) ? $totalActual : 0}}</b></td>
                            <td class="text-right"><b>{{money_format('%!.0n',round($totalDev))}}</b></td>
                        </tr>
                        <input type="hidden" value="{{$no}}" id="act_count_{{$tbl_no}}">
                        <input type="hidden" value="{{$row->monthlyPlan}}" id="monthlyPlan_{{$tbl_no}}">
                        <input type="hidden" value="{{$row->day_count}}" id="dayCount_{{$tbl_no}}">
                        <input type="hidden" value="{{$cnt}}" id="askRate_{{$tbl_no}}">
                     </tbody>
                  </table>
                  </div>
                    @php
                        
                        $minPlanArr1[] =  $cnt1;
                         
                        $totalPlanArr1[] = $totalPlan;
                        $totalActualArr1[] = $totalActual;
                        $totalDeviationArr1[] = $totalDev;
                        
                        if ($actualArr) 
                        {
                            $actualArr1[] = $actualArr;
                        }
                        $tbl_no++; 
                        $totalPlan = 0;
                        $totalActual = 0;
                        $totalDev = 0; 
                    @endphp
                    @endforeach  
                <div class="col-md-3">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="table_total">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th colspan="6" class="text-center">Total</th>
                        </tr>
                        <tr style="background-color:#eee;">
                           <th>Date</th>
                           <th>No of m/cs</th>
                           <th>Efficiency</th>
                           <th>PLANNED</th>
                           <th>ACTUAL</th>
                           <th>DEVIATION</th>
                        </tr>
                     </thead>
                     <tbody>
                    @php
                         
                        $overAllMC = 0;
                        $overAllEfficiency = 0;
                        $overAllTotalPlanned = 0;
                        $overAllToalActual = 0;
                        $overAllTotalDeviation = 0;
                        $sumArray = array();

                        foreach ($actualArr1 as $k=>$subArray) {
                          foreach ($subArray as $id=>$value) {
                            isset($sumArray[$id]) || $sumArray[$id] = 0;
                            $sumArray[$id]+=$value;
                          }
                        }
                        
                        for($b=1; $b<=$days; $b++)
                        { 
                        
                            $fullDate = $year."-".$monthId."-".$b; 
                           
                            $TotalDeviationDetail = DB::select("select sum(noOfMC) as totalNoOfMC, sum(efficiency) as totalEfficiency,sum((noOfMC * efficiency) * 480) as planned from deviation_ppc_date_wise_mc 
                                                where line_id IN(".$linesArr.") AND noOfMC != '' AND  monthDate = '".$fullDate."'   
                                                AND vendorId=".$vendorId);
                                   
                            $totMC = isset($TotalDeviationDetail[0]->totalNoOfMC) ? $TotalDeviationDetail[0]->totalNoOfMC : 0;
                            $totEff = isset($TotalDeviationDetail[0]->totalEfficiency) ? $TotalDeviationDetail[0]->totalEfficiency : 0;
                            $totalPlanned = isset($TotalDeviationDetail[0]->planned) ? $TotalDeviationDetail[0]->planned : 0;
                            
                                    
                            $Stitching6=DB::select("select sum(size_qty_total) as  qty1
                                from stitching_inhouse_detail WHERE line_id IN (".$linesArr.") AND sti_date ='".$fullDate."' AND vendorId=".$vendorId);
                              
                            $Stitching5=DB::select("select sales_order_no  from stitching_inhouse_detail WHERE line_id IN (".$linesArr.") AND sti_date ='".$fullDate."' AND vendorId=".$vendorId);
                                     
                            $sales_order_no = isset($Stitching5[0]->sales_order_no) ? $Stitching5[0]->sales_order_no : "";    
                            $SAM1=DB::select("select sam from sales_order_costing_master where sales_order_no='".$sales_order_no."'"); 
                            if(count($SAM1) > 0)
                            {
                                $sam1 = $SAM1[0]->sam;
                            }
                            else
                            {
                                $sam1 = 0;
                            }
                            
                            @endphp
                                <tr>
                                   <td nowrap>{{$fullDate}}</td>
                                   <td>{{ money_format('%!.0n',round($totMC))}}</td>
                                   <td>{{ money_format('%!.0n',round($totEff))}}</td>
                                   <td class="text-right">{{money_format('%!.0n',round($totalPlanned))}}</td> 
                                   <td class="text-right">{{money_format('%!.0n',round($sumArray[$b-1]))}}</td> 
                                   <td class="text-right">{{money_format('%!.0n',round($totalPlanned - $sumArray[$b-1]))}} </td>
                                </tr>
                            @php
                            
                            $overAllMC += $totMC;
                            $overAllEfficiency += $totEff;
                            $overAllTotalPlanned += $totalPlanned;
                            $overAllToalActual += $sumArray[$b-1];
                            $overAllTotalDeviation += $totalPlanned - $sumArray[$b-1];
                        }
                        @endphp
                        <tr>
                            <td></td>
                            <td><b>{{ money_format('%!.0n',round($overAllMC))}}</b></td>
                            <td><b>{{ money_format('%!.0n',round($overAllEfficiency)) }}</b></td>
                            <td><b>{{ money_format('%!.0n',round($overAllTotalPlanned)) }}</b></td>
                            <td><b>{{ money_format('%!.0n',round($overAllToalActual)) }}</b></td>
                            <td><b>{{ money_format('%!.0n',round($overAllTotalDeviation)) }}</b></td>
                        </tr>
                     </tbody>
                  </table>
                </div>
                 @php  
                    $workDays = $d - $holidayCnt;
                   
                 @endphp
                <div class="col-md-12">
                    <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="overAll">
                        <tr>
                            <th></th>
                           @foreach($lineList as $lines)
                                 <th class="text-center">{{$lines->line_name}}</th>
                           @endforeach
                        </tr>
                        <tr>
                            <th>Production Min.Till date</th>
                            @foreach($lineList as $key=>$lines)
                                <td  class="text-right">{{money_format('%!.0n',round($totalActualArr1[$key],2))}}</td>
                             @endforeach
                        </tr>
                        <tr>
                            <th>Min.Planned per day</th>
                            @foreach($lineList as $key=>$lines)
                            @php
                                if($totalPlanArr1[$key] > 0 && $minPlanArr1[$key] > 0)
                                {
                                    $min_plan_per_day = money_format('%!.0n',round($totalPlanArr1[$key]/$minPlanArr1[$key],2));
                                }
                                else
                                {
                                    $min_plan_per_day = 0;
                                }
                            @endphp
                                <td class="text-right">{{$min_plan_per_day}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Planned Qty</th>
                            @foreach($lineList as $key=>$lines)
                                <td   class="text-right">{{money_format('%!.0n',round($totalPlanArr1[$key],2))}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Deviation</th>
                             @foreach($lineList as $key=>$lines)
                                <td id="deviation_{{$b}}" class="text-right">{{money_format('%!.0n',round($totalDeviationArr1[$key],2))}}</td>
                             @endforeach
                        </tr>
                        <tr>
                            <th>Monthly Plan</th>
                            @foreach($lineList as $key=>$lines)
                            @php
                                  $DeviationDetail2 = DB::select("SELECT count(*) as total_count FROM deviation_ppc_date_wise_mc WHERE vendorId = ".$vendorId." AND line_id = ".$lines->line_id." AND noOfMC = '0.00' AND monthDate BETWEEN '2023-".$monthId."-01' AND '".$month_lstDate."'");
                                  $holidayCnt2 = isset($DeviationDetail2[0]->total_count) ? $DeviationDetail2[0]->total_count : 0; 
                                  $workDays2 = $d - $holidayCnt2;
                                  
                                  if($totalPlanArr1[$key] > 0 && $minPlanArr1[$key] > 0)
                                  {
                                        $month_plannerd = money_format('%!.0n',round(($totalPlanArr1[$key]/$minPlanArr1[$key])*$workDays2,2));
                                  }
                                  else
                                  {
                                        $month_plannerd = 0;
                                  }
                            @endphp      
                                <td class="text-right">{{$month_plannerd}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Balance</th>
                            @foreach($lineList as $key=>$lines)
                            @php
                                  $DeviationDetail3 = DB::select("SELECT count(*) as total_count FROM deviation_ppc_date_wise_mc WHERE vendorId = ".$vendorId." AND line_id = ".$lines->line_id." AND noOfMC = '0.00' AND monthDate BETWEEN '2023-".$monthId."-01' AND '".$month_lstDate."'");
                                  $holidayCnt3 = isset($DeviationDetail3[0]->total_count) ? $DeviationDetail3[0]->total_count : 0; 
                                  $workDays3 = $d - $holidayCnt3;
                                  
                                  if($totalPlanArr1[$key] > 0 && $minPlanArr1[$key] > 0)
                                  {
                                        $balanced = $totalPlanArr1[$key]/$minPlanArr1[$key];
                                  }
                                  else
                                  {
                                        $balanced = 0;
                                  }
                            @endphp
                            
                                <td class="text-right">{{money_format('%!.0n',round((($balanced)*$workDays3) - $totalActualArr1[$key],2))}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Avg.</th>
                            @foreach($lineList as $key=>$lines) 
                            @php
                                if($totalActualArr1[$key] > 0 && $minPlanArr1[$key] > 0)
                                {
                                        $avged = money_format('%!.0n',round(($totalActualArr1[$key]/$minPlanArr1[$key]),2));
                                }
                                else
                                {
                                        $avged = 0;
                                }
                            @endphp
                                <td  class="text-right">{{$avged}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>New Asking Rate</th>
                            @foreach($lineList as $key=>$lines) 
                                @php
                                
                                    $DeviationDetail3 = DB::select("SELECT count(*) as total_count FROM deviation_ppc_date_wise_mc WHERE vendorId = ".$vendorId." AND line_id = ".$lines->line_id." AND noOfMC = '0.00' AND monthDate BETWEEN '2023-".$monthId."-01' AND '".$month_lstDate."'");
                                    $holidayCnt = isset($DeviationDetail3[0]->total_count) ? $DeviationDetail3[0]->total_count : 0; 
                                    $workDays = $d - $holidayCnt;
                                  
                                    if($totalPlanArr1[$key] > 0 && $minPlanArr1[$key] > 0)
                                    {
                                            $ask1 = $totalPlanArr1[$key]/$minPlanArr1[$key];
                                    }
                                    else
                                    {
                                            $ask1 = 0;
                                    }
                                    
                                    if(((($ask1)*$workDays) - $totalActualArr1[$key]) > 0 )
                                    {
                                        $ask2 = (($ask1)*$workDays) - $totalActualArr1[$key];
                                    }
                                    else
                                    {
                                         $ask2 = 0;
                                    }
                                    
                                    if(($workDays - $minPlanArr1[$key]) > 0 )
                                    {
                                        $ask3 = $workDays - $minPlanArr1[$key];
                                    }
                                    else
                                    {
                                        $ask3 = 0;
                                    }
                                    
                                    if($ask1 > 0 && $ask2 > 0 &&  $ask3 > 0)
                                    {
                                        $ask4 = $ask2/$ask3;
                                    }
                                    else
                                    {
                                        $ask4 = 0;
                                    }
                                    
                                @endphp
                                    <td  class="text-right">{{money_format('%!.0n',round($ask4,2))}}</td>
                            @endforeach
                        </tr>
                    </table>
                </div>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated DEVIATION PPC REPORT</p>
      <p class="text-center d-print-none"><a href="{{Route('GetDeviationPPC')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   
   $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
     document.getElementById("doPrint").addEventListener("click", function() {
     var printContents = document.getElementById('invoice').innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     });
     
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'DEVIATION PPC REPORT.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
      function changeCurrency(ele)
      {
            var x=Math.round(ele);
            x=x.toString();
            var lastThree = x.substring(x.length-3);
            var otherNumbers = x.substring(0,x.length-3);
            if(otherNumbers != '')
                lastThree = ',' + lastThree;
            var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
                    
            return res;
      }

   </script>
</html>