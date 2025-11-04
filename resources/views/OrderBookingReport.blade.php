@extends('layouts.master') 
@section('content')  
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .btn-icon {
        display: flex;
        align-items: center;
    }
    .btn-icon i {
        margin-right: 5px;
    }
    
    @media (max-width: 600px) {
        .breadcumbCls {
            display: none;
        }
        
        .navbar-header {
            background: #703eb385;
        }
        .titleCls { 
            text-align: center;
        }
        
        #vertical-menu-btn {
            display: none;
        }
    }
 
    td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #f1c40f38;
        color: black;
        font-weight: 900;
    }

    caption {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .table>thead>tr {
        vertical-align: middle;
        background-color: #024A8E!important;
        color: #fff;
    }
     
    thead {  
        background-color: #024A8E; /* Adjust color as needed */
        color: #fff; /* Adjust text color as needed */ 
        border-bottom: 2px solid #ddd; /* Optional: Add border to distinguish header */
    } 
    
    /*thead.sticky {*/
    /*    position: fixed;*/
    /*    top: 70px;    */
    /*    z-index: 1000;*/
    /*}*/
    thead.sticky {
            position: sticky;
            top: 0; /* Start at the top initially */
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            z-index: 1000;
        }
</style> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="page-title-box d-sm-flex align-items-center justify-content-between titleCls">
    <div class="col-8">
        <h4 class="mb-sm-0 font-size-18">Order Booking Report</h4>
    </div>
</div>
 
<form method="GET" action="{{ route('OrderBookingReport') }}" class="form-inline">
    <div class="col-md-12">
        <div class="row">
                <div class="col-md-2 form-group mb-2">
                    <label for="from_date" class="sr-only">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $from_date }}">
                </div>
                <div class="col-md-2 form-group mx-sm-3 mb-2">
                    <label for="to_date" class="sr-only">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $to_date }}">
                </div>
                <div class="col-md-2 form-group mx-sm-3 mb-2">
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </div>
                <div class="col-md-2 form-group mx-sm-3 mb-2">
                    <button id="exportButton" class="btn btn-primary">Export to Excel</button>
                </div>
        </div> 
    </div> 
