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
          background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
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
                   
                    $planArr = array();
                    $actArr = array();
                    $devArr = array();
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
                  <table id="table_{{$row->line_id}}" class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                          <tr style="background-color:#eee;">
                           <th rowspan="2">Date</th>
                           <th></th>
                           <th>Efficiency</th>
                           <th>SAM</th>
                           <th></th>
                        </tr>
                        <tr>
                           <td></td>
                           <td>{{$row->efficiency}}</td>
                           <td>{{$sam1}}</td>
                           <td></td>
                        </tr>
                       
                        <tr style="background-color:#eee;">
                           <th colspan="5" class="text-center">{{$row->line_name}}</th>
                        </tr>
                        <tr style="background-color:#eee;">
                           <th></th>
                           <th>No of m/cs</th>
                           <th>PLANNED</th>
                           <th>ACTUAL</th>
                           <th>DEVIATION</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                       
                            $no = 0;
                            $cnt = 0;
                            $noOfMC = 0;
                            
                            if($monthId < 10)
                            {
                                $monthId = '0'.$monthId;
                            }
                            else
                            {
                                $monthId = $monthId;    
                            }
                            
                            for($f=$days; $f>=1; $f--)
                            {
                                $fdate = $year."-0".$monthId."-".$f;
                                
                                $Stitching=DB::select("select sum(size_qty_total) as  qty, sales_order_no
                                    from stitching_inhouse_detail WHERE sti_date ='".$fdate."' AND vendorId=".$vendorId." AND line_id='".$row->line_id."'");
                                    
                                    
                                $SAM=DB::select("select sam from sales_order_costing_master where sales_order_no='".$Stitching[0]->sales_order_no."'");
                                //DB::enableQueryLog();
                               
                               //dd(DB::getQueryLog());
        
        
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
                                $fdate = $year."-0".$monthId."-".$i;
                                
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
                                
                                $monthName = substr($monthName, 0, 3);
                                
                                if(($Stitching[0]->qty * $sam) > 0)
                                {
                                    $no++;
                                }
                                 
                            if($i < 10)
                            {
                                $i = '0'.$i;
                            }
                            else
                            {
                                $i = $i;    
                            }
                            if( in_array( $i ,$dateSun ) )
                            {
                           
                            
                            $fullDate = $year."-".$monthId."-".$i;
                            $DeviationDetail = DB::select("select * from deviation_ppc_date_wise_mc where  monthDate = '".$fullDate."' AND line_id='".$row->line_id."'");
                            $noOfMC = isset($DeviationDetail[0]->noOfMC) ? $DeviationDetail[0]->noOfMC : 0;  
                        @endphp
                        <tr>
                           <td>{{$i.'/'.$monthName}}</td>
                           <td>{{$noOfMC}}</td>
                           <td class="text-right 1">0</td>
                           <td class="text-right 1">0</td>
                           <td class="text-right 1">0</td>
                        </tr>
                         @php
                          }
                          else
                          {
                         @endphp
                         <tr>
                           <td>{{$i.'/'.$monthName}}</td>
                           <td id="mc_{{$row->line_id}}_{{$i}}">{{$noOfMC}}</td>
                           <td id="plan_{{$row->line_id}}_{{$i}}" class="text-right 2">{{number_format(round(($noOfMC * $row->efficiency) * 480,0))}}</td>
                           <td id="act_{{$row->line_id}}_{{$i}}" class="text-right 2">{{number_format(round($Stitching[0]->qty * $sam,0))}}</td>
                           <td id="dev_{{$row->line_id}}_{{$i}}" class="text-right 2">{{number_format(round(($Stitching[0]->qty * $sam) - (($noOfMC * $row->efficiency) * 480),0))}}</td>
                        </tr>
                        @php
                            $noOfMC = 0;
                            }
                         } 
                        @endphp
                        <input type="hidden" value="{{$no}}" id="act_count_{{$row->line_id}}">
                        <input type="hidden" value="{{$row->monthlyPlan}}" id="monthlyPlan_{{$row->line_id}}">
                        <input type="hidden" value="{{$row->day_count}}" id="dayCount_{{$row->line_id}}">
                        <input type="hidden" value="{{$cnt}}" id="askRate_{{$row->line_id}}">
                     </tbody>
                  </table>
                  </div>
                     @endforeach
                <div class="col-md-3">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="table_total">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th colspan="5" class="text-center">Total</th>
                        </tr>
                        <tr style="background-color:#eee;">
                           <th></th>
                           <th>No of m/cs</th>
                           <th>PLANNED</th>
                           <th>ACTUAL</th>
                           <th>DEVIATION</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        
                        for($i=1; $i<=$days; $i++)
                        { 
                            @endphp
                                <tr>
                                   <td>{{$i}}</td>
                                   <td id="total_mc_{{$i}}">0</td>
                                   <td id="total_plan_{{$i}}" class="text-right">0</td>
                                   <td id="total_act_{{$i}}" class="text-right">0</td>
                                   <td id="total_dev_{{$i}}" class="text-right">0</td>
                                </tr>
                            @php
                        }
                        @endphp
                     </tbody>
                  </table>
                </div>
                
                <div class="col-md-6 ml-5">
                    <table class="table table-bordered text-1 table-sm" style="height:10vh;">
                        <tr>
                            <th></th>
                            @for($b=1;$b<=4;$b++)
                                 <th>Line {{$b}}</th>
                            @endfor
                        </tr>
                        <tr>
                            <th>Production Min.Till date</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="act_min_{{$b}}" class="text-right"></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Min.Planned per day</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="plan_min_{{$b}}" class="text-right"></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Planned Qty</th>
                             @for($b=1;$b<=4;$b++)
                                <td id="plan_qty_{{$b}}" class="text-right"></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Deviation</th>
                             @for($b=1;$b<=4;$b++)
                                <td id="deviation_{{$b}}" class="text-right"></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Monthly Plan</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="total_monthly_plan_{{$b}}" class="text-right">-</td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Balance</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="total_bal_{{$b}}" class="text-right">-</td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Avg.</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="total_avg_{{$b}}" class="text-right">-</td>
                            @endfor
                        </tr>
                        <tr>
                            <th>New Asking Rate</th>
                            @for($b=1;$b<=4;$b++)
                                <td id="total_asking_rate_{{$b}}" class="text-right">-</td>
                            @endfor
                        </tr>
                    </table>
                </div>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated DEVIATION PPC REPORT</p>
      <p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   $(function()
   {  
      for(var k=1;k<=30;k++)
      {
           var plan = 0;
           var mc = 0;
           var act = 0;
           var dev = 0;
           var totalPlan = 0;
           var totalMC = 0;
           var totalAct = 0;
           var totalDev = 0;
           
           for(var j=1;j<=4;j++)
           {
              plan = $('#plan_'+j+'_'+k).text().replace(/,/g , '');
              mc = $('#mc_'+j+'_'+k).text().replace(/,/g , '');
              act = $('#act_'+j+'_'+k).text().replace(/,/g , '');
              dev = $('#dev_'+j+'_'+k).text().replace(/,/g , '');
              totalPlan = parseInt(totalPlan ? totalPlan : 0) + parseInt(plan ? plan : 0);
              totalMC = parseInt(totalMC ? totalMC : 0) + parseInt(mc ? mc : 0);
              totalAct = parseInt(totalAct ? totalAct : 0) + parseInt(act ? act : 0);
              totalDev = parseInt(totalPlan ? totalPlan : 0) + parseInt(dev ? dev : 0);
           }
           $('#total_plan_'+k).text(changeCurrency(totalPlan ? totalPlan : 0));
           $('#total_mc_'+k).text(changeCurrency(totalMC ? totalMC : 0));   
           $('#total_act_'+k).text(changeCurrency(totalAct ? totalAct : 0));  
           $('#total_dev_'+k).text(changeCurrency((parseInt(totalPlan ? totalPlan : 0) - parseInt(totalAct ? totalAct : 0)) ? (parseInt(totalPlan ? totalPlan : 0) - parseInt(totalAct ? totalAct : 0)) : 0)); 
           
       }
       
   });
   
   
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
      $(document).ready(function()
      {
            var result = [];
            for(var g=1;g<=4;g++)
            {
                $('#table_'+g+' tr').each(function(){
                   $('td', this).each(function(index, val){
                      if(!result[index]) result[index] = 0;
                      result[index] += parseFloat($(val).text().replace(/,/g , ''));
                   });
                });
                result.shift();
                $('#table_'+g).append('<tr><td class="text-right"><strong>Total : </strong></td></tr>');
                $(result).each(function(index)
                {
                    var x=Math.round(this);
                    x=x.toString();
                    var lastThree = x.substring(x.length-3);
                    var otherNumbers = x.substring(0,x.length-3);
                    if(otherNumbers != '')
                        lastThree = ',' + lastThree;
                    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
                   $('#table_'+g+' tr').last().append('<td class="text-center" id="act_'+g+'_'+index+'"><strong>'+res+'</strong></td>');
                     res = 0;
                  
                });
                 
                
                var totalAct = $("#act_"+g+"_1 strong").text().replace(/,/g , '');
                var totalPlan = $("#plan_"+g+"_1").text().replace(/,/g , '');
                var totalPlanQty = $("#act_count_"+g).val();
                var MonthlyPlan = $("#monthlyPlan_"+g).val();
                var dayCount = $("#dayCount_"+g).val();
                var askRate = $("#askRate_"+g).val();
                var unSelectedDayCount = parseInt(dayCount) - parseInt(MonthlyPlan ? MonthlyPlan : 0);
               
                $("#act_min_"+g).text( changeCurrency(Math.round(totalAct)));
                $("#plan_min_"+g).text(changeCurrency(totalPlan));
              
                $("#plan_qty_"+g).text(changeCurrency(parseInt($("#plan_min_"+g).text().replace(/,/g , '')) * parseInt(MonthlyPlan)));
                $("#deviation_"+g).text(changeCurrency((parseInt(totalPlan ? totalPlan : 0) * parseInt(totalPlanQty ? totalPlanQty : 0)) - parseInt(totalAct ? totalAct : 0)));
                $("#total_monthly_plan_"+g).text(changeCurrency(parseInt(totalPlan ? totalPlan : 0) * parseInt(MonthlyPlan ? MonthlyPlan : 0)));
                $("#total_bal_"+g).text(changeCurrency((parseInt(totalPlan ? totalPlan : 0) * parseInt(MonthlyPlan ? MonthlyPlan : 0)) - parseInt(totalAct)));
                $("#total_avg_"+g).text(changeCurrency(Math.round(totalAct ? totalAct : 0) * MonthlyPlan ? MonthlyPlan : 0));
                $("#total_asking_rate_"+g).text(changeCurrency((Math.round((parseInt(totalPlan ? totalPlan : 0) * parseInt(MonthlyPlan ? MonthlyPlan : 0)) - parseInt(totalAct ? totalAct : 0)) / unSelectedDayCount)));

            }
            
            $('#table_total tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
               });
            });
            result.shift();
            $('#table_total').append('<tr><td class="text-right"><strong>Total: </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
                $('#table_total tr').last().append('<td class="text-center"><strong>'+res1+'</strong></td>');
                res1 = 0;
            });
      });
   </script>
</html>