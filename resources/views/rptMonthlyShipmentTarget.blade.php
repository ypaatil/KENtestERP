@extends('layouts.master') 
@section('content')   
@php 
    setlocale(LC_MONETARY, 'en_IN');
@endphp
<style>
    .text-right
    {
      text-align:end;   
    }
</style>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Monthly Shipment Target Plan</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
                            <li class="breadcrumb-item active">Monthly Shipment Target Plan</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
 
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body"> 
                    <div class="col-md-6">
                      <form action="/rptMonthlyShipmentTarget" method="GET" enctype="multipart/form-data">
                          <div class="row m-4"> 
                              <div class="col-md-3"> <label for="fromDate" class="form-label">Month</label><input type="month" name="monthDate" value="{{$monthDate}}" class="form-control" id="monthDate" ></div>
                              <div class="col-sm-2 mt-4"> 
                                 <button type="submit" class="btn btn-primary w-md">Search</button> 
                              </div>
                          </div>
                      </form>
                    </div>
                     <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                     <thead>
                        <tr style="background-color:#eee;">
                             <th class="text-center" nowrap>Sr.No.</th>
                             <th class="text-center" nowrap>Order No</th>
                             <th class="text-center" nowrap>Order Status</th>
                             <th class="text-center" nowrap>Order Type</th>
                             <th class="text-center" nowrap>Buyer Name</th> 
                             <th class="text-center" nowrap>Buyer Brand</th>
                             <th class="text-center" nowrap>Main Style</th>
                             <th class="text-center" nowrap>SAM</th>
                             <th class="text-center" nowrap>Order Rate</th>
                             <th class="text-center" nowrap>Week-1</th>
                             <th class="text-center" nowrap>Week-2</th>
                             <th class="text-center" nowrap>Week-3</th>
                             <th class="text-center" nowrap>Week-4</th>
                             <th class="text-center" nowrap>Target</th>
                             <th class="text-center" nowrap>Target Min</th>
                             <th class="text-center" nowrap>Value</th>
                             <th class="text-center" nowrap>Week 1</th>
                             <th class="text-center" nowrap>Week 2</th>
                             <th class="text-center" nowrap>Week 3</th>
                             <th class="text-center" nowrap>Week 4</th>
                             <th class="text-center" nowrap>Actual Ship</th>
                             <th class="text-center" nowrap>Actual Ship Min</th>
                             <th class="text-center" nowrap>Value</th>
                             <th class="text-center" nowrap>Diff Qty</th>
                             <th class="text-center" nowrap>Diff Value</th>
                             <th class="text-center" nowrap>%</th>
                        </tr>
                     </thead>
                     <tbody>
                      @php
                        $nos = 1;
                        $totalOrderQty1 = 0;
                        $totalOrderQty2 = 0;
                        $totalOrderQty3 = 0;
                        $totalOrderQty4 = 0;
                        
                        function weekOfMonth($date) 
                        {
                            $firstOfMonth = strtotime(date("Y-m-01", $date));
                            return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
                        }
                        
                        function weekOfYear($date) 
                        {
                            $weekOfYear = intval(date("W", $date));
                            if (date('n', $date) == "1" && $weekOfYear > 51) 
                            {
                                return 0;
                            }
                            else if (date('n', $date) == "12" && $weekOfYear == 1) 
                            {
                                return 53;
                            }
                            else 
                            {
                                return $weekOfYear;
                            }
                        }
                     $week1 = 0;
                     $week2 = 0;
                     $week3 = 0;
                     $week4 = 0;
                     @endphp
                     @foreach($salesOrderList as $row) 
                   @php 
                           $monthlyShipementDetails = App\Models\MonthlyShipmentTargetDetailModel::select('*')
                                 ->where('sales_order_no','=', $row->tr_code)
                                 ->where('monthDate', '=', date("Y-m", strtotime($fromDate)))
                                 ->first();
                            if($monthlyShipementDetails!= "")
                            {
                                $week1 = $monthlyShipementDetails->week1;
                                $week2 = $monthlyShipementDetails->week2;
                                $week3 = $monthlyShipementDetails->week3;
                                $week4 = $monthlyShipementDetails->week4;
                            }
                                
                            $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select( 'sale_transaction_detail.sale_date',DB::raw('sum(order_qty) as order_qty'))
                                 ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                                 ->where('sales_order_no','=', $row->tr_code)
                                 ->whereBetween('sale_transaction_master.sale_date', [$fromDate,$toDate])
                                 ->groupBy('sale_transaction_master.sale_code')
                                 ->get();
                           
                            foreach($SaleTransactionDetails as $details)
                            {
                                if(weekOfMonth(strtotime($details->sale_date)) == 1)
                                {
                                    $totalOrderQty1 = $totalOrderQty1 + $details->order_qty;
                                }
                                
                                if(weekOfMonth(strtotime($details->sale_date)) == 2)
                                {
                                    $totalOrderQty2 = $totalOrderQty2 + $details->order_qty;
                                }
                                
                                if(weekOfMonth(strtotime($details->sale_date)) == 3)
                                {
                                    $totalOrderQty3 = $totalOrderQty3 + $details->order_qty;
                                }
                                
                                if(weekOfMonth(strtotime($details->sale_date)) == 4)
                                {
                                    $totalOrderQty4 = $totalOrderQty4 + $details->order_qty;
                                }
                                
                            }
                                $targetQty = $week1 + $week2 + $week3 + $week4;
                                $actualShipped = $totalOrderQty1 + $totalOrderQty2 + $totalOrderQty3 + $totalOrderQty4;
                                if($targetQty > 0)
                                {
                                    $percentage  = sprintf("%.2f",((($targetQty-$actualShipped) * 100)/$targetQty));
                                }
                                else
                                {
                                    $percentage  = 0;
                                }
                                $ActDiffVal = ($targetQty * $row->order_rate) - ($actualShipped * $row->order_rate);
                          
                                
                       @endphp
                       <tr>
                                 <td>{{ $nos++ }}</td>
                                 <td> {{ $row->tr_code  }}</td>
                                 <td> {{ $row->job_status_name  }}</td>
                                 <td>{{ $row->order_type  }} </td>
                                 <td>{{ $row->Ac_name  }}</td> 
                                 <td>{{ $row->brand_name  }}</td>
                                 <td>{{ $row->mainstyle_name }}</td>
                                 <td class="text-right">{{ $row->sam  }}</td>
                                 <td class="text-right">{{ money_format('%!i',$row->order_rate )  }}</td>
                                 <td class="text-right">{{number_format($week1)}}</td>
                                 <td class="text-right">{{number_format($week2)}}</td>
                                 <td class="text-right">{{number_format($week3)}}</td>
                                 <td class="text-right">{{number_format($week4)}}</td>
                                 <td class="text-right">{{number_format($week1 + $week2 + $week3 + $week4)}}</td>
                                 <td class="text-right">{{number_format((($week1 + $week2 + $week3 + $week4) * $row->sam))}}</td>
                                 <td class="text-right">{{number_format(($week1 + $week2 + $week3 + $week4) * $row->order_rate)}}</td>
                                 <td class="text-right">{{number_format($totalOrderQty1)}}</td>
                                 <td class="text-right">{{number_format($totalOrderQty2)}}</td>
                                 <td class="text-right">{{number_format($totalOrderQty3)}}</td>
                                 <td class="text-right">{{number_format($totalOrderQty4)}}</td>
                                 <td class="text-right">{{number_format($actualShipped)}}</td>
                                 <td class="text-right">{{number_format(($actualShipped * $row->sam))}}</td>
                                 <td class="text-right">{{ number_format(($actualShipped * $row->order_rate))}}</td>
                                 <td class="text-right">{{number_format(($targetQty - $actualShipped))}}</td>
                                 <td class="text-right">{{number_format($ActDiffVal)}}</td>
                                 <td class="text-right">{{$percentage}}</td>
                            </tr>
                             @php
                                
                                $week1 = 0;
                                $week2 = 0;
                                $week3 = 0;
                                $week4 = 0;
                                $totalOrderQty1 = 0;
                                $totalOrderQty2 = 0;
                                $totalOrderQty3 = 0;
                                $totalOrderQty4 = 0;
                                
                             @endphp
                            @endforeach
                     </tbody>
                  </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row --> 
    <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  @endsection