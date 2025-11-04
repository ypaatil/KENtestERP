      
   @extends('layouts.master') 

@section('content')   
                      
                          <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center; white-space:nowrap">
                                               <th>Code</th>
                                                <th>KDPL No.</th>
                                                <th>Order Status</th>
                                                <th>Style No</th>
                                                <th>Style Description</th>
                                                <th>Order Qty</th>
                                                <th>Total Cutting Qty</th>  
                                                <th>Bundling </th>
                                                <th>Stickering</th> 
                                                <th>Total Line Issue</th> 
                                                <th>Cutting Stock</th>
                                               <th>Vendor 1</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 2</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 3</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 4</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 5</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 6</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 7</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 8</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 9</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 10</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 11</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 12</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                                <th>Vendor 13</th>
                                               <th>Line No</th>
                                               <th>Qty Issued</th>
                                               
                                            </tr>
                                            </thead>
        
                                            <tbody>
                                            @php $no=1; @endphp
                                            @foreach($PPCCuttingList as $row)  
                                            @if($row->TotalCutQty !=0)
                                            @php
                                            
                                            $VendorIssueList=DB::select(" select ledger_master.ac_name as vendorName,line_master.line_name,
                                            sum(cut_panel_issue_master.total_qty) as issueQty from cut_panel_issue_master
                                            inner join ledger_master on ledger_master.ac_code=cut_panel_issue_master.vendorId
                                            inner join line_master on line_master.line_id=cut_panel_issue_master.line_id
                                            where sales_order_no='".$row->tr_code."'
                                            group by cut_panel_issue_master.vendorId, cut_panel_issue_master.line_id
                                            ");
                                             
                                            @endphp
                                            
                                            <tr>
                                                <td style="text-align:center; white-space:nowrap"> {{ $no  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->job_status_name  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->style_no  }} </td>
                                                <td style="text-align:center; white-space:nowrap">  {{ $row->style_description }}   </td>
                                                <td style="text-align:center; white-space:nowrap">  {{ $row->order_qty }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->TotalCutQty  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> 0 </td>
                                                <td style="text-align:center; white-space:nowrap"> 0 </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->TotalCutIssueQty  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->TotalCutQty - $row->TotalCutIssueQty  }} </td>
                                                 
                                                @foreach($VendorIssueList as $VIL)
                                                
                                                 <td style="text-align:center; white-space:nowrap"> {{  $VIL->vendorName  }} </td>
                                                  <td style="text-align:center; white-space:nowrap"> {{  $VIL->line_name  }} </td>
                                                 <td style="text-align:center; white-space:nowrap"> {{  $VIL->issueQty  }} </td>
                                                 
                                                @endforeach
                                            </tr>
                                            
                                            @endif
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