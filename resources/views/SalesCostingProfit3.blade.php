      
   @extends('layouts.master') 

@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
                        
                        <!-- end page title -->
                       <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Sales Costing and Profit Sheet</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Sales Costing and Profit Sheet</li>
            </ol>
        </div>

    </div>
</div>
</div>
                        
                        
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr>
                                                    <th> Buyer Name</th>
                                                    <th> Sales Order No</th>
                                                    <th>Order Received Date</th>
                                                    <th> Style No</th>
                                                    <th> Main Style Category</th>
                                                    <th> Sub Style Category</th>
                                                    <th>Brand Name</th>
                                                    <th>SAM</th>
                                                    <th>Order Qty</th>
                                                    <th>FOB Rate</th>
                                                    <th>Order Value</th>
                                                    <td >Overhead Cost</td>
                                                    <th>% Of FOB</th>
                                                    <td >Total Cost</td>
                                                    <th>% Of FOB</th>
                                                    <td >Profit Value</td>
                                                    <th> Profit %</th>
                                                    
                                             </tr>
                                            </thead>
        
                                            <tbody>

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
                                        	        $percentOfgarment_reject_value=($row->garment_reject_value / $row->order_rate) * 100;  
                                        	        $percentOftesting_charges_value=($row->testing_charges_value / $row->order_rate) * 100;  
                                        	        $percentOffinance_cost_value=($row->finance_cost_value / $row->order_rate) * 100;  
                                        	        $percentOfextra_value=($row->extra_value / $row->order_rate) * 100;  
                                        	        $percentOftotal_cost_value=($row->total_cost_value / $row->order_rate) * 100;  
                                        	      
                                         	@endphp
                                            <tr>
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->Ac_name }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->sales_order_no }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->order_received_date }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->style_no }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->mainstyle_name }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->substyle_name }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->brand_name }}</td> 
                                            <td style="text-align:center; white-space:nowrap"> {{ $row->sam }}</td> 
                                            <td style="text-align:right; white-space:nowrap"> {{ number_format($row->order_qty) }}</td> 
                                            <td style="text-align:right; white-space:nowrap"> {{ money_format('%!i',$row->order_rate) }}</td> 
                                            <td style="text-align:right; white-space:nowrap"> {{ money_format('%!i',($row->order_rate*$row->order_qty)) }}</td> 
                                            <td style="text-align:right;">{{ $row->other_value }}</td>
                                            <td style="text-align:right;">{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
                                           <td style="text-align:right;">{{ $row->total_cost_value }}</td>
                                            <td style="text-align:right;">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
                                            @php
                                                $profit_value=0.0;
                                                $profit_value=  ($row->order_rate - $row->total_cost_value);
                                                $profitpercentage= (($profit_value / $row->order_rate) * 100);
                                            @endphp
                                            <td>{{number_format((float)$profit_value, 2, '.', '')}}</td>
                                            <td>{{number_format((float)$profitpercentage, 2, '.', '')}} %</td>
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
           @endsection