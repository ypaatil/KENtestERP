@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                

<style>
    .text-left
    {
      text-align:left;
    }
  
    .text-right
    {
      text-align:right;
    }
  
    /*table th:nth-child(10),td:nth-child(10) */
    /*{*/
    /*  position: sticky;*/
    /*  left: 0;*/
    /*  z-index: 2;*/
    /*  background:#f4f2eef0;*/
    /*}*/
    
    *{
       font-family: Open Sans,sans-serif!important;
    }
     
  
  /* table#datatable-buttons thead th {*/
  /*  position: sticky;*/
  /*  top: 0;*/
  /*  z-index: 999;*/
  /*}*/

  /* Sticky columns */
  /*.sticky-column {*/
  /*  position: sticky;*/
  /*  left: 0;*/
  /*  z-index: 998;*/
  /*  background-color: #4a4646!important; */
  /*  color: #fff!important; */
  /*}*/
  .border-line
  {
      border-left: 2px solid gray!important;
  }
  th
  {
      background-color: #4a4646!important; 
      color: #fff!important; 
  }
  thead {
      top: 0;
      position: sticky;
      z-index: 1; 
    }

    tr td:nth-child(1),
    tr td:nth-child(2),
    tr th:nth-child(1),
    tr th:nth-child(2){
      position: sticky;
      left: 0;
      background-color: #4a4646!important; 
      color: #fff!important; 
      
    }
    tr th:nth-child(2),
    tr td:nth-child(2){
      left: 60px;
    }
     
 
 .table-responsive{
  height: 700px;
  max-width: 100vw;
  overflow-x: auto;
  overflow-y: auto;
  position: relative;
  margin-top: 30px;
}

