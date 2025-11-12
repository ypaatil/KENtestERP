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

 <style>
  table.dataTable thead th { position: relative;  }

  .filter-icon { cursor: pointer; margin-left: 6px;font-size: large;  }

  /* Dropdown */
  .filter-menu {
    display: none;
    position: absolute;
    top: 100%; left: 0;
    z-index: 200;
    background: #fff;
    border: 1px solid #ccc;
    padding: -1px 0 40px 10px;
    max-height: 300px;
    overflow-y: auto;
    width: 260px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  }

  /* ---------- Labels & Checkboxes ---------- */
.filter-menu label {
  display: block;
  margin: 3px 10px;
  font-size: 15px;
  cursor: pointer;
}

.year-block  label {
  display: block;
  margin-top: 3px;  
    margin-right: 10px;  
     margin-bottom: 3px; 
      margin-left:  0px;
  font-size: 15px;
  cursor: pointer;
}

.filter-menu input[type='checkbox'] {
  margin-right: 6px;
}

  .filter-search {
  width: 90%;
  margin: 2px 5% 8px 5%;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
  outline: none;
  font-size: 13px;
}
.filter-search:focus {
  border-color: #007bff;
  box-shadow: 0 0 4px rgba(0,123,255,0.3);
}

  /* Sticky Apply / Clear */
  .filter-actions {
    position: sticky;
    bottom: 0;
    background: #f8f8f8;
    padding: 6px;
    border-top: 1px solid #ccc;
    text-align: right;
  }
  .filter-actions button {
    margin-left: 6px;
    padding: 3px 8px;
    border: 1px solid #aaa;
     
    cursor: pointer;
    border-radius: 4px;
  }

  .filter-actions button:hover { }

  /* Tree Style */
  .tree-toggle { cursor: pointer; font-weight: bold; color: #007bff; margin-right: 6px; font-size: x-large; }
  .tree-line { display: flex; align-items: center; gap: 4px; }

  .month-list, .day-list { margin-left: 18px; }
  .day-list { margin-left: 32px; }
  .collapsed { display: none; }

  tfoot { background-color: #f3f3f3; font-weight: bold; }
  .year-block{ padding-left:10px; }

  /* ✅ Apply Button Style */
    .apply-btn {
      background-color: #0078d7;
      color: #fff;
      box-shadow: 0 2px 4px rgba(0, 120, 215, 0.3);
    }
    .apply-btn:hover {
      background-color: #0078d7;
      box-shadow: 0 3px 6px rgba(0, 120, 215, 0.4);
    }

    /* ❎ Clear Button Style */
    .clear-btn {
      background-color: #eaeaea;
      color: #333;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .clear-btn:hover {
      background-color: #d6d6d6;
      color: #000;
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }
   
</style>

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                     <tr class="text-center" >
                        <th>Sr No </th>
                        <th>Invoice No <span class="filter-icon">🔽</span><div class="filter-menu invno-menu"></div></th>
      <th>Sale Head <span class="filter-icon">🔽</span><div class="filter-menu salehead-menu"></div></th>
      <th>Invoice Date <span class="filter-icon">🔽</span><div class="filter-menu date-menu"></div></th>
      <th>Buyer <span class="filter-icon">🔽</span><div class="filter-menu buyer-menu"></div></th>      
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
        
      // Define a single exportOptions block in one place
      

        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Sales Report (' + formattedDate + ')';
        
       const table = $('#dt').DataTable({
            destroy: true,
            paging: true,
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    exportOptions: commonExportOptions,
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                }
            ]
        });


  // New script added 10-11-2025 
  function unique(arr){ return [...new Set(arr)].sort(); }

  buildAllMenus();
  //updateFooterTotals();

  function buildAllMenus() {
    buildSimpleFilter('.invno-menu', 1);
    buildSimpleFilter('.salehead-menu', 2);
    buildDateFilter();
    buildSimpleFilter('.buyer-menu', 4);
  }

  function buildSimpleFilter(selector, colIndex) {
    const visible = table.rows({ search: 'applied' }).data().toArray();
    const values = unique(visible.map(r=>r[colIndex]));
    let html = `
      <input type='text' class='filter-search' placeholder='Search...'>
      <label><input type='checkbox' class='select-all' checked> Select All</label>
      <div class='options'>`;
    values.forEach(v=>{
      html += `<label><input type='checkbox' class='opt' value='${v}' checked> ${v}</label>`;
    });
    html += `</div>
      <div class='filter-actions'>
        <button class='apply-btn'>Apply</button>
        <button class='clear-btn'>Clear</button>
      </div>`;
    $(selector).html(html);
  }

  function buildDateFilter(){
    const visible = table.rows({ search: 'applied' }).data().toArray();
    const dates = unique(visible.map(r=>r[3]));
    const tree = {};

    dates.forEach(d=>{
      const dt = new Date(d);
      const y = dt.getFullYear();
      const m = dt.toLocaleString('default',{month:'short'});
      if(!tree[y]) tree[y] = {};
      if(!tree[y][m]) tree[y][m] = [];
      tree[y][m].push(d);
    });

    let html = `<input type='text' class='filter-search' placeholder='Search date...'>`;
    Object.keys(tree).forEach(y=>{
      html += `
        <div class='year-block'>
          <div class='tree-line'>
            <span class='tree-toggle' data-target='year-${y}'>+</span>
            <label><input type='checkbox' class='year-check' data-year='${y}' checked> ${y}</label>
          </div>
          <div class='month-list collapsed' id='year-${y}'>`;
      Object.keys(tree[y]).forEach(m=>{
        html += `
          <div class='month-block'>
            <div class='tree-line'>
              <span class='tree-toggle' data-target='month-${y}-${m}'>+</span>
              <label><input type='checkbox' class='month-check' data-year='${y}' data-month='${m}' checked> ${m}</label>
            </div>
            <div class='day-list collapsed' id='month-${y}-${m}'>`;
        tree[y][m].forEach(d=>{
          html += `<label><input type='checkbox' class='date-opt' data-year='${y}' data-month='${m}' value='${d}' checked> ${d}</label>`;
        });
        html += `</div></div>`;
      });
      html += `</div></div>`;
    });

    html += `
      <div class='filter-actions'>
        <button class='apply-btn'>Apply</button>
        <button class='clear-btn'>Clear</button>
      </div>`;
    $('.date-menu').html(html);
  }

  // === UI Events ===
  $('.filter-icon').on('click', function(e){
    e.stopPropagation();
    $('.filter-menu').hide();
    $(this).next('.filter-menu').toggle();
  });
  $(document).on('click', e=>{
    if(!$(e.target).closest('.filter-menu, .filter-icon').length) $('.filter-menu').hide();
  });
  $(document).on('click', '.filter-menu', e=> e.stopPropagation());

  $(document).on('input', '.filter-search', function(){
    const val = $(this).val().toLowerCase();
    $(this).closest('.filter-menu').find('label').each(function(){
      $(this).toggle($(this).text().toLowerCase().includes(val));
    });
  });

  $(document).on('change', '.select-all', function(){
    $(this).closest('.filter-menu').find('.opt').prop('checked', this.checked);
  });

  $(document).on('click', '.tree-toggle', function(){
    const id = $(this).data('target');
    const block = $('#'+id);
    if(block.hasClass('collapsed')){
      block.removeClass('collapsed');
      $(this).text('−');
    } else {
      block.addClass('collapsed');
      $(this).text('+');
    }
  });

  $(document).on('change', '.year-check', function(){
    const y = $(this).data('year');
    $(`.month-check[data-year='${y}'], .date-opt[data-year='${y}']`).prop('checked', this.checked);
  });
  $(document).on('change', '.month-check', function(){
    const y = $(this).data('year');
    const m = $(this).data('month');
    $(`.date-opt[data-year='${y}'][data-month='${m}']`).prop('checked', this.checked);
    const allMonths = $(`.month-check[data-year='${y}']`);
    $(`.year-check[data-year='${y}']`).prop('checked', allMonths.length === allMonths.filter(':checked').length);
  });
  $(document).on('change', '.date-opt', function(){
    const y = $(this).data('year');
    const m = $(this).data('month');
    const allDates = $(`.date-opt[data-year='${y}'][data-month='${m}']`);
    const allChecked = allDates.length === allDates.filter(':checked').length;
    $(`.month-check[data-year='${y}'][data-month='${m}']`).prop('checked', allChecked);
    const allMonths = $(`.month-check[data-year='${y}']`);
    $(`.year-check[data-year='${y}']`).prop('checked', allMonths.length === allMonths.filter(':checked').length);
  });

  $(document).on('click', '.apply-btn', function(){
    const menu = $(this).closest('.filter-menu');
    if(menu.hasClass('invno-menu')) applySimpleFilter(1, menu);
    else if(menu.hasClass('salehead-menu')) applySimpleFilter(2, menu);
    else if(menu.hasClass('buyer-menu')) applySimpleFilter(4, menu);
    else if(menu.hasClass('date-menu')) applyDateFilter(menu);
    $('.filter-menu').hide();
    buildAllMenus();
    updateFooterTotals();
  });

  $(document).on('click', '.clear-btn', function(){
    table.search('').columns().search('').draw();
    buildAllMenus();
    updateFooterTotals();
  });

  function applySimpleFilter(col, menu){
    const vals = menu.find('.opt:checked').map((i,e)=>e.value).get();
    table.column(col).search(vals.length ? vals.join('|') : '❌', true, false).draw();
  }

  function applyDateFilter(menu){
    const vals = menu.find('.date-opt:checked').map((i,e)=>e.value).get();
    table.column(3).search(vals.length ? vals.join('|') : '❌', true, false).draw();
  }

 // Start Function updateFooterTotals
 function updateFooterTotals() {
  const data = table.rows({ search: 'applied' }).data();
  const cols = [5, 6, 7, 8, 9, 10, 11, 12]; // numeric columns
  const totals = Array(cols.length).fill(0);

  for (let i = 0; i < data.length; i++) {
    cols.forEach((c, idx) => {
      let cell = (data[i][c] || "0").toString()
        .replace(/<[^>]*>/g, '') // remove HTML
        .trim()
        .replace(/,/g, '')       // remove commas
        .replace(/[^\d.-]/g, ''); // remove symbols
      const num = parseFloat(cell);
      if (!isNaN(num)) totals[idx] += num;
    });
  }

  const footerCells = $('#dt tfoot th');
  cols.forEach((c, idx) => {
    let value;
    if (c === 7) {
     
      value = 0.0;
      if (totals[0]  > 0){
      const calculateV= totals[3] / totals[0] ;
      value = calculateV.toFixed(2); 
      }

    } else {       
      value = totals[idx].toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
    $(footerCells[c]).text(value);
  }); 
  }
// end Function updateFooterTotals
  table.on('draw', updateFooterTotals);

  });  
</script>

 


@endsection