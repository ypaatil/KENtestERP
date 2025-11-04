@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<style>
    .hide
    {
        display:none;
    }
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Finishing Inward Outward Detail Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Finishing Inward Detail Summary Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/FinishingInwardOutwardDetailReport" method="GET">
              <div class="row">  
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div>  
                  <div class="col-md-4 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/FinishingInwardOutwardDetailReport" class="btn btn-warning">Clear</a> 
                  </div>
                  <div class="col-md-2 mt-4">  
                        <button type="button" onclick="ShowAll();" class="btn btn-success" id="compl" >Balance Qty is <b>Not</b> Zero</button>
                  </div>
              </div>
          </form>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
                <div class="row"> 
                        <div class="col-md-2 form-group mx-sm-3 mb-2">
                            <button id="exportButton" class="btn btn-primary" style="background:#74788d">Export to Excel</button>
                        </div>
                </div> 
              <table id="dt" class="table table-bordered">
                    <thead>
                        <tr style="text-align:center"> 
                            <th>Outward Date</th>
                            <th>Sales Order No</th>
                            <th>Buyer</th>
                            <th>Brand</th>
                            <th>Style</th>
                            <th>Vendor Name</th>
                            <th>DC No.</th>
                            <th>Outward Qty</th>
                            <th>Inward Date</th>
                            <th>Inward Qty</th>
                            <th>Balance Qty</th> 
                            <th>Remark(Inward)</th> 
                        </tr> 
                    </thead>
                    <tbody>
                     @php
                        $srno = 1;
                        $total_inward = 0;
                        $rowspans = [];
                        $rowspans1 = [];
                        $subtotal = [];
                        $previous_inout = []; // To carry forward inout value for duplicate rows
                    
                        // Pre-calculate the rowspan and subtotal for each ofp_code
                        foreach ($inwardData as $row) {
                            if (!isset($rowspans[$row->ofp_code])) {
                                $rowspans[$row->ofp_code] = 1;
                                $subtotal[$row->ofp_code] = ['inward_qty' => 0, 'inout' => 0];
                                $previous_inout[$row->ofp_code] = 0; // Initialize previous inout
                            } else {
                                $rowspans[$row->ofp_code]++;
                            }
                    
                            if (!isset($rowspans1[$row->ofp_code])) {
                                $rowspans1[$row->ofp_code] = 1;
                                $subtotal[$row->ofp_code] = ['inward_qty' => 0, 'inout' => 0];
                                $previous_inout[$row->ofp_code] = 0; // Initialize previous inout
                            } else {
                                $rowspans1[$row->ofp_code]++;
                            }
                            
                            $outwardData = DB::select("SELECT SUM(outward_for_packing_size_detail2.size_qty) as outward_qty
                                                        FROM outward_for_packing_size_detail2 
                                                        WHERE ofp_code='".$row->ofp_code."' 
                                                        GROUP BY outward_for_packing_size_detail2.ofp_code");
                    
                            $outward_qty1 = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0;
                    
                            $inData = DB::select("SELECT SUM(inward_for_packing_size_detail2.size_qty) as inward_qty
                                                    FROM inward_for_packing_size_detail2 
                                                    WHERE ofp_code='".$row->ofp_code."' AND ifp_date='".$row->date."'");
                    
                            $inward_qty1 = isset($inData[0]->inward_qty) ? $inData[0]->inward_qty : 0;
                            if ($rowspans1[$row->ofp_code] != 1) 
                            { 
                                $subtotal[$row->ofp_code]['inward_qty'] += $inward_qty1;
                            }
                            else
                            {
                                $subtotal[$row->ofp_code]['inward_qty'] += 0;
                            }
                            
                        }
                    @endphp
                    
                    @foreach($inwardData as $index => $row)
                        @php 
                            $outwardData = DB::select("SELECT ofp_date, SUM(outward_for_packing_size_detail2.size_qty) as outward_qty
                                                        FROM outward_for_packing_size_detail2 
                                                        WHERE ofp_code='".$row->ofp_code."'");
                    
                            $outward_qty1 = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0;
                            $ofp_date1 = isset($outwardData[0]->ofp_date) ? $outwardData[0]->ofp_date : '';
                            $ofp_date = $ofp_date1;
                            $outward_qty = $outward_qty1;
                    
                            $inData = DB::select("SELECT SUM(inward_for_packing_size_detail2.size_qty) as inward_qty
                                                    FROM inward_for_packing_size_detail2 
                                                    WHERE ofp_code='".$row->ofp_code."' AND ifp_code='".$row->ifp_code."' AND ifp_date='".$row->date."'");
                                                    
                            $inward_qty1 = isset($inData[0]->inward_qty) ? $inData[0]->inward_qty : 0;
                    
                            // Adjust $inout for duplicate rows
                            if ($rowspans[$row->ofp_code] == 1) {
                                $inout = $outward_qty1 - $inward_qty1; // First row calculation
                                $previous_inout[$row->ofp_code] = $inout; // Save inout for next rows
                            } else {
                                $inout =  $outward_qty1 - $inward_qty1 - $previous_inout[$row->ofp_code];
                                $previous_inout[$row->ofp_code] = $inout; // Update previous inout
                            }
                        @endphp
                    
                        <tr class="{{$row->ofp_code}}  out_qty_{{$inout}}">
                            @if($rowspans[$row->ofp_code] > 0)
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{ date("d-m-Y", strtotime($ofp_date)) }}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->sales_order_no}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->buyer}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->brand_name}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->style_no}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->vendor_name}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{$row->ofp_code}}
                                </td>
                                <td style="white-space:nowrap;vertical-align: middle;" class="text-right" rowspan="{{ $rowspans[$row->ofp_code] }}">
                                    {{ number_format($outward_qty, 0, ',', ',') }}
                                </td>
                                @php $rowspans[$row->ofp_code] = 0; @endphp
                            @endif
                            @if($inward_qty1 > 0)
                            <td style="white-space:nowrap">{{ date("d-m-Y", strtotime($row->date)) }}</td>
                            @else
                             <td style="white-space:nowrap"></td>
                            @endif
                            <td style="white-space:nowrap" class="text-right in_{{$row->ofp_code}}">
                                {{ number_format($inward_qty1, 0, ',', ',') }}
                            </td>
                            <td style="white-space:nowrap" class="text-right">
                                {{ number_format(abs($inout), 0, ',', ',') }}
                            </td>
                             <td style="white-space:nowrap">{{$row->narration}}</td>
                        </tr>
                    
                        @if(($index + 1 == count($inwardData) || $row->ofp_code != $inwardData[$index + 1]->ofp_code) && $subtotal[$row->ofp_code]['inward_qty'] > $inward_qty1)
                            <tr style="font-weight:bold; text-align:center; background-color:#f8f9fa;">
                                <td colspan="9" style="text-align:right;">Total:</td>
                                <td class="text-right">{{ number_format($subtotal[$row->ofp_code]['inward_qty'], 0, ',', ',') }}</td>
                                <td class="text-right">-</td>
                            </tr>
                        @endif
                    
                        @php 
                            $total_inward += $inward_qty1;
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script>
     
   function ShowAll() 
   {
        if ($(".out_qty_0").hasClass('hide')) 
        {
            $(".out_qty_0").removeClass('hide');
            $("#compl").html("Balance Qty is Zero");
        } 
        else 
        {
            $(".out_qty_0").addClass('hide');
            $("#compl").html('Balance Qty is <b>Not</b> Zero');
        }
    }

    $(document).ready(function() 
    {
        $(".out_qty_0").addClass('hide');
    });
    
    function ShowCompleted() 
    {
        if ($(".complete").hasClass("hide")) 
        {
            $(".complete").removeClass("hide");
        } 
        else 
        {
            $(".complete").addClass("hide");
        }
    }

    
    function formatIndianNumber(value) 
    {
            // Convert the value to string and split into integer and decimal parts
            var x = value.toString().split('.');
            var intPart = x[0];
            var decimalPart = x.length > 1 ? '.' + x[1] : '';
            
            // Format integer part for Indian numbering system
            var lastThree = intPart.substring(intPart.length - 3);
            var otherNumbers = intPart.substring(0, intPart.length - 3);
            if (otherNumbers !== '') {
                lastThree = ',' + lastThree;
            }
            var formattedIntPart = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;

            return formattedIntPart + decimalPart;
    }
    
    // $(document).ready(function () {
    //     var classRowMap = {};
    
    //     // Merge cells based on class names
    //     $('table tbody tr').each(function () {
    //         var $currentRow = $(this);
    //         var classNames = $currentRow.attr('class') ? $currentRow.attr('class').split(' ') : [];
    //         classNames.forEach(function (className) {
    //             if (!classRowMap[className]) {
    //                 classRowMap[className] = $currentRow;
    //             } else {
    //                 // If a duplicate is found, merge cells by adjusting rowspan
    //                 var $targetRow = classRowMap[className];
    
    //                 var $previousRow = $currentRow.prev();
    //                 var bal_qty = parseFloat($previousRow.find('td:eq(5)').text()) || 0;
    //                 var inward_qty = parseFloat($currentRow.find('td:eq(4)').text()) || 0;
    
    //                 var $firstCell = $currentRow.find('td:eq(0)');
    //                 var $firstCell1 = $currentRow.find('td:eq(1)');
    //                 var $firstCell2 = $currentRow.find('td:eq(2)');
    //                 var $existingCell = $targetRow.find('td:eq(0)');
    //                 // var rowspan = parseInt($existingCell.attr('rowspan')) || 1;
    //                 // $existingCell.attr('rowspan', rowspan + 1);
    
    //                 // var $existingCell1 = $targetRow.find('td:eq(1)');
    //                 // var rowspan1 = parseInt($existingCell1.attr('rowspan')) || 1;
    //                 // $existingCell1.attr('rowspan', rowspan1 + 1);
    
    //                 // var $existingCell2 = $targetRow.find('td:eq(2)');
    //                 // var rowspan2 = parseInt($existingCell2.attr('rowspan')) || 1;
    //                 // $existingCell2.attr('rowspan', rowspan2 + 1);
    
    //                 // $existingCell.css('vertical-align', 'middle');
    //                 // $existingCell1.css('vertical-align', 'middle');
    //                 // $existingCell2.css('vertical-align', 'middle');
    
    //                 // // // You can choose to hide these cells instead of removing them
    //                 // $firstCell.remove();
    //                 // $firstCell1.remove();
    //                 // $firstCell2.remove();
    
    //                 // Update the remaining balance quantity
    //                 var new_bal_qty = bal_qty - inward_qty;
    //                 $currentRow.find('td:eq(2)').text(new_bal_qty);    
    //                 var total_inward = 0; 
    //                 $(".in_"+className).each(function()
    //                 {
    //                     total_inward += parseFloat($(this).text() || 0); 
    //                 });
                    
                 
    //                 var $totalRow = $('<tr class="total-row"></tr>');
    //                 $totalRow.append('<td colspan="4" style="text-align:right; font-weight:bold;">Total</td>');
    //                 $totalRow.append('<td style="font-weight:bold;text-align:right;">' + formatIndianNumber(total_inward) + '</td>');
    //                 $totalRow.append('<td style="font-weight:bold;text-align:right;">-</td>');
                
    //                 $currentRow.after($totalRow);
    //             }
    //         });
    //     });
    // }); 

    $('#exportButton').click(function() {
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet($('#dt')[0]);
    
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    
        // Generate Excel file
        XLSX.writeFile(wb, 'Finishing_inward_outward_Report.xlsx');
    }); 
    


</script>
@endsection