</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18"></h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Monthly Order Status Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="/MonthlyOrderStatusReport" method="GET" enctype="multipart/form-data">
                   @csrf 
                   <div class="row">
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="fin_year_id" class="form-label">Financial Year</label>
                            <select name="fin_year_id" id="fin_year_id" class="form-control"> 
                                <option value="0">All</option>
                            @foreach($Financial_Year1 as $years)
                                 <option value="{{$years->fin_year_id}}" {{ $years->fin_year_id == $fin_year_id ? 'selected="selected"' : '' }}>{{$years->fin_year_name}}</option>
                            @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-sm-6">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/MonthlyOrderStatusReport" class="btn btn-danger w-md">Cancel</a>
                             <button type="button" id="export_button" class="btn btn-warning">Export</button>  
                          </div>
                       </div> 
                   </div>
             </form>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card" id="mos_report">
         <div class="card-body"> 
         <h4 class="mb-sm-0 font-size-18">Monthly Order Status Report</h4>
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered nowrap w-100 sticky-columns">
                  <thead>
                     <tr style="text-align:center;white-space:nowrap;">
                        <th>Sr No.</th>
                        <th class="sticky-column border-line">Buyer Name</th>
                        @if($fin_year_id >0)
                        <th colspan="3" class="border-line">Opening</th>
                        @endif
                        @php
                         

                         $backgroundColors = ['#ff6dff6e', '#6dff746e', '#ffbc6d6e', '#6dceff6e','#6d86ff6e','#ff6db36e']; // Add more colors as needed
                         $colorIndex = 0; // Initialize color index
                         
                         $j=date("Y", strtotime($from));
                         for($i=date("Y", strtotime($from));$i<date("Y", strtotime($to));$i++)
                         {
                           $j = $i;
                        @endphp
                            @foreach($financialYearMonths as $key=>$months)
                            @php
                            
                                 $backgroundColor = $backgroundColors[$colorIndex];
                                 $colorIndex = ($colorIndex + 1) % count($backgroundColors);
                            @endphp
                           <th colspan="3" class="border-line" style="background-color: {{$backgroundColor}}">{{$months}} {{$j}}</th>
                            @php
                            if($months == 'DEC')
                            { 
                                $j++; 
                            }
                            @endphp
                            @endforeach
                        @php 
                        }
                        @endphp
                        <th colspan="3" class="border-line">Grand Total</th>
                     </tr> 
                     <tr style="text-align:center;white-space:nowrap;">
                        <th></th>
                        <th class="border-line"></th>
                        @if($fin_year_id >0)
                        <th class="sticky-column border-line">PCS L</th>
                        <th class="sticky-column">Min L</th>
                        <th class="sticky-column">Cr.</th>
                        @endif
                        @php
                        
                         $j=date("Y", strtotime($from));
                         for($i=date("Y", strtotime($from));$i<date("Y", strtotime($to));$i++)
                         {
                           $j = $i;
                        @endphp
                        @foreach($financialYearMonths as $months)
                        @php
                        
                             $backgroundColor = $backgroundColors[$colorIndex];
                             $colorIndex = ($colorIndex + 1) % count($backgroundColors);
                        @endphp
                            <th class="border-line" style="background-color: {{$backgroundColor}}">PCS L</th>
                            <th style="background-color: {{$backgroundColor}}">Min L</th>
                            <th style="background-color: {{$backgroundColor}}">Cr.</th> 
                        @endforeach
                        @php 
                        }
                        @endphp
                        <th class="border-line">PCS L</th>
                        <th>Min L</th>
                        <th>Cr.</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                        $srno = 1;
                        $bookingGrandTotal = 0;
                        $minGrandTotal = 0;
                        $valueGrandTotal = 0;
                        $count = 0;
                     @endphp
                     @foreach($Buyer_Purchase_Order_List as $row)
                     <tr style="text-align:center;white-space:nowrap;">
                        <td>{{$srno++}}</td>
                        <td style="text-align:left;white-space:nowrap;" class="border-line">{{$row->ac_short_name}}</td> 
                        @php
                        if($fin_year_id >0)
                        {
                             if(sprintf("%.2f", $row->opening_qty/100000) == "0.00")
                             {
                                $obk = "";
                             }
                             else
                             {
                                $obk = sprintf("%.2f", $row->opening_qty/100000);
                             }
                             
                             if(sprintf("%.2f", $row->opening_min/100000) == "0.00")
                             {
                                $omin = "";
                             }
                             else
                             {
                                $omin = sprintf("%.2f", $row->opening_min/100000);
                             }
                                
                             if(sprintf("%.2f", $row->opening_value/10000000) == "0.00")
                             {
                                $oval = "";
                             }
                             else
                             {
                                $oval = sprintf("%.2f", $row->opening_value/10000000);
                             }
                             
                        @endphp
                        <td class="text-right sticky-column border-line">{{$obk}}</td>
                        <td class="text-right sticky-column">{{$omin}}</td>
                        <td class="text-right sticky-column">{{$oval}}</td> 
                        @php
                        }
                         $j=date("Y", strtotime($from));
                         for($i=date("Y", strtotime($from));$i<date("Y", strtotime($to));$i++)
                         {
                           $j = $i;
                           $counter = 4;
                        @endphp
                        @foreach($financialYearMonths as $key=>$months)
                        @php
                           $counter1 = 0;
                           if($counter < 10)
                           {
                                $month_no = "0".$counter++;
                           }
                           else
                           {
                                $month_no = $counter++;
                           }
                       
                           $fromDate = $j."-".$month_no."-01";
                           $toDate = date("Y-m-t", strtotime(date("Y-m", strtotime($j."-".$month_no))));
                              
                            if($months == 'DEC')
                            { 
                                $j++;
                                $counter = 1;
                            } 
                             
                             $BuyerData = DB::SELECT("SELECT sam, sum(total_qty) as booking_qty, sum(total_qty * sam) as minutes,sum(total_qty * order_rate) as value FROM buyer_purchse_order_master 
                                    WHERE delflag = 0 AND og_id != 4 AND order_received_date BETWEEN '".$fromDate."' and '".$toDate."' AND Ac_code=".$row->Ac_code." AND job_status_id!=3");

                             $booking_qty = isset($BuyerData[0]->booking_qty) ? $BuyerData[0]->booking_qty: 0;   
                             $minutes_qty = isset($BuyerData[0]->minutes) ? $BuyerData[0]->minutes: 0;    
                             $value = isset($BuyerData[0]->value) ? $BuyerData[0]->value: 0;  
                             
                             $bookingGrandTotal += $booking_qty;
                             $minGrandTotal += $minutes_qty;
                             $valueGrandTotal += $value;
                             
                             if(sprintf("%.2f", $booking_qty/100000) == "0.00")
                             {
                                $bk = "";
                             }
                             else
                             {
                                $bk = sprintf("%.2f", $booking_qty/100000);
                             }
                             
                             if(sprintf("%.2f", $minutes_qty/100000) == "0.00")
                             {
                                $min = "";
                             }
                             else
                             {
                                $min = sprintf("%.2f", $minutes_qty/100000);
                             }
                             
                             
                             if(sprintf("%.2f", $value/10000000) == "0.00")
                             {
                                $val = "";
                             }
                             else
                             {
                                $val = sprintf("%.2f",  $value/10000000);
                             }
                             
                             $backgroundColor = $backgroundColors[$colorIndex];
                             $colorIndex = ($colorIndex + 1) % count($backgroundColors);
                        @endphp
                            <td class="text-right border-line" style="background-color: {{$backgroundColor}}">{{$bk}}</td>
                            <td class="text-right" style="background-color: {{$backgroundColor}}">{{$min}}</td>
                            <td class="text-right" style="background-color: {{$backgroundColor}}">{{$val}}</td>
                        @endforeach
                        @php
                            }
                            if($fin_year_id >0)
                            {
                                $bookingGrandTotal += $row->opening_qty;
                                $minGrandTotal += $row->opening_min;
                                $valueGrandTotal += $row->opening_value;
                            }
                            if(sprintf("%.2f", $bookingGrandTotal/100000) == "0.00")
                            {
                                $gbk = "";
                            }
                            else
                            {
                                $gbk = sprintf("%.2f", $bookingGrandTotal/100000);
                            }
                              
                            if(sprintf("%.2f", $minGrandTotal/100000) == "0.00")
                            {
                                $gmin = "";
                            }
                            else
                            {
                                $gmin = sprintf("%.2f", $minGrandTotal/100000);
                            }
                               
                            if(sprintf("%.2f", $valueGrandTotal/10000000) == "0.00")
                            {
                                $gval = "";
                            }
                            else
                            {
                                $gval = sprintf("%.2f", $valueGrandTotal/10000000);
                            }
                             
                        @endphp
                        <td class="text-right border-line">{{$gbk}}</td>
                        <td class="text-right">{{$gmin}}</td>
                        <td class="text-right">{{$gval}}</td>
                     </tr> 
                     @php
                        $bookingGrandTotal = 0;
                        $minGrandTotal = 0;
                        $valueGrandTotal = 0;
                     @endphp
                     @endforeach
                  </tbody> 
                  <tfoot>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script>

    function html_table_to_excel(type)
    {
         var data = document.getElementById('mos_report');
     
         var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
     
         XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
     
         XLSX.writeFile(file, 'Monthly Order Status Report.' + type);
    }
     
    const export_button = document.getElementById('export_button');
     
    export_button.addEventListener('click', () =>  {
         html_table_to_excel('xlsx');
    });
    
    $(document).ready(function(){
        var result = [];
        $('table tr').each(function(){
            $('td', this).each(function(index, val){
                if(!result[index]) result[index] = 0;
                result[index] += parseFloat($(val).text()) ? parseFloat($(val).text()) : 0;
            });
        });
        result.shift();
        result.shift();
    
        // $('tfoot').each(function () {
        //     $(this).insertBefore($(this).siblings('thead'));
        // });
        $('tfoot').append('<tr class="overall_total"><td></td><td class="text-right border-line"><strong>Total : </strong></td></tr>');
        $(result).each(function(i){
            var y = this.toFixed(2); 
            y = y.toString(); 
            var res1 = y.replace(/\,/g, '');
            var j = i;
            var border_class = "";

            if ((parseInt(j) + 3) % 3 === 0) 
            {
                border_class = "border-line"; 
            }
            
            $('table tr').last().append('<td class="text-right '+border_class+'"><strong>' + res1 + '</strong></td>');


        });   
   
        // Hide the warning message
        $.fn.DataTable.ext.errMode = 'none';
        
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#datatable-buttons')) { 
            $('#datatable-buttons').DataTable().destroy();
        }
     
      
        
      var dataTable = $('#datatable-buttons').DataTable({
            "order": [[13, "desc"]],  
            "bProcessing": true,
            "sAutoWidth": false,
            "bDestroy":true,
            "sPaginationType": "bootstrap", 
            "bPaginate": false, 
            "bFilter": false, 
            "bInfo": false, 
            "paging": false
        });
        
        var lastSecondColumnIndex = dataTable.columns().count() - 2;
        
        dataTable.order([[lastSecondColumnIndex, "desc"]]).draw();

        dataTable.on('order.dt search.dt', function () {
            dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });


</script>
@endsection