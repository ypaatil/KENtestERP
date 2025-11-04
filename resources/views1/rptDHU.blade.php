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
          background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
          }
          
          .invoice-container{
                  border: none;
          }
          
          .op{
                text-transform: uppercase;
                transform: rotate(180deg) translate(-4px);
                writing-mode: vertical-lr;
                text-align: center;
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
                  <h4 class="mb-0">DHU Report</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh;" id="dhu_table">
                         <thead>
                              <tr>
                                   <th nowrap style="background: black;color: #fff;">{{$line_name}}</th>
                                   <th nowrap></th>
                                   <th nowrap><strong>Total</strong></th>
                                   @php
                                    for($i=1;$i<=$days;$i++)
                                    {
                                    @endphp
                                    <th class="text-center" nowrap  style="background-color:#f3ec12;">{{$i}}</th>
                                    @php
                                    }
                                   @endphp
                              </tr>
                         </thead>
                         <tbody>
                            <tr>
                               <td nowrap></td>
                               <td nowrap  style="background-color: #68e30c;"><strong>Rejected%</strong></td>
                               <td class="text-center" nowrap id="total_dhu_rejected" style="background-color: #68e30c;">0.00</td>
                                 @php
                                    for($i=1;$i<=$days;$i++)
                                    { 
                                    @endphp
                                    <td class="text-center dhu_rejected_{{$i}}" nowrap  style="background-color: #68e30c;">0.00</td>
                                    @php 
                                    }
                                   @endphp
                            </tr>  
                            <tr>
                               <td nowrap></td>
                               <td nowrap  style="background-color: #68e30c;"><strong>DHU%</strong></td>
                               <td class="text-center" id="total_dhu" nowrap  style="background-color: #68e30c;">0.00</td>
                                 @php
                                    for($i=1;$i<=$days;$i++)
                                    { 
                                    @endphp
                                    <td class="text-center dhu_{{$i}}" style="background-color: #68e30c;" nowrap>0.00</td>
                                    @php 
                                    }
                                   @endphp
                            </tr>
                            <tr>
                               <td nowrap><strong>Checker:</strong></td>
                               <td nowrap>Pass Pieces</td>
                               <td class="text-center" nowrap id="total_pass">0.00</td>
                                 @php
                                    for($i=1;$i<=$days;$i++)
                                    { 
                                        $fullDate = $monthDate."-".$i;
                                        $passData = DB::select("select ifnull(SUM(size_qty_total),0) as pass_qty FROM qcstitching_inhouse_detail 
                                                    WHERE qcstitching_inhouse_detail.vendorId=".$vendorId." AND qcsti_date='".$fullDate."' AND line_id=".$line_no);    
                                      
                                    @endphp
                                    <td class="text-center pass_{{$i}}" nowrap>{{ money_format('%!.0n',round(isset($passData[0]->pass_qty) ? $passData[0]->pass_qty : 0))}}</td>
                                    @php 
                                    }
                                   @endphp
                            </tr>
                            <tr>
                               <td nowrap></td>
                               <td nowrap>Rejected Pieces</td>
                               <td class="text-center" nowrap id="total_rejected">0.00</td>
                                 @php
                                    for($i=1;$i<=$days;$i++)
                                    { 
                                        $fullDate = $monthDate."-".$i;
                                        $rejectData = DB::select("select ifnull(SUM(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail 
                                                    WHERE qcstitching_inhouse_reject_detail.vendorId=".$vendorId." AND qcsti_date='".$fullDate."' AND line_id=".$line_no);    
                                      
                                    @endphp
                                    <td class="text-center reject_{{$i}}" nowrap>{{ money_format('%!.0n',round(isset($rejectData[0]->reject_qty) ? $rejectData[0]->reject_qty : 0))}}</td>
                                    @php 
                                    }
                                   @endphp
                            </tr>
                            <tr>
                               <td nowrap><strong>Month: {{$monthDate}}</strong></td>
                               <td nowrap >Number of Defects</td>
                               <td class="text-center" nowrap  id="total_defects">0.00</td>
                                 @php
                                    for($i=1;$i<=$days;$i++)
                                    { 
                                    @endphp
                                    <td class="text-center noOfDefect_{{$i}}" nowrap><strong>0.00</strong></td>
                                    @php 
                                    }
                                   @endphp
                            </tr>
                            @php
                               $nx = 1;
                               $temp = "";
                               $defectArr = array();
                            @endphp
                            @foreach($DHUOp as $op)
                            @php
                                 
                                $defectData = DB::select("select dhu_stiching_defect_type.dhu_sdt_Id,dhu_stiching_defect_type.dhu_sdt_Name,dhu_master.dhu_code,dhu_details.dhu_so_Id FROM dhu_stiching_defect_type 
                                            INNER JOIN dhu_details ON dhu_details.dhu_sdt_Id = dhu_stiching_defect_type.dhu_sdt_Id
                                            INNER JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code
                                            WHERE dhu_master.vendorId=".$vendorId." AND dhu_master.line_no = ".$line_no." AND dhu_details.dhu_so_Id =".$op->dhu_so_Id." GROUP BY dhu_details.dhu_sdt_Id");
                           
                                $dCount = count($defectData);  
                            @endphp
                            @foreach($defectData as $defect)
                           
                            <tr class="sop_{{$op->dhu_so_Id}}">
                            @php
                                if($temp != $op->dhu_so_Id)
                                {
                                     $rowSpan = 'rowspan='.$dCount;
                                   
                            @endphp
                                <td class="op" {{$rowSpan}}><strong>{{$op->dhu_so_Name}}</strong></td>
                            @php
                                }
                                else
                                {
                                    $nx == 0;
                                }
                            @endphp
                                   <td nowrap><strong>{{$defect->dhu_sdt_Name}}</strong></td>
                                   <td class="text-center total_{{$defect->dhu_sdt_Id}}" nowrap>0.00</td>
                                   @php
                                        $defectArr[] = $defect->dhu_sdt_Id;
                                        for($i=1;$i<=$days;$i++)
                                        { 
                                              $fullDate = $monthDate."-".$i;
                                              //DB::enableQueryLog();
                                              $defectQtyData = DB::select("select ifnull(sum(dhu_details.defect_qty),0) as defect_qty,dhu_details.dhu_sdt_Id,$defect->dhu_so_Id FROM dhu_details 
                                                LEFT JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code
                                                WHERE dhu_master.vendorId=".$vendorId." AND dhu_master.line_no = ".$line_no." AND dhu_master.dhu_date = '".$fullDate."' 
                                            
                                                AND dhu_details.dhu_so_Id =".$defect->dhu_so_Id." AND dhu_details.dhu_sdt_Id =".$defect->dhu_sdt_Id." GROUP BY dhu_details.dhu_sdt_Id");
                                               //dd(DB::getQueryLog());
                                             
                                        @endphp
                                        <td class="text-center d_{{$defect->dhu_sdt_Id}} v_{{$i}}" nowrap>{{ money_format('%!.0n',round(isset($defectQtyData[0]->defect_qty) ? $defectQtyData[0]->defect_qty : ""))}}</td>
                                        @php 
                                        }
                                    @endphp
                                
                            </tr>
                            @php
                                $nx++;
                                $temp = $op->dhu_so_Id;
                            @endphp
                            @endforeach
                            @endforeach
                            <input type="hidden" id="defectArr" value="{{json_encode($defectArr)}}">
                            <input type="hidden" id="totalDays" value="{{$days}}">
                            <input type="hidden" id="sunDays" value="{{json_encode($dateSun)}}">
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated DHU Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
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
      
     document.getElementById("printPage").addEventListener("click", function() {
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

        XLSX.writeFile(file, 'DHU Report.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     $(document).ready(function()
     {
            var result = $.parseJSON($('#defectArr').val());
            var totalDays = $('#totalDays').val();
            var sunDays = $.parseJSON($('#sunDays').val());
           
            $.each(result, function (i)             
            {
                var totalQty = 0;
                var dayData =  $(".d_"+result[i]);
                $.each(dayData, function (i) 
                {
                    totalQty = parseFloat(totalQty) + parseFloat($(this).html() ? $(this).html() : 0);
                });
                $('.total_'+result[i]).html(totalQty.toFixed());
                
                var de_horizontal = 0;
                var pass_horizontal = 0;
                var reject_horizontal = 0;
                var dhu_horizontal = 0;
                var dhu_reject_horizontal = 0;
                for(var x=1; x<=totalDays; x++)
                {
                   var vertical = 0;
                   if(jQuery.inArray(x, sunDays) === -1)
                   {
                        verticalData = $(".v_"+x);
                        $.each(verticalData, function (i) 
                        {
                            vertical = parseFloat(vertical) + parseFloat($(this).html() ? $(this).html() : 0);
                        });
                        $('.noOfDefect_'+x).html(vertical.toFixed());
                        
                        de_horizontal = parseFloat(de_horizontal) + parseFloat($(".noOfDefect_"+x).html() ? $(".noOfDefect_"+x).html() : 0);
                        pass_horizontal = parseFloat(pass_horizontal) + parseFloat($(".pass_"+x).html() ? $(".pass_"+x).html() : 0);
                        reject_horizontal = parseFloat(reject_horizontal) + parseFloat($(".reject_"+x).html() ? $(".reject_"+x).html() : 0);
                        
                        var defect = $(".noOfDefect_"+x).html() ? $(".noOfDefect_"+x).html() : 0;
                        var pass = $(".pass_"+x).html() ? $(".pass_"+x).html() : 0;
                        var reject = $(".reject_"+x).html() ? $(".reject_"+x).html() : 0;
                       
                        var dhu = round(((parseFloat(defect) + parseFloat(reject))/(parseFloat(pass) + parseFloat(defect) + parseFloat(reject))) * 100,2);
                        var rejected_dhu = round((parseFloat(reject)/(parseFloat(pass) + parseFloat(defect) + parseFloat(reject)) * 100),2);
                        
                        $(".dhu_"+x).html((dhu ? dhu : 0).toFixed(2)+'%');
                        $(".dhu_rejected_"+x).html((rejected_dhu ? rejected_dhu : 0).toFixed(2)+'%');
                        
                        dhu_horizontal = parseFloat(dhu_horizontal) + parseFloat($(".dhu_"+x).html() ? $(".dhu_"+x).html() : 0);
                        dhu_reject_horizontal = parseFloat(dhu_reject_horizontal) + parseFloat($(".dhu_rejected_"+x).html() ? $(".dhu_rejected_"+x).html() : 0);
                        
                   }
                }
                
                $('#total_defects').html(de_horizontal.toFixed());
                $('#total_rejected').html(reject_horizontal.toFixed());
                $('#total_pass').html(pass_horizontal.toFixed());
                $('#total_dhu_rejected').html(dhu_reject_horizontal.toFixed(2)+'%');
                $('#total_dhu').html(dhu_horizontal.toFixed(2)+'%');
            });
      });
      
      function round(value, decimals) 
      {
        return Number(Math.floor(parseFloat(value + 'e' + decimals)) + 'e-' + decimals);
     }
   </script>
</html>