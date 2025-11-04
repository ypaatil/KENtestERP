      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
                       <div class="row">
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
                                                    <th> Order Received Date</th>
                                                    <th> Style No</th>
                                                    <th> Main Style</th>
                                                    <th> Sub Style</th>
                                                    <td >Total  Fabric Cost</td> 
                                                    
                                                    <td >Sewing Trims Cost</td>
                                                   
                                                    <td >Packing Trims Cost</td>
                                                    
                                                    <td >Manufacturing Cost</td>
                                                  
                                                    <td >Commission Cost</td>
                                                    
                                                    <td >Transport Cost</td>
                                                     <td >Overhead Cost</td>
                                                    <td >Garment Washing Cost</td>
                                                    <td >Printing Cost</td>
                                                    <td >Embroidery Cost</td>
                                                    <td >IXD Cost</td>
                                                    <td >Garment Rejection</td>
                                                    <td >Testing Charges</td>
                                                    <td >Finance Cost</td>
                                                    <td >Other Cost</td>
                                                    
                                                   
                                                    
                                                    <td >Total Cost</td>
                                                    
                                                    <td >Profit Value</td>
                                                    <th> Profit %</th>
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           @foreach($SalesOrderCostingMaster as $row)   
                                           	@php 
                                           	       if($row->fabric_value > 0 )
                                           	       {
                                           	         $percentOffabric =($row->fabric_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	          $percentOffabric = 0;
                                           	       }
                                           	       
                                           	       
                                           	       if($row->sewing_trims_value > 0 && $row->order_rate > 0)
                                           	       {
                                           	            $percentOfsewing_trims_value=($row->sewing_trims_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfsewing_trims_value = 0;
                                           	       }
                                        	      
                                        	        
                                           	       if($row->packing_trims_value > 0 && $row->order_rate > 0)
                                           	       {
                                           	            $percentOfpacking_trims_value=($row->packing_trims_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfpacking_trims_value = 0;
                                           	       }
                                           	       
                                           	       if($row->production_value > 0 && $row->order_rate > 0)
                                           	       {
                                           	            $percentOfproduction_value=($row->production_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfproduction_value = 0;
                                           	       }
                                        	        
                                           	       if($row->agent_commision_value > 0 && $row->order_rate > 0)
                                           	       {
                                           	            $percentOfagent_commision_value=($row->agent_commision_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfagent_commision_value = 0;
                                           	       }
                                        	       
                                        	       if($row->transaport_value > 0 && $row->order_rate > 0)
                                           	       {
                                        	            $percentOftransaport_value=($row->transaport_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOftransaport_value = 0;
                                           	       }
                                        	        
                                        	       if($row->other_value > 0 && $row->order_rate > 0)
                                           	       {
                                        	             $percentOfother_value=($row->other_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfother_value = 0;
                                           	       }
                                        	        
                                        	         
                                        	       if($row->dbk_value > 0 && $row->order_rate > 0)
                                           	       {
                                        	            $percentOfdbk_value=($row->dbk_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfdbk_value = 0;
                                           	       }
                                        	        
                                        	       if($row->printing_value > 0 && $row->order_rate > 0)
                                           	       {
                                        	             $percentOfprinting_value=($row->printing_value / $row->order_rate) * 100; 
                                           	       }
                                           	       else
                                           	       {
                                           	            $percentOfprinting_value = 0;
                                           	       }
                                           	       
                                        	       
                                        	        $percentOfembroidery_value=($row->embroidery_value / $row->order_rate) * 100; 
                                        	        $percentOfixd_value=($row->ixd_value / $row->order_rate) * 100;
                                        	        // $percentOfgarment_reject_value=($row->garment_reject_value / $row->order_rate) * 100;  
                                        	      
                                        	        $TotalCost= round(($row->fabric_value  +  $row->sewing_trims_value
                                    +  $row->printing_value  +  $row->embroidery_value + 
                                     $row->packing_trims_value  + $row->production_value +
                                    $row->dbk_value),2);
                      
                    
                //  $percentOfgarment_reject_value=round((($row->garment_reject_value / $TotalCost) * 100),2);  
                  
                  
                    	        $percentOfgarment_reject_value=($row->garment_reject_value /$TotalCost) * 100;  
                                        	      
                                        	      
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
                                            <td>{{ $row->fabric_value }} 
                                            <br>{{ number_format((float)$percentOffabric, 2, '.', '') }}%</td> 
                                            <td>{{ $row->sewing_trims_value }} 
                                            <br>{{ number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->packing_trims_value }} 
                                            <br>{{ number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->production_value }} 
                                            <br>{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->agent_commision_value }} 
                                            <br>{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->transaport_value }} 
                                            <br>{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->other_value }} 
                                            <br>{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->dbk_value }} 
                                            <br>{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->printing_value }} 
                                            <br>{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->embroidery_value }} 
                                            <br>{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->ixdvalue }} 
                                            <br>{{ number_format((float)$percentOfixd_value, 2, '.', '') }}%</td>
                                            
                                            <td>{{ $row->garment_reject_value }} <br> {{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->testing_charges_value }}<br> {{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->finance_cost_value }}<br> {{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}%</td>
                                            <td>{{ $row->extra_value }}<br> {{ number_format((float)$percentOfextra_value, 2, '.', '') }}%</td>
                                             
                                            <td>{{ $row->total_cost_value }} 
                                            <br>{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
                                            @php
                                                $profit_value=0.0;
                                                $profit_value=  ($row->order_rate - $row->total_cost_value);
                                                $profitpercentage= (($profit_value / $row->order_rate) * 100);
                                            @endphp
                                            <td >{{number_format((float)$profit_value, 2, '.', '')}} </td>
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