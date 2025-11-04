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
       <div class="col-md-12" style="margin: 10px;">
      <div class="row">
      <!-- Header -->
      <div class="invoice" style="border: 4px solid #aba3a3;margin: 5px;">
      <!-- Main Content -->
      <main>
         <!-- Item Details -->
        <div class="col-md-12" style="background: blanchedalmond;">
            <div class="row">
                <div class="col-md-4">
                   <p style="margin: 11px 15px 2px 0px;"><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230" style="border-radius: 13px;"> </p>
                </div>
                <div class="col-md-6" style="margin-top: 61px;">
                   <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                </div>
                <div class="col-md-2">    
                </div>
            </div>
        </div>
         <hr>
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
            
            .table thead th, td {
                vertical-align: middle!important;
            }

            th {
                writing-mode: vertical-rl;
                /* text-orientation: sideways; */
                transform: scale(-1);
                font-size: 13px;
                margin: 0px;
                background: #58347ec2;
                color: #fff;
            }
            
            .vl{
                writing-mode: horizontal-tb!important;
                text-orientation: sideways!important;
                transform: scale(1)!important;
                font-size: 13px!important;
                margin: 0px!important;
                background: #58347ec2;
                color: #fff;
            }
            .lv{
                writing-mode: vertical-rl;
                /* text-orientation: sideways; */
                transform: scale(-1);
                font-size: 13px;
                margin: 0px;
                text-align: center;
                background: #58347ec2;
                color: #fff;
            }
         </style>
         <!-- Passenger Details -->
         <div class="col-md-12" style="margin: 10px;background: #2e2c2512;">
             <table class="table table-bordered text-1 table-sm" style="height:10vh;white-space: nowrap;" >
                <thead>
                   <tr>
                    @php 
                        $remainAmt = 0;
                        $incWhole = 0;
                        $counter = 0;
                        $overAllRemain = 0;
                        $subRemainTotal = 0;
                        $otherOverAllRemain = 0;
                        $counter =1;
                        $totalDateCount = count($period);
                    @endphp
                    <th class="vl text-center">Vendor Name</th>
                    <th class="text-center">Color</th>
                    <th class="text-center">Line No</th>
                    @foreach($period as $pc)
                        <th>{{ date('d-M-Y',strtotime($pc)) }} </th>
                    @endforeach
                   </tr>
                </thead>
                <tbody>
                    @php
                    foreach($PPCList as $key=> $value)
                    {
                    
                    
                    @endphp
                        <tr>
                            <td class="vl">{{$PPCList[$key]->Ac_name}}</td>
                            <td class="lv">{{$PPCList[$key]->color_name}}</td>
                            <td class="lv">{{$PPCList[$key]->line_name}}</td>
                            @php
                          
                              $targetAmt = $PPCList[$key]->target;
                              $pCapacity = $PPCList[$key]->production_capacity;
                            
                              $divident = $targetAmt/$pCapacity;
                              $totalAmt = $targetAmt/$divident;
                              $whole = floor($divident);      // 1
                              $fraction = $divident - $whole;
                                
                              if($counter == 0)
                               {
                               
                                   for($i=0; $i<=$whole;$i++)
                                   {
                                      $overAllRemain = $targetAmt-$remainAmt;
                                      if ($i != $whole)
                                      {
                                      @endphp
                                            <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$totalAmt}}</td>
                                      @php 
                                       }
                                       else
                                       {
                                         @endphp
                                            <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$overAllRemain}}</td>
                                         @php 
                                             
                                       }
                                        $remainAmt += $totalAmt;
                                  }
                                  for($j=0; $j<=$totalDateCount-$whole-2; $j++)
                                  {
                                      @endphp
                                          <td style="text-align:center;">0</td>
                                      @php 
                                  
                                  }
                                  
                                  
                                 
                            }
                            else
                            {
                           
                                for($m=0; $m<=$incWhole-1; $m++)
                                {
                                     @endphp
                                        <td style="text-align:center;">0</td>
                                     @php 
                                }    
                                for($k=0; $k<=$whole; $k++)
                                {
                                 
                               
                                   if($k == 0)
                                   {
                                     if($overAllRemain > 0)
                                     {
                                        @endphp
                                            <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$totalAmt-$overAllRemain}}</td>
                                        @php 
                                     }
                                     else
                                     {
                                        @endphp
                                            <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$totalAmt-$otherOverAllRemain}}</td>
                                        @php 
                                     } 
                                   }
                                   else if ($k != $whole)
                                   {
                                   @endphp
                                        <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$totalAmt}}</td>
                                   @php 
                                   }
                                   else
                                   {
                                
                                    $overAllRemain = 0;
                                    $otherOverAllRemain = $targetAmt-$remainAmt;
                                     @endphp
                                        <td style="text-align:center;background-color: #f9f90dc7;font-weight: 700;">{{$otherOverAllRemain}}</td>
                                     @php 
                                         
                                   }
                                     
                                    $remainAmt += $totalAmt;
                                    $overAllRemain = 0;
                                 
                                }  
                               for($n=0; $n<=$totalDateCount-$whole-$incWhole-2; $n++)
                                {
                                     @endphp
                                        <td style="text-align:center;">0</td>
                                     @php 
                                }
                               
                               
                            }
                                  
                    @endphp
                       </tr>
                    @php 
                           $targetAmt = 0;
                           $incWhole += $whole;
                           $totalAmt = 0;
                           $remainAmt = 0;
                           $counter++;
                    }
                 @endphp
                </tbody>
             </table>
              <!-- Footer -->
              <footer  >
                 <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
              </footer>
           </div>
      </div>
      </div>
      </div>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>
      $(document).ready(function(){
            var result = [];
            $('table tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text());
               });
            });
            result.shift();
            result.shift();
            result.shift();
            $('table').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
               $('table tr').last().append('<td class="text-center"><strong>'+this+'</strong></td>')
            });
      });
   </script>
</html>