</form>
<div class="row">
    <div class="col-12">
        <div class="card parent-container">
            <div class="card-body table-responsive table-wrapper">
                <table data-page-length='100' id="sales_costing_table" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="sticky">
                        <tr class="text-center">
                            <th>Sr No</th>
                            <th>Month</th>
                            <th>Date</th>
                            <th>Customer</th> 
                            <th>Brand</th>
                            <th>Style</th>
                            <th colspan="3">Order Details</th> 
                            <th>CMOHP</th>
                            <th>FOB</th>
                            <th>Value</th> 
                        </tr>
                        <tr class="text-center">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <th></th>
                            <th></th>
                            <th>L PCS</th> 
                            <th>SAM</th> 
                            <th>L Min</th>
                            <th></th>
                            <th></th>
                            <th>(₹ in Lakhs)</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $srno = 1;
                            $grand_total = 0;
                            $grand_min = 0;
                            $grand_value = 0;
                            $grand_fob = 0;
                            $grand_cmohp = 0;
                            $grand_value1 = 0;
                    
                            $monthly_totals = [];
                            foreach ($Buyer_Purchase_Order_List as $row) {
                                $timestamp = strtotime($row->order_received_date);
                                $currentMonth = date("F", $timestamp);
                                if (!isset($monthly_totals[$currentMonth])) {
                                    $monthly_totals[$currentMonth] = [
                                        'count' => 0,
                                        'total_qty' => 0,
                                        'sam' => 0,
                                        'order_min' => 0,
                                        'order_value' => 0,
                                        'order_value1' => 0,
                                        'cmohp_per' => 0,
                                        'cost_value' => 0
                                    ];
                                }
                                $monthly_totals[$currentMonth]['count']++;
                            }
                    
                            $previousMonth = '';
                            $currentMonth = '';
                            $monthly_counter = 0;
                            $monthly_middle_index = 0;
                        @endphp
                        @foreach($Buyer_Purchase_Order_List as $row)
                        @php  
                            
                            $cmohp1 = round($row->cmohp,2);
                           
                            $timestamp = strtotime($row->order_received_date);
                            $currentMonth = date("F", $timestamp);
                    
                            $monthly_totals[$currentMonth]['total_qty'] += $row->total_qty;
                            $monthly_totals[$currentMonth]['sam'] += ($row->order_min/$row->total_qty) ? ($row->order_min/$row->total_qty) : 0;
                            $monthly_totals[$currentMonth]['order_min'] += $row->order_min;
                            $monthly_totals[$currentMonth]['order_value'] += $row->order_value;
                            $monthly_totals[$currentMonth]['order_value1'] += $row->order_value1;
                           
                            if($cmohp1 > 0 && $row->order_min > 0)
                            {
                                $CMOHP_per = $cmohp1;
                            }
                            else
                            {
                                $CMOHP_per = 0;
                            }
                             
                            $monthly_totals[$currentMonth]['cmohp_per'] += $CMOHP_per;
                            $monthly_totals[$currentMonth]['cost_value'] += $row->order_value/$row->total_qty;
                    
                            $monthly_middle_index = ceil($monthly_totals[$currentMonth]['count'] / 2);
                        @endphp
                    
                        @if ($previousMonth && $previousMonth != $currentMonth)
                            <tr style="border: 2px solid black;">
                                <td colspan="6" class="text-right"><b>Total for {{$previousMonth}}</b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['total_qty']/100000)}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_min']/100000)}}</b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value']/$monthly_totals[$previousMonth]['order_min'])}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value']/100000)}}</b></td>
                            </tr>
                            @php
                                $monthly_counter = 0;
                            @endphp
                            <tr style="border: 2px solid black;">
                                <td colspan="6" class="text-right"><b>Average for {{$previousMonth}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_min'] / $monthly_totals[$previousMonth]['total_qty'])}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value'] / $monthly_totals[$previousMonth]['total_qty'])}}</b></td>
                                <td class="text-right"><b></b></td>
                            </tr>
                        @endif
                    
                        <tr>
                            <td>{{$srno++}}</td>
                            @if ($previousMonth != $currentMonth || $monthly_counter == $monthly_middle_index)
                                <td rowspan="{{ $monthly_totals[$currentMonth]['count'] }}" class="text-center" style="vertical-align: middle;">{{$currentMonth}}</td>
                                @php
                                    $previousMonth = $currentMonth;
                                   
                                @endphp
                            @endif
                            <td>{{date("d-m-Y", strtotime($row->order_received_date))}}</td>
                            <td>{{$row->ac_short_name}}</td>
                            <td>{{$row->brand_name}}</td>
                            <td nowrap>{{$row->mainstyle_name}}</td>
                            <td nowrap class="text-right">{{sprintf("%.2f", $row->total_qty/100000)}}</td>
                            <td nowrap class="text-right">{{sprintf("%.2f", ($row->order_min/$row->total_qty) ? ($row->order_min/$row->total_qty) : 0)}}</td>
                            <td nowrap class="text-right">{{sprintf("%.2f", $row->order_min/100000)}}</td>
                            <td class="text-right">{{ $CMOHP_per }}</td>
                            <td class="text-right">{{ sprintf("%.2f", $row->order_value/$row->total_qty)}}</td>
                            <td class="text-right">{{sprintf("%.2f", $row->order_value/100000)}}</td>
                        </tr>
                    
                        @php
                            $grand_total += $row->total_qty;
                            $grand_min += $row->order_min;
                            $grand_value += $row->order_value;
                            $grand_value1 += $row->order_value1;
                            $grand_fob += $row->order_value/$row->total_qty;
                            $grand_cmohp += $CMOHP_per;
                    
                            $monthly_counter;
                        @endphp
                        @endforeach
                    
                        @if ($previousMonth)
                            <tr style="border: 2px solid black;">
                                <td colspan="6" class="text-right"><b>Total for {{$previousMonth}}</b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['total_qty']/100000)}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_min']/100000)}}</b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value1']/$monthly_totals[$previousMonth]['order_min'])}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value']/100000)}}</b></td>
                            </tr>
                            <tr style="border: 2px solid black;border-bottom: 3px solid black;">
                                <td colspan="6" class="text-right"><b>Average for {{$previousMonth}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_min'] / $monthly_totals[$previousMonth]['total_qty'])}}</b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b></b></td>
                                <td class="text-right"><b>{{sprintf("%.2f", $monthly_totals[$previousMonth]['order_value'] / $monthly_totals[$previousMonth]['total_qty'])}}</b></td>
                                <td class="text-right"><b></b></td>
                            </tr>
                        @endif
                        <tr style="border: 2px solid black; font-weight: bold;">
                            <td colspan="6" class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b>{{sprintf("%.2f", $grand_total/100000)}}</b></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b>{{sprintf("%.2f", $grand_min/100000)}}</b></td>
                            <td class="text-right"><b>{{sprintf("%.2f",$grand_value1/$grand_min)}}</b></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b>{{sprintf("%.2f", $grand_value/100000)}}</b></td>
                        </tr>
                        <tr style="border: 2px solid black;border-bottom: 3px solid black;">
                            <td colspan="6" class="text-right"><b>Grand Average</b></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b>{{sprintf("%.2f", $grand_min/$grand_total)}}</b></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b>{{sprintf("%.2f", $grand_value / $grand_total)}}</b></td>
                            <td class="text-right"><b></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


<script> 
    $(document).ready(function() {
      $('#exportButton').click(function() {
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet($('#sales_costing_table')[0]);
    
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    
        // Generate Excel file
        XLSX.writeFile(wb, 'Order_Booking_Report.xlsx');
      });
    });

   $(window).scroll(function() { 
        var scrollTop = $(this).scrollTop();
        var offsetTop = 0;   
        $('.sticky').css('top', Math.max(offsetTop + scrollTop - 155, 0) + 'px');
    });

</script>
