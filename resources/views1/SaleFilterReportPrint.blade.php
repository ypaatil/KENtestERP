@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sale Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sale Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Qty</p>
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',$SaleTotal[0]->TotalQty)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Gross</p>
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',round($SaleTotal[0]->TotalGross,2))}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Total GST</p>
                  <h4 class="mb-0" style="color:#fff;" >{{money_format('%!i',round($SaleTotal[0]->TotalGst,2))}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Net Amount</p>
                  <h4 class="mb-0" style="color:#fff;"> {{money_format('%!i',round($SaleTotal[0]->TotalNet,2))}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table  id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                     <tr>
                        <th>SrNo</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Buyer</th>
                        <th>GST</th>
                        <th>Total Qty</th>
                        <th>Total Minutes</th>
                        <th>Gross Amount</th>
                        <th>Freight Charges</th>
                        <th>GST Amount</th>
                        <th>Net Amount</th>
                        <th>Narration</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php 
                     
                     $TotalGross=0;
                     $totalGST=0;
                     $totalNet=0;
                     $totalFreight=0;
                     $totalQty=0;
                     $totalMin=0;
                     
                     @endphp
                     @foreach($SaleTransactionMasterList as $row)    
                     <tr>
                        @php 
                        
                            $number=intval(substr($row->sale_code,15,50)); 
                            
                            if($DFilter == 'd')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_detail.sale_date ='".date('Y-m-d')."' AND sale_transaction_detail.sale_code='".$row->sale_code."'");          
                            }
                            else if($DFilter == 'm')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where  MONTH(sale_transaction_detail.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_detail.sale_date)=YEAR(CURRENT_DATE()) AND sale_transaction_detail.sale_code='".$row->sale_code."'"); 
                            }
                            else if($DFilter == 'y')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where  sale_transaction_master.sale_date between (select fdate from financial_year_master 
                                    where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3) AND sale_transaction_detail.sale_code='".$row->sale_code."'");
                            }
                            else
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_detail.sale_date ='".$row->sale_date."' AND sale_transaction_detail.sale_code='".$row->sale_code."'");   
                            }
        
                            
                        @endphp
                        <th>{{$number }}</th>
                        <td  nowrap>{{ $row->sale_code }}</td>
                        <td  nowrap>{{ $row->sale_date }}</td>
                        <td  nowrap>{{ $row->ac_name1 }}</td>
                        <td  nowrap>{{ $row->tax_type_name }}</td>
                        <td>{{ number_format($row->total_qty) }}</td>
                        <td>{{ number_format(isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0) }}</td>
                        <td>{{ number_format($row->Gross_amount) }}</td>
                        <td>{{ number_format($row->freight_charges) }}</td>
                        <td>{{ number_format($row->Gst_amount) }}</td>
                        <td>{{ number_format($row->Net_amount) }}</td>
                        <td>{{ $row->narration }}</td>
                     </tr>
                     @php
                     $totalQty = $totalQty + $row->total_qty;
                     $totalMin = $totalMin + (isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0);
                     $TotalGross= $TotalGross + $row->Gross_amount ;
                     $totalGST= $totalGST + $row->Gst_amount;
                     $totalNet= $totalNet + $row->Net_amount;
                     $totalFreight= $totalFreight + $row->freight_charges;
                     @endphp
                     @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <th></th>
                        <th> </th>
                        <th> </th>
                        <th></th>
                        <th>Total </th>
                        <th>{{number_format($totalQty)}}</th>
                        <th>{{number_format($totalMin)}}</th>
                        <th>{{number_format($TotalGross)}}</th>
                        <th>{{number_format($totalFreight)}}</th>
                        <th>{{number_format($totalGST)}}</th>
                        <th>{{number_format($totalNet)}}</th>
                        <th></th>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection