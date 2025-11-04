@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
    
    table th,td{
        font-family: Open Sans,sans-serif!important;
    }
    
    .bolder{
        font-weight:bolder;
    }
    
   .fixTableHead { 
      overflow-y: auto; 
      height: 700px; 
      z-index: 99999;
    } 
    .fixTableHead thead { 
      position: sticky; 
      top: 0; 
      background: #ffa500!important;
      z-index: 99999;
    } 
    table { 
      border-collapse: collapse;         
      width: 100%; 
    } 
    th, 
    td { 
      padding: 10px 9px!important;
      border: 2px solid #529432; 
    } 
    th { 
      background: #ABDD93; 
    } 
    
     .fixTableCol { 
      position: sticky;   
      left: 0;  
      background: #ffa500!important;
    } 
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Production Cost Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Production Cost Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row">
   <div class="col-12">
      <div class="card" style="margin-bottom: 0;">
         <div class="card-body">
            <div class="col-md-12 text-center">
                  <form action="{{route('productionCostReport')}}" method="GET" enctype="multipart/form-data">
                       @csrf 
                       <div class="row">
                           <div class="col-md-2">
                             <div class="mb-3">
                                <label for="fromDate" class="form-label">From Date</label>
                                <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                             </div>
                           </div>
                           <div class="col-md-2">
                             <div class="mb-3">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate :  date('Y-m-d')}}">
                             </div>
                           </div> 
                           <div class="col-sm-2">
                              <label for="formrow-inputState" class="form-label"></label>
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary w-md">Submit</button>
                              </div>
                           </div> 
                       </div>
                 </form>
              </div> 
            <div class="col-md-3 mb-5">
                <button type="button" id="export_button" class="btn btn-warning">Export</button>
            </div>
            <div class="table-responsive fixTableHead" id="TblData">
                 <table id="dt" class="table table-bordered   nowrap w-100">
                     
                         @php 
                            $sewingData = DB::SELECT("SELECT * FROM line_master WHERE Ac_code IN(56,69,115) order By seq_id ASC");
                            $sewingData56 = DB::SELECT("SELECT count(*) as total FROM line_master WHERE Ac_code = 56");         
                            $sewingData69 = DB::SELECT("SELECT count(*) as total FROM line_master WHERE Ac_code = 69");
                            $sewingData115 = DB::SELECT("SELECT count(*) as total FROM line_master WHERE Ac_code = 115");
                         @endphp
                     <thead>
                          <tr style="background-color:#eee;"> 
                               <th></th> 
                               <th nowrap colspan="5" class="text-center">Cutting</th> 
                               <th nowrap colspan="35" class="text-center">Sewing</th> 
                               <th nowrap colspan="6" class="text-center">Packing</th> 
                          </tr>
                          <tr style="background-color:#eee;">  
                               <th nowrap class="text-center"></th> 
                               <th nowrap colspan="3" class="text-center">UNIT-1</th> 
                               <th nowrap colspan="{{ 13+ $sewingData56[0]->total}}" class="text-center">UNIT-1</th> 
                               <th nowrap colspan="{{ 8+ $sewingData115[0]->total}}" class="text-center">UNIT-2</th> 
                               <th nowrap colspan="2" class="text-center">ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                               <th nowrap class="text-center">UNIT 1, 2, ANSH</th> 
                          </tr>
                          <tr style="background-color:#eee;">  
                               <th nowrap class="text-center tblTh fixTableCol">Date</th> 
                               <th class="text-center tblTh" nowrap>Cutting Qty</th> 
                               <th class="text-center tblTh" nowrap>Cutting Cost</th>
                               <th class="text-center tblTh" nowrap>Cutting Cost/Pcs</th> 
                               <th class="text-center tblTh" nowrap>Cumulative Cutting Qty</th>  
                               <th class="text-center tblTh" nowrap>Cumulative Cutting Cost</th> 
                               
                               @foreach($sewingData as $sewings)
                               <th class="text-center tblTh" nowrap>{{$sewings->line_name}} Qty</th>
                               <th class="text-center tblTh" nowrap>{{$sewings->line_name}} Min</th>
                               <th class="text-center tblTh" nowrap>{{$sewings->line_name}} Cost</th> 
                               @endforeach
                               <th class="text-center tblTh" nowrap>Total Sewing Qty</th>
                               <th class="text-center tblTh" nowrap>Total Sewing Min</th>
                               <th class="text-center tblTh" nowrap>Total Sewing Cost</th>
                               <th class="text-center tblTh" nowrap>Cumulative Sewing Qty</th>
                               <th class="text-center tblTh" nowrap>Cumulative Sewing Min</th>
                               <th class="text-center tblTh" nowrap>Cumulative Sewing Cost</th>
                               <th class="text-center tblTh" nowrap>Packing Qty</th> 
                               <th class="text-center tblTh" nowrap>Packing Cost</th>
                               <th class="text-center tblTh" nowrap>Packing Cost/Pcs</th>
                               <th class="text-center tblTh" nowrap>Cumulative Packing Qty</th> 
                               <th class="text-center tblTh" nowrap>Cumulative Packing Cost</th> 
                          </tr> 
                     </thead>
                     <tbody>
                         @php 
                            
                            $temp = 0;
                            $temp1 = 0;
                            $temp2 = 0;
                            $temp3 = 0;
                            $temp4 = 0;
                            $temp5 = 0;
                            $temp6 = 0; 
                            $total_cutting_cost = 0; 
                             
                         @endphp 
                         @foreach($allDates as $row)
                         @php
                            $misData = DB::SELECT("SELECT sum(employeemaster1.misRate) as totalMIS FROM attendancelogs INNER JOIN employeemaster1 ON employeemaster1.employeeCode = attendancelogs.employeeCode
                                        WHERE attendancelogs.Status != 15 AND AttendanceDate='".$row."' AND employeemaster1.dept_id=5 AND employeemaster1.mis_location IN(56)");
                         
                            $cutting_cost = isset($misData[0]->totalMIS) ? $misData[0]->totalMIS : 0;
                            
                            
                            $cuttingData = DB::SELECT("SELECT SUM(total_qty) as total_cut_qty,cpg_date FROM cut_panel_grn_master WHERE vendorId='56' AND cpg_date ='".$row."' GROUP BY cpg_date"); 
                            $total_cut_qty = isset($cuttingData[0]->total_cut_qty) ? $cuttingData[0]->total_cut_qty : 0;
                            
                            if($total_cut_qty > 0 && $cutting_cost > 0)
                            {
                                $cutting_per = $cutting_cost/$total_cut_qty;
                            }
                            else
                            {
                                $cutting_per = 0;
                            }
                            
                            $total_cutting_cost = $cutting_cost; 
                        
                           
                         @endphp
                        <tr>
                            <td class="text-right fixTableCol" nowrap>{{date('d-m-Y',strtotime($row))}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_cut_qty)}}</td> 
                            <td class="text-right"><a href="/EmployeeListCostingWise/{{$row}}" target="_blank">{{money_format("%!.0n",$cutting_cost)}}</a></td>
                            <td class="text-right">{{number_format($cutting_per, 2, '.', '' )}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_cut_qty + $temp)}}</td> 
                            <td class="text-right">{{money_format("%!.0n",$total_cutting_cost+$temp4)}}</td>
                            @php
                                $total_sewing_qty = 0;
                                $total_prod_sewing_min = 0;
                                $total_packing_cost = 0;
                                $total_sewing_cost = 0; 
                               
                            @endphp
                            @foreach($sewingData as $sewings)
                             @php
                             
                                
                            $LQ = 0;
                                $sewingData1 = DB::SELECT("SELECT SUM(total_qty) as total_sewing_qty FROM stitching_inhouse_master WHERE sti_date ='".$row."' AND vendorId=".$sewings->Ac_code." AND line_id=".$sewings->line_id); 
                                
                                $StichingData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.vendorId='".$sewings->Ac_code."' and stitching_inhouse_size_detail2.line_id='".$sewings->line_id."' and 
                                    stitching_inhouse_size_detail2.sti_date = '".$row."'");
                            
                                $line_qty = isset($sewingData1[0]->total_sewing_qty) ? $sewingData1[0]->total_sewing_qty : 0; 
                                $prod_sewing_min = isset($StichingData[0]->total_min) ? $StichingData[0]->total_min : 0; 
                                
                                $total_sewing_qty += $line_qty;
                                $total_prod_sewing_min += round($prod_sewing_min,2);
                                // DB::enableQueryLog();
                                $sewingMisData = DB::SELECT("SELECT sum(employeemaster1.misRate) as totalMIS FROM daily_operators_line_wise 
                                        INNER JOIN employeemaster1 ON employeemaster1.employeeCode = daily_operators_line_wise.employeeCode
                                        WHERE daily_operators_line_wise.dopDate='".$row."' AND daily_operators_line_wise.line_no = '".$sewings->line_id."' 
                                        AND employeemaster1.dept_id=43 AND daily_operators_line_wise.company_id='".$sewings->hrms_company_id."'");
                                //dd(DB::getQueryLog());

                                $sewing_cost = isset($sewingMisData[0]->totalMIS) ? $sewingMisData[0]->totalMIS : 0;
                                $total_sewing_cost +=$sewing_cost;  
                             @endphp
                            <td class="text-right">{{money_format("%!.0n",$line_qty)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$prod_sewing_min)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$sewing_cost)}}</td>
                            
                            @php
                                $LQ += $line_qty;
                            
                            @endphp
                            @endforeach 
                            @php
                                $sewing_total_qty_array[] = $LQ;
                            
                            @endphp
                            <td class="text-right">{{money_format("%!.0n",$total_sewing_qty)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_prod_sewing_min)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_sewing_cost)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_sewing_qty + $temp1)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_prod_sewing_min + $temp3)}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_sewing_cost + $temp6)}}</td>
                            
                            @php
                                $packingData = DB::SELECT("SELECT ifnull(sum(total_qty),0) as total_qty FROM packing_inhouse_master WHERE vendorId IN (56,69,115) AND pki_date ='".$row."'");
                                $total_packing_qty = isset($packingData[0]->total_qty) ? $packingData[0]->total_qty : 0;
                                //DB::enableQueryLog();
                                $packingMisData = DB::SELECT("SELECT sum(employeemaster1.misRate) as totalMIS FROM employeemaster1 
                                                            INNER JOIN attendancelogs ON attendancelogs.employeeCode = employeemaster1.employeeCode
                                                            WHERE employeemaster1.dept_id=45 AND employeemaster1.sub_company_id IN (4,5,6,7,8,11) 
                                                            AND attendancelogs.Status!=15 AND attendancelogs.AttendanceDate='".$row."'");
                                    
                                //dd(DB::getQueryLog());  
                                $packing_cost = isset($packingMisData[0]->totalMIS) ? $packingMisData[0]->totalMIS : 0;
                                $total_packing_cost += $packing_cost;
                                
                                if($packing_cost > 0 && $total_packing_qty > 0)
                                {
                                    $packing_per = $packing_cost/$total_packing_qty;
                                }
                                else
                                {
                                    $packing_per = 0;
                                }
                                
                            @endphp
                            <td class="text-right">{{money_format("%!.0n",$total_packing_qty)}}</td> 
                            <td class="text-right">{{money_format("%!.0n",$packing_cost)}}</td>
                            <td class="text-right">{{number_format($packing_per, 2, '.', '' )}}</td>
                            <td class="text-right">{{money_format("%!.0n",$total_packing_qty + $temp2)}}</td> 
                            <td class="text-right">{{money_format("%!.0n",$total_packing_cost + $temp5)}}</td>
                        </tr>
                         @php 
                            
                            $temp += $total_cut_qty;
                            $temp1 += $total_sewing_qty;
                            $temp2 += $total_packing_qty;
                            $temp3 += $total_prod_sewing_min;
                            $temp4 += $total_cutting_cost;
                            $temp5 += $total_packing_cost;
                            $temp6 += $total_sewing_cost;
                         @endphp
                        @endforeach  
                     </tbody> 
                  </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row --> 
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>

<script>
   
 
      $(document).ready(function()
      { 
            var result = [];
            $('tbody tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , '')); 
               });
            });  
            result.shift();
            $('table').append('<tr><td class="text-right fixTableCol" nowrap><strong>Total : </strong></td></tr>');
            $(result).each(function(i){
               var total_cutting_cost = result[1];
               var total_cutting_qty = result[0];
              
               var total_cost_per_pcs = parseFloat(total_cutting_cost/total_cutting_qty).toFixed(2);
           
               $('table tr').last().append('<td class="text-right cls_'+i+'"><strong>'+changeCurrency(this.toFixed(2))+'</strong></td>');
               
               $('table tr').last().find('td:nth-child(4)').text(total_cost_per_pcs).addClass('bolder');
               
               var total_packing_qty = result[result.length-5];
               var total_packing_cost = result[result.length-4];
             
               var total_cost_per_pcs_packing = parseFloat(total_packing_cost/total_packing_qty).toFixed(2); 
               
               var res = result.length-1;
               var cum1 = parseInt(result.length) + 1;
               var cum2 =parseInt(result.length);
               var cum3 =parseInt(result.length - 4);
               var cum4 =parseInt(result.length - 5);
               var cum5 =parseInt(result.length - 6);
              $('table tr').last().find('td:nth-child('+res+')').text(total_cost_per_pcs_packing).addClass('bolder'); 
              $('table tr').last().find('td:nth-child(5)').text("-").addClass('bolder'); 
              $('table tr').last().find('td:nth-child(6)').text("-").addClass('bolder'); 
              $('table tr').last().find('td:nth-child('+cum1+')').text("-").addClass('bolder'); 
              $('table tr').last().find('td:nth-child('+cum2+')').text("-").addClass('bolder'); 
              $('table tr').last().find('td:nth-child('+cum3+')').text("-").addClass('bolder');
              $('table tr').last().find('td:nth-child('+cum4+')').text("-").addClass('bolder');
              $('table tr').last().find('td:nth-child('+cum5+')').text("-").addClass('bolder');
            });
          
 
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
      
     function html_table_to_excel(type)
     {
        var data = document.getElementById('TblData');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Production Cost Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
</script>
@endsection