@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp

                        <!-- end page title -->
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Costing Details Report</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                <li class="breadcrumb-item active">Costing Details Report</li>
            </ol>
        </div>

    </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('SalesCostingProfitSheet')}}" method="GET" enctype="multipart/form-data">
                   @csrf 
                   <div class="row">
                       <label for="" class="form-label"><b>Order Receive</b></label>
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="receivedFromDate" class="form-label">From</label>
                            <input type="date" class="form-control" name="receivedFromDate" id="receivedFromDate" value="{{ isset($receivedFromDate) ? $receivedFromDate : date('Y-m-01')}}">
                         </div>
                       </div>
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="receivedToDate" class="form-label">To</label>
                            <input type="date" class="form-control" name="receivedToDate" id="receivedToDate" value="{{ isset($receivedToDate) ? $receivedToDate :  date('Y-m-d')}}">
                         </div>
                       </div>    
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No</label>
                            <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($salesOrderList as $row)
                                    <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$row->tr_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id" id="job_status_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" {{ $row->job_status_id == $job_status_id ? 'selected="selected"' : '' }} >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId" id="orderTypeId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($orderTypeList as $row)
                                    <option value="{{$row->orderTypeId}}" {{ $row->orderTypeId == $orderTypeId ? 'selected="selected"' : '' }} >{{$row->order_type}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="ac_code" class="form-label">Buyer Name</label>
                            <select name="ac_code" id="ac_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($buyerList as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                        <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="is_approved" class="form-label">Costing Status</label>
                            <select name="is_approved" id="is_approved" class="form-control select2">
                                <option value="">--Select--</option>
                                <option value="1" {{ $is_approved == 1 ? 'selected="selected"' : '' }} >Pending</option> 
                                <option value="2" {{ $is_approved == 2 ? 'selected="selected"' : '' }} >Approved</option> 
                                <option value="3" {{ $is_approved == 3 ? 'selected="selected"' : '' }} >Rejected</option> 
                            </select> 
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($brandList as $row)
                                    <option value="{{$row->brand_id}}"  {{ $row->brand_id == $brand_id ? 'selected="selected"' : '' }}  >{{$row->brand_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>  
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="mainstyle_id" class="form-label">Main Style Category</label>
                            <select name="mainstyle_id" id="mainstyle_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($mainStyleList as $row)
                                    <option value="{{$row->mainstyle_id}}"  {{ $row->mainstyle_id == $mainstyle_id ? 'selected="selected"' : '' }} >{{$row->mainstyle_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="style_no" class="form-label">Style No</label>
                            <select name="style_no" id="style_no" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($styleNoList as $row)
                                    <option value="{{$row->style_no}}" {{ $row->style_no == $style_no ? 'selected="selected"' : '' }}>{{$row->style_no}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>  
                       <div class="col-sm-6">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/SalesCostingProfitSheet" class="btn btn-danger w-md">Cancel</a>
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
                <table id="dt" class="table table-bordered nowrap w-100">
                      <thead>
                        <tr>
                                <th class="text-center" nowrap>Sr. No.</th>
                                <th class="text-center" nowrap>Sales Order No<span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>
                                <th class="text-center" nowrap>Buyer<span class="filter-icon">🔽</span><div class="filter-menu buyer"></div></th>
                                <th class="text-center" nowrap>Brand<span class="filter-icon">🔽</span><div class="filter-menu brand"></div></th>
                                <th class="text-center" nowrap>Costing Status<span class="filter-icon">🔽</span><div class="filter-menu costing-status"></div></th>
                                <th class="text-center" nowrap>Status<span class="filter-icon">🔽</span><div class="filter-menu status"></div></th>
                                <th class="text-center" nowrap>Order Type<span class="filter-icon">🔽</span><div class="filter-menu order-type"></div></th>
                                <th class="text-center" nowrap>Order Recd. Date<span class="filter-icon">🔽</span><div class="filter-menu order-recd-date"></div></th>
                                <th class="text-center" nowrap>Style No<span class="filter-icon">🔽</span><div class="filter-menu style-no"></div></th>
                                <th class="text-center" nowrap>Style Category<span class="filter-icon">🔽</span><div class="filter-menu style-category"></div></th> 
                                <th class="text-center" nowrap>SAM</th>
                                <th class="text-center" nowrap>Order Qty</th>
                                <th class="text-center" nowrap>FOB Rate</th>
                                <th class="text-center" nowrap>Order Value</th>
                                <th class="text-center" nowrap>Cons.</th>
                                <th class="text-center" nowrap>Fabric Cost</th> 
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Sewing Trims Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Packing Trims Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Manufacturing Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Garment Washing Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Printing Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Embroidery Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Garment Rejection</th>
                                <th class="text-center" nowrap>% Of (Fab+Trim)</th>
                                <th class="text-center" nowrap>IXD Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Commission Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Transport Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Overhead Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Testing Charges</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Finance Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Other Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>CM/SAM</th>
                                <th class="text-center" nowrap>Total Cost</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>DBK Value 1</th>
                                <th class="text-center" nowrap>% Of FOB</th>
                                <th class="text-center" nowrap>Profit Value</th>
                                <th class="text-center" nowrap> Profit %</th>
                                <th class="text-center" nowrap>CMOHP</th>
                         </tr>
                        </thead>
                        <tbody>
                       @php
                         $srno = 1;
                       @endphp
                       @foreach($SalesOrderCostingMaster as $row)   
                       @php 
                    	        $percentOffabric =($row->fabric_value / $row->order_rate) * 100; 
                    	        $percentOfsewing_trims_value=($row->sewing_trims_value / $row->order_rate) * 100; 
                    	        $percentOfpacking_trims_value=($row->packing_trims_value / $row->order_rate) * 100; 
                    	        $percentOfproduction_value=($row->production_value / $row->order_rate) * 100; 
                    	        $percentOfagent_commision_value=($row->agent_commision_value / $row->order_rate) * 100; 
                    	        $percentOftransaport_value=($row->transaport_value / $row->order_rate) * 100; 
                    	        $percentOfother_value=($row->other_value / $row->order_rate) * 100;
                    	        $percentOfdbk_value=($row->dbk_value / $row->order_rate) * 100;  
                    	        $percentOfprinting_value=($row->printing_value / $row->order_rate) * 100;  
                    	        $percentOfembroidery_value=($row->embroidery_value / $row->order_rate) * 100;  
                    	        $percentOftotalmaking_value=($row->total_making_value / $row->order_rate) * 100;  
                    	        $percentOfdbk_value1=($row->dbk_value1 / $row->order_rate) * 100;  
                    	        $percentOfixd_value=($row->ixd_value / $row->order_rate) * 100; 
                    	        
                    	        $TotalCost= round(($row->fabric_value  +  $row->sewing_trims_value
                                            +  $row->printing_value  +  $row->embroidery_value + 
                                             $row->packing_trims_value  + $row->production_value +
                                            $row->dbk_value),2);
      
                   
                                //  $percentOfgarment_reject_value=round((($row->garment_reject_value / $TotalCost) * 100),2);  
                  
                                if($row->garment_reject_value > 0 && $TotalCost > 0)
                                {
                                    $percentOfgarment_reject_value=($row->garment_reject_value/$TotalCost) * 100; 
                                }
                                else
                                { 
                                    $percentOfgarment_reject_value = 0;
                                }
                    	         
                    	        
                    	        if($row->testing_charges_value > 0 && $row->order_rate > 0)
                                {
                                    $percentOftesting_charges_value=($row->testing_charges_value/$row->order_rate) * 100; 
                                }
                                else
                                { 
                                    $percentOftesting_charges_value = 0;
                                }
                                
                    	        
                    	        if($row->finance_cost_value > 0 && $row->order_rate > 0)
                                {
                                    $percentOffinance_cost_value=($row->finance_cost_value/$row->order_rate) * 100; 
                                }
                                else
                                { 
                                    $percentOffinance_cost_value = 0;
                                }
                                
                    	        if($row->extra_value > 0 && $row->order_rate > 0)
                                {
                                    $percentOfextra_value=($row->extra_value/$row->order_rate) * 100; 
                                }
                                else
                                { 
                                    $percentOfextra_value = 0;
                                }
                                
                    	        if($row->total_cost_value > 0 && $row->order_rate > 0)
                                {
                                    $percentOftotal_cost_value=($row->total_cost_value/$row->order_rate) * 100; 
                                }
                                else
                                { 
                                    $percentOftotal_cost_value = 0;
                                }
                                 
                    	        if($row->order_type == 1)
                    	        {
                    	            $order_type = "Fresh";
                    	        }
                    	        else if($row->order_type == 2)
                    	        {
                    	            $order_type = "Stock";
                    	        }
                    	        else
                    	        {
                    	            $order_type = "-";
                    	        }
                    	        
                    	        if($row->is_approved == 1)
                    	        {
                    	            $costingStatus = 'Pending';
                    	        }
                    	        else if($row->is_approved == 2)
                    	        {
                    	            $costingStatus = 'Approved';
                    	        }
                    	        else if($row->is_approved == 3)
                    	        {
                    	            $costingStatus = 'Rejected';
                    	        }
                    	        else
                    	        {
                    	             $costingStatus = 'Pending';
                    	        }
                     	@endphp
                        <tr>
                        <td style="text-align:right; white-space:nowrap"> {{ $srno++ }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ $row->sales_order_no }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ $row->ac_short_name }}</td> 
                        <td style="text-align:left; white-space:nowrap">{{ $row->brand_name }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ $costingStatus }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ $row->job_status_name }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ $order_type }}</td> 
                        <td style="text-align:left; white-space:nowrap"> {{ date("d-M-Y", strtotime($row->order_received_date)) }}</td>
                        <td style="text-align:left; white-space:wrap">{{ $row->style_no }}</td> 
                        <td style="text-align:left; white-space:nowrap">{{ $row->mainstyle_name }}</td>  
                        <td style="text-align:right; white-space:nowrap">{{ sprintf('%.2f', round($row->sam,2)) }}</td> 
                        <td style="text-align:right; white-space:nowrap">{{ number_format($row->order_qty, 0, ',', ',') }}</td> 
                        <td style="text-align:right; white-space:nowrap">{{ money_format('%!i',$row->order_rate) }}</td> 
                        <td style="text-align:right; white-space:nowrap">{{ money_format('%!.0n',round($row->order_rate*$row->order_qty)) }}</td> 
                        <td style="text-align:right;">{{ sprintf('%.2f', round($row->consumption,2)) }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->fabric_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOffabric, 2, '.', '') }}</td> 
                        <td style="text-align:right;">{{ number_format((float)$row->sewing_trims_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->packing_trims_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}</td> 
                        <td style="text-align:right;">{{ number_format((float)$row->production_value, 2, '.', '')  }}</td> 
                        <td style="text-align:right;">{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->dbk_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->printing_value, 2, '.', '')  }} </td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->embroidery_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->garment_reject_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->ixd_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfixd_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->agent_commision_value, 2, '.', '')  }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->transaport_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->other_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfother_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->testing_charges_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->finance_cost_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$row->extra_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfextra_value, 2, '.', '') }}</td>
                        @php
                           if($row->production_value > 0 && $row->sam > 0)
                           {
                                $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                           }
                           else
                           {
                                $cm_sam = 0;
                           }
                        @endphp
                        <td style="text-align:right;">{{ $cm_sam }}</td>
                        
                        
                        <td style="text-align:right;">{{ $row->total_cost_value }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}</td>
                        <td style="text-align:right;">{{ $row->dbk_value1 }}</td>
                        <td style="text-align:right;">{{ number_format((float)$percentOfdbk_value1, 2, '.', '') }}</td>
                        @php
                            $profit_value=0.0;
                            $profit_value = ($row->order_rate - $row->total_cost_value) + $row->dbk_value1;
                            
                	        if($profit_value > 0 && $row->order_rate > 0)
                            {
                                $profitpercentage= (($profit_value / $row->order_rate) * 100) + $percentOfdbk_value1;
                            }
                            else
                            { 
                                $profitpercentage = $percentOfdbk_value1;
                            }
                            
                            $cmohp1 = $row->production_value + $profit_value + $row->other_value;
                            $cmohp2 = $row->sam;
                            if($cmohp1 && $cmohp2)
                            {
                                $cmohp = $cmohp1/$cmohp2;
                            }
                            else
                            {
                                $cmohp = 0;
                            }
                        @endphp
                        <td style="text-align:right;" >{{number_format((float)$profit_value, 2, '.', '')}}</td>
                        <td style="text-align:right;" >{{number_format((float)$profitpercentage, 2, '.', '')}} </td>
                        <td style="text-align:right;" >{{number_format((float)$cmohp, 2, '.', '')}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                       </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
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
            const exportTitle = 'Costing Details Report (' + formattedDate + ')';
            
            $('#dt').DataTable({
                destroy: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        title: exportTitle,
                    exportOptions: commonExportOptions(),
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: exportTitle,
                    exportOptions: commonExportOptions(),
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        title: exportTitle,
                    exportOptions: commonExportOptions(),
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: exportTitle, 
                    exportOptions: commonExportOptions(),
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
                    exportOptions: commonExportOptions(),
                    }
                ]
    
            });

            // Start script for filter search and apply
            buildAllMenusSalesCostingProfitSheet();

             $(document).on('click', '.apply-btn', function() {
            const menu = $(this).closest('.filter-menu');

            if (!validateFilterMenu(menu)) {
                return;
            }

            if(menu.hasClass('sales-order-no')) applySimpleFilter(1, menu);
            else if(menu.hasClass('buyer')) applySimpleFilter(2, menu);            
            else if(menu.hasClass('brand')) applySimpleFilter(3,menu);
            else if(menu.hasClass('costing-status')) applySimpleFilter(4,menu);
            else if(menu.hasClass('status')) applySimpleFilter(5,menu);
            else if(menu.hasClass('order-type')) applySimpleFilter(6,menu);
            else if(menu.hasClass('order-recd-date')) applyDateFilter(7, menu);    
            else if(menu.hasClass('style-no')) applySimpleFilter(8,menu);
            else if(menu.hasClass('style-category')) applySimpleFilter(9,menu);                         
            $('.filter-menu').hide();
            buildAllMenusSalesCostingProfitSheet();            
            });
            // End script for filter search and apply

            
        });
    </script>                                               
    
    @endsection