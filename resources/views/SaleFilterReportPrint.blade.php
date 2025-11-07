@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
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
  
 .filter-icon {
    cursor: pointer;
    margin-left: 6px;
    color: #555;
  }
  .filter-menu {
    display: none;
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 1000;
    top: 100%;
    left: 0;
    width: 250px;
  }
  .filter-menu input[type="text"] {
    width: 100%;
    padding: 4px 6px;
    margin-bottom: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  .filter-options {
    max-height: 150px;
    overflow-y: auto;
    margin-bottom: 8px;
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
                        <th>Invoice No <span class="filter-icon">🔽</span></th>
                        <th>Sale Head <span class="filter-icon">🔽</span></th>
                        <th>Invoice Date <span class="filter-icon">🔽</span></th>
                        <th>Buyer <span class="filter-icon">🔽</span></th>
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
      const commonExportOptions = {
         columns: ':visible',
         modifier: { search: 'applied' },
         format: {
           header: function (data, columnIdx) {
                           const div = document.createElement("div");
                           div.innerHTML = data;

                           // Remove any filter dropdown content
                           const filterMenus = div.querySelectorAll('.filter-menu');
                           filterMenus.forEach(el => el.remove());

                           const filterMenusicon = div.querySelectorAll('.filter-icon');
                           filterMenusicon.forEach(el => el.remove());
                           

                           // Remove icons if present
                           const icons = div.querySelectorAll('i, svg');
                           icons.forEach(el => el.remove());

                           return div.textContent.trim() || div.innerText.trim() || "";
                           }
         }
      };

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
    });
</script>



