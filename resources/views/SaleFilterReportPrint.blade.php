@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
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
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!.0n',round($SaleTotal[0]->TotalQty))}} </h4>
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
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!.0n',round($SaleTotal[0]->TotalGross))}} </h4>
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
                  <h4 class="mb-0" style="color:#fff;" >{{money_format('%!.0n',round($SaleTotal[0]->TotalGst))}} </h4>
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
                  <h4 class="mb-0" style="color:#fff;"> {{money_format('%!.0n',round($SaleTotal[0]->TotalNet))}} </h4>
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
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/SaleFilterReport" method="GET" enctype="multipart/form-data">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="fromDate" class="form-label">From date</label>
                            <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}" required> 
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}" required>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sales_head_id" class="form-label">Sale Head</label> 
                            <select name="sales_head_id[]" class="form-control select2" id="sales_head_id" multiple>
                                <option value="">--Select--</option>
                                @foreach($salesHeadlist as $row)
                                    <option value="{{ $row->sales_head_id }}"
                                        {{ in_array($row->sales_head_id, $sales_head_id ?? []) ? 'selected' : '' }}>
                                        {{ $row->sales_head_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="Ac_code" class="form-label">Buyer Name</label> 
                           <select name="Ac_code" class="form-control select2" id="Ac_code">
                            <option value="">--Buyer--</option>
                                @foreach($LedgerList as  $row) 
                                    <option value="{{ $row->ac_code }}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{ $row->ac_short_name }}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="sale_code" class="form-label">Invoice No</label> 
                           <select name="sale_code" class="form-control select2" id="sale_code">
                            <option value="">--Select--</option>
                                @foreach($invoiceList as  $row) 
                                    <option value="{{ $row->sale_code }}"  {{ $row->sale_code == $sale_code ? 'selected="selected"' : '' }} >{{ $row->sale_code }}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div> 
                <div class="col-sm-6">
                    <label for="formrow-inputState" class="form-label"></label>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md">Search</button>
                        <a href="/SaleFilterReport" class="btn btn-danger w-md">Cancel</a>
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
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                     <tr class="text-center" >
                        <th>Sr No</th>
                        <th>Invoice No</th>
                        <th>Sale Head</th>
                        <th>Invoice Date</th>
                        <th>Buyer</th>
                        <th>Total Qty</th>
                        <th>Total Min</th>
                        <th>CMOHP</th>
                        <th>CMOHP Value</th>
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
                     $total_cmohp = 0;
                     $total_CMOHP_value=0;
                     $no=1;
                     @endphp
                     @foreach($SaleTransactionMasterList as $row)    
                     <tr>
                        @php 
                       
                           // $number=intval(substr($row->sale_code,15,50)); 
                            
                            if($DFilter == 'd')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_master.sales_head_id != 10 AND sale_transaction_detail.sale_date ='".date('Y-m-d')."' AND sale_transaction_detail.sale_code='".$row->sale_code."'");          
                            }
                            else if($DFilter == 'm')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_master.sales_head_id != 10 AND MONTH(sale_transaction_detail.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_detail.sale_date)=YEAR(CURRENT_DATE()) AND sale_transaction_detail.sale_code='".$row->sale_code."'"); 
                            }
                            else if($DFilter == 'y')
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.sale_date between (select fdate from financial_year_master 
                                    where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3) AND sale_transaction_detail.sale_code='".$row->sale_code."'");
                            }
                            else
                            {
                                $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                                    from sale_transaction_detail  
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                                    where sale_transaction_master.sales_head_id != 10 AND sale_transaction_detail.sale_date ='".$row->sale_date."' AND sale_transaction_detail.sale_code='".$row->sale_code."'");   
                            }
        
                            $costingData = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                            INNER JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                            INNER JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                            INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                            WHERE sale_transaction_detail.sale_code='".$row->sale_code."' AND sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                            AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 AND og_id !=4");

                                            
                            $cmohp = 0;
                            $cmohp_value = 0;
                            $cmohpmain = 0;
                            foreach($costingData as $costing)
                            {
                                $total_cost_value = isset($costing->total_cost_value) ? $costing->total_cost_value : 0;
                                $other_value = isset($costing->other_value) ? $costing->other_value : 0;
                                $order_rate = isset($costing->order_rate) ? $costing->order_rate : 0;
                                $production_value = isset($costing->production_value) ? $costing->production_value : 0;
                                
                                $profit_value=0.00;
                                $profit_value = ($order_rate - $total_cost_value);
                              
                                $cmohp1 = $production_value + $profit_value + $other_value;
                                $cmohp2 = $costing->sam;
                                if($cmohp1 && $cmohp2)
                                {
                                    $cmohp = $cmohp1/$cmohp2;
                                }
                                else
                                {
                                    $cmohp = 0;
                                }
                           
                              $cmohpmain = $cmohp;
                           
                            $cmohp_value += $costing->order_qty*$cmohpmain;
        
                            }
                            
                            if($cmohp_value > 0 && $row->total_qty > 0)
                            { 
                                $cmohp_per_min = $cmohp_value/$row->total_qty;
                            }
                            else
                            {
                                $cmohp_per_min = 0;
                            }
                        @endphp
                        <th style="text-align:right; white-space:nowrap">{{$no }}</th>
                        <td style="text-align:left;" nowrap>{{ $row->sale_code }}</td>
                        <td style="text-align:left; white-space:nowrap" nowrap>{{ $row->sales_head_name }}</td>
                        <td style="text-align:center; white-space:nowrap" nowrap>{{ date("d-M-Y", strtotime($row->sale_date)) }}</td>
                        <td style="text-align:left; white-space:nowrap">{{ $row->ac_name1 }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n",$row->total_qty) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n",isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ sprintf('%.2f', round($cmohp_per_min,2)) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n", round($cmohp_value)) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n",$row->Gross_amount) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ number_format($row->freight_charges) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n",$row->Gst_amount) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ money_format("%!.0n",$row->Net_amount) }}</td>
                        <td style="text-align:right; white-space:nowrap">{{ $row->narration }}</td>
                     </tr>
                     @php
                     $totalQty = $totalQty + $row->total_qty;
                     $totalMin = $totalMin + (isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0);
                     $TotalGross= $TotalGross + $row->Gross_amount ;
                     $totalGST= $totalGST + $row->Gst_amount;
                     $totalNet= $totalNet + $row->Net_amount;
                     $totalFreight= $totalFreight + $row->freight_charges;
                     $total_CMOHP_value = $total_CMOHP_value + $cmohp_value;
                     $no=$no+1;
                     
                     if($total_CMOHP_value > 0 && $totalQty > 0)
                     { 
                         $total_cmohp = $total_CMOHP_value/$totalQty;
                     }
                     else
                     {
                        $total_cmohp = 0;
                     }
                     @endphp
                     @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right; white-space:nowrap">Total </th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n",$totalQty)}}</th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n",$totalMin)}}</th>
                        <th style="text-align:right; white-space:nowrap">{{sprintf('%.2f', round($total_cmohp,2))}}</th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n", round($total_CMOHP_value))}}</th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n",$TotalGross)}}</th>
                        <th style="text-align:right; white-space:nowrap">{{number_format($totalFreight)}}</th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n",$totalGST)}}</th>
                        <th style="text-align:right; white-space:nowrap">{{money_format("%!.0n",$totalNet)}}</th>
                        <th style="text-align:right; white-space:nowrap"></th>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    
    $(document).ready(function() 
    {
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().clear().destroy();
        } 
        
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Sales Report (' + formattedDate + ')';
        
        $('#dt').DataTable({
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle
                }
            ]

        });
    });
</script>
@endsection