<script>
$(document).ready(function() {
  // ASSUME: Your DataTable already initialized above and assigned to `table` or re-get it here:
  var table = $('#dt').DataTable();

  // --- Config ---
  var dateColIndex = 3; // Invoice Date column index (0-based) - update if different

  // Create a filter menu container inside the header cell for Invoice Date
  var $dateTh = $('#dt thead th').eq(dateColIndex);
  var $dateMenu = $('<div class="filter-menu" style="min-width:260px;"></div>');
  $dateTh.css('position','relative').append($dateMenu);

  // Build UI: Year / Month / Date sections with checkboxes
  var $yearWrap = $('<div><strong>Years</strong><div class="year-list" style="max-height:120px; overflow:auto;"></div></div>').appendTo($dateMenu);
  var $monthWrap = $('<div style="margin-top:8px;"><strong>Months</strong><div class="month-list" style="max-height:120px; overflow:auto;"></div></div>').appendTo($dateMenu);
  var $dateWrap = $('<div style="margin-top:8px;"><strong>Dates</strong><div class="date-list" style="max-height:140px; overflow:auto;"></div></div>').appendTo($dateMenu);
  var $btnArea = $('<div class="d-flex justify-content-between" style="margin-top:8px;">' +
                    '<button class="btn btn-sm btn-primary apply-date-filter">Apply</button>' +
                    '<button class="btn btn-sm btn-secondary clear-date-filter">Clear</button>' +
                    '</div>').appendTo($dateMenu);

  // Collect unique dates from the column and group Year -> Month -> Dates
  var rawDates = table.column(dateColIndex).data().toArray();
  var grouped = {}; // { year: { monthIndex: { monthName: [dateStrs...] } } }

  rawDates.forEach(function(d) {
    if (!d) return;
    // Input format: 25-Apr-2011  (PHP date("d-M-Y"))
    var m = moment(d, 'DD-MMM-YYYY', true); // strict parse
    if (!m.isValid()) {
      // try some other common formats if needed
      m = moment(d, ['YYYY-MM-DD','DD/MM/YYYY','D-M-YYYY'], true);
      if (!m.isValid()) return;
    }
    var y = m.format('YYYY');
    var moIndex = parseInt(m.format('MM'), 10); // 1..12
    var moName = m.format('MMMM'); // e.g., "April"
    var dateFull = m.format('DD-MMM-YYYY'); // keep same format like shown
    grouped[y] = grouped[y] || {};
    grouped[y][moIndex] = grouped[y][moIndex] || { name: moName, dates: new Set() };
    grouped[y][moIndex].dates.add(dateFull);
  });

  // Sort years descending (newest first) for better UX, then months ascending
  var years = Object.keys(grouped).sort(function(a,b){ return b - a; });

  years.forEach(function(y) {
    var $yDiv = $('<div style="margin-bottom:6px;"></div>');
    $yDiv.append('<div><label><input type="checkbox" class="year-check" data-year="' + y + '"> <b>' + y + '</b></label></div>');
    var months = Object.keys(grouped[y]).map(Number).sort(function(a,b){ return a - b; });
    months.forEach(function(mi) {
      var mo = grouped[y][mi];
      var $moDiv = $('<div style="margin-left:14px;"></div>');
      $moDiv.append('<div><label><input type="checkbox" class="month-check" data-year="'+y+'" data-month="'+mi+'"> ' + mo.name + '</label></div>');
      // Dates under month
      var dateArr = Array.from(mo.dates).sort(function(a,b){
        var ma = moment(a,'DD-MMM-YYYY'), mb = moment(b,'DD-MMM-YYYY');
        return ma - mb;
      });
      dateArr.forEach(function(dt) {
        var $dtDiv = $('<div style="margin-left:28px;"><label><input type="checkbox" class="date-check" data-year="'+y+'" data-month="'+mi+'" value="' + dt + '"> ' + dt + '</label></div>');
        $moDiv.append($dtDiv);
      });
      $yDiv.append($moDiv);
    });
    $yearWrap.find('.year-list').append($yDiv);
  });

  // --- Hierarchical checkbox behavior ---
  $dateMenu.on('change', '.year-check', function() {
    var year = $(this).data('year');
    var checked = $(this).is(':checked');
    // check/uncheck months and dates for this year
    $dateMenu.find('.month-check[data-year="'+year+'"]').prop('checked', checked);
    $dateMenu.find('.date-check[data-year="'+year+'"]').prop('checked', checked);
  });

  $dateMenu.on('change', '.month-check', function() {
    var year = $(this).data('year');
    var month = $(this).data('month');
    var checked = $(this).is(':checked');
    $dateMenu.find('.date-check[data-year="'+year+'"][data-month="'+month+'"]').prop('checked', checked);

    // if all months under a year are checked -> check year; if none -> uncheck
    var allMonths = $dateMenu.find('.month-check[data-year="'+year+'"]');
    var allChecked = allMonths.length && allMonths.filter(':checked').length === allMonths.length;
    $dateMenu.find('.year-check[data-year="'+year+'"]').prop('checked', allChecked);
  });

  $dateMenu.on('change', '.date-check', function() {
    var year = $(this).data('year');
    var month = $(this).data('month');
    // if all dates under month are checked -> check month; else uncheck
    var allDates = $dateMenu.find('.date-check[data-year="'+year+'"][data-month="'+month+'"]');
    var allChecked = allDates.length && allDates.filter(':checked').length === allDates.length;
    $dateMenu.find('.month-check[data-year="'+year+'"][data-month="'+month+'"]').prop('checked', allChecked);

    // adjust year checkbox if all months checked
    var allMonths = $dateMenu.find('.month-check[data-year="'+year+'"]');
    var monthsChecked = allMonths.filter(':checked').length;
    $dateMenu.find('.year-check[data-year="'+year+'"]').prop('checked', allMonths.length && monthsChecked === allMonths.length);
  });

  // Toggle menu when clicking the filter icon inside header
  $dateTh.find('.filter-icon').off('click').on('click', function(e) {
    e.stopPropagation();
    $('.filter-menu').not($dateMenu).hide();
    $dateMenu.toggle();
  });

  // Hide menu on outside click
  $(document).on('click', function(e) {
    if ($(e.target).closest('.filter-menu').length === 0 && $(e.target).closest('.filter-icon').length === 0) {
      $('.filter-menu').hide();
    }
  });

  // --- DataTables custom row filter for dates ---
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    // only apply for this table
    if (settings.nTable.id !== 'dt') return true;

    var checkedDates = $dateMenu.find('.date-check:checked').map(function(){ return $(this).val(); }).get();
    var checkedMonths = $dateMenu.find('.month-check:checked').map(function(){ return { y: $(this).data('year'), m: $(this).data('month') }; }).get();
    var checkedYears = $dateMenu.find('.year-check:checked').map(function(){ return $(this).data('year'); }).get();

    // if nothing selected -> allow row
    if (!checkedDates.length && !checkedMonths.length && !checkedYears.length) {
      return true;
    }

    var cell = data[dateColIndex] || '';
    if (!cell) return false;
    var m = moment(cell, 'DD-MMM-YYYY', true);
    if (!m.isValid()) {
      m = moment(cell, ['YYYY-MM-DD','DD/MM/YYYY','D-M-YYYY'], true);
      if (!m.isValid()) return false;
    }
    var y = m.format('YYYY');
    var mi = parseInt(m.format('MM'), 10);
    var full = m.format('DD-MMM-YYYY');

    // match exact date checkbox
    if (checkedDates.length && checkedDates.indexOf(full) !== -1) return true;

    // match month selection (year+month)
    for (var i=0;i<checkedMonths.length;i++){
      if (String(checkedMonths[i].y) === String(y) && parseInt(checkedMonths[i].m,10) === mi) return true;
    }

    // match year selection
    if (checkedYears.indexOf(y) !== -1) return true;

    return false;
  });

  // Apply & Clear buttons
  $dateMenu.on('click', '.apply-date-filter', function() {
    table.draw();
    $dateMenu.hide();
  });
  $dateMenu.on('click', '.clear-date-filter', function() {
    $dateMenu.find('input[type="checkbox"]').prop('checked', false);
    table.search('').columns().search('').draw(); // clear column searches and redraw
    $dateMenu.hide();
  });

  // ---------------------
  // Keep your existing generic filters for other columns:
  // We'll create checkbox-search menus for other columns (same structure as before)
  // but avoid re-creating the date column menu.
  $('#dt thead th').each(function(i) {
    if (i === dateColIndex) return; // skip date column (already done)
    var column = table.column(i);
    var $th = $(this);
    var $menu = $th.find('.filter-menu');
    if ($menu.length === 0) {
      $menu = $('<div class="filter-menu"></div>').appendTo($th);
    }
    // small search box
    var $search = $('<input type="text" placeholder="Search..." class="form-control form-control-sm mb-1">').appendTo($menu);
    var $options = $('<div class="filter-options" style="max-height:160px; overflow:auto;"></div>').appendTo($menu);
    $options.append('<div><input type="checkbox" class="select-all"> <strong>Select All</strong></div>');
    var unique = column.data().unique().sort();
    unique.each(function(d) {
      if (d === null || d === undefined || String(d).trim() === '') return;
      // keep values trimmed and displayed
      $options.append('<div><label><input type="checkbox" value="' + $('<div>').text(d).html() + '"> ' + d + '</label></div>');
    });

    // click icon to toggle
    $th.find('.filter-icon').off('click').on('click', function(e) {
      e.stopPropagation();
      $('.filter-menu').not($menu).hide();
      $menu.toggle();
    });

    // select all toggle
    $options.on('change', '.select-all', function() {
      var checked = $(this).is(':checked');
      $options.find('input[type="checkbox"]').not(this).prop('checked', checked);
    });

    // search inside options
    $search.on('keyup', function() {
      var val = $(this).val().toLowerCase();
      $options.children('div').each(function() {
        var text = $(this).text().toLowerCase();
        // keep the select-all row visible always
        if ($(this).find('.select-all').length) { $(this).show(); return; }
        $(this).toggle(text.indexOf(val) > -1);
      });
    });

    // Apply / Clear controls (add if not exist)
    if ($menu.find('.apply-filter').length === 0) {
      var $btns = $('<div class="d-flex justify-content-between mt-2">' +
        '<button class="btn btn-primary btn-sm apply-filter">Filter</button>' +
        '<button class="btn btn-secondary btn-sm clear-filter">Clear</button>' +
        '</div>').appendTo($menu);

      $btns.find('.apply-filter').on('click', function() {
        var selected = [];
        $options.find('input[type="checkbox"]:checked').each(function() {
          if (!$(this).hasClass('select-all')) selected.push($(this).val());
        });
        column.search(selected.join('|'), true, false).draw();
        $menu.hide();
      });

      $btns.find('.clear-filter').on('click', function() {
        $options.find('input[type="checkbox"]').prop('checked', false);
        column.search('').draw();
        $menu.hide();
      });
    }
  });

});

// Filter Data by filter
// Columns to total (0-based indexes)
var totalColumns = [5, 6, 7, 8, 9, 10,11];

// Function to calculate totals for specified columns
function updateFooterTotals() {
  var table = $('#dt').DataTable();

  totalColumns.forEach(function(colIdx) {
    // Calculate the sum of visible (filtered) rows
    var total = table
      .column(colIdx, { search: 'applied' })
      .data()
      .reduce(function(a, b) {
        var x = parseFloat(String(a).replace(/[^0-9.-]+/g, '')) || 0;
        var y = parseFloat(String(b).replace(/[^0-9.-]+/g, '')) || 0;
        return x + y;
      }, 0);

    // Format total nicely (Indian format example)
    var formattedTotal = total.toLocaleString('en-IN', { minimumFractionDigits: 2 });

    // Update the footer cell
    $(table.column(colIdx).footer()).html(formattedTotal);
  });
}

// Recalculate when DataTable is redrawn (after search/filter/sort)
$('#dt').on('draw.dt', function() {
  updateFooterTotals();
});

// Initial total calculation
updateFooterTotals();

</script>




@endsection