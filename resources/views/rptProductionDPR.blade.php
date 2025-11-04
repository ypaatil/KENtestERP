@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<style>
    .hide
    {
        display:none;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Production DPR Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Production DPR Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/rptProductionDPR" method="GET">
              <div class="row">  
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="DPRDate" class="form-label">Date</label>
                        <input type="date" class="form-control" name="DPRDate" id="DPRDate" value="{{$DPRDate}}">
                     </div>
                   </div>
                   <div class="col-md-3" id="v1">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor</label>
                        <select name="vendorId" id="vendorId" class="form-control select2" onchange="GetLineNoFromVendor(1);">
                            <option value="0">--All--</option>
                            @foreach($vendorList as $row)
                                <option value="{{$row->ac_code}}" {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }}>{{$row->ac_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div>  
                   <div class="col-md-3" id="v2">
                     <div class="mb-3">
                        <label for="outsourceId" class="form-label">Outsource</label>
                        <select name="outsourceId" id="outsource" class="form-control select2" onchange="GetLineNoFromVendor(2);">
                            <option value="0">--All--</option>
                            @foreach($outsourceList as $row)
                                <option value="{{$row->ac_code}}" {{ $row->ac_code == $outsourceId ? 'selected="selected"' : '' }}>{{$row->ac_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style</label>
                        <select name="style_no" id="style_no" class="form-control select2">
                            <option value="0">--All--</option>
                            @foreach($styleList as $row)
                                <option value="{{$row->fg_id}}" {{ $row->fg_id == $style_no ? 'selected="selected"' : '' }}>{{$row->fg_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div> 
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="line_id" class="form-label">Line No</label>
                        <select name="line_id" id="line_id" class="form-control select2">
                            <option value="">--All--</option>
                            @foreach($LineList as $row)
                                <option value="{{$row->line_id}}" {{ $row->line_id == $line_id ? 'selected="selected"' : '' }}>{{$row->line_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div> 
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">Sales Order No</label>
                        <select name="sales_order_no" id="sales_order_no" class="form-control select2" >
                            <option value="">--Select--</option> 
                             @foreach($BuyerPurchaseList as $row)
                                <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }}>{{$row->tr_code}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div>


                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" id="Ac_code" class="form-control select2">
                            <option value="0">--Select--</option>  
                             @foreach($BuyerList as $row)
                                <option value="{{$row->ac_code}}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }}>{{$row->ac_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div> 
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" id="mainstyle_id" class="form-control select2">
                            <option value="0">--Select--</option> 
                             @foreach($mainStyleList as $row)
                                <option value="{{$row->mainstyle_id}}" {{ $row->mainstyle_id == $mainstyle_id ? 'selected="selected"' : '' }}>{{$row->mainstyle_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div>  
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/rptProductionDPR" class="btn btn-warning">Clear</a>
                  </div>
              </div>
          </form>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th>Order No</th>
                        <th>Buyer Name</th> 
                        <th>Style Name</th> 
                        <th>Color</th> 
                        <th nowrap>Cutting<br/> (All Vendor)<br/>A</th>
                        <th nowrap>Cutting Issue <br/>(Selected Vendor)<br/> B</th>
                        <th nowrap>Stitching <br/>(Selected Vendor)<br/> C</th>
                        <th nowrap>Line/Rej <br/>(Selected Vendor)<br/> D</th>
                        <th nowrap>Total Output<br/> C+D</th>
                        <th nowrap>Line/Bal<br/> B-(C+D)</th>
                        <th nowrap>Washing <br/>(All Vendor)<br/> E</th>
                        <th>Wash/Bal<br/> C-E</th>
                        <th nowrap>Washing Inward <br/>(All Vendor)<br/> F</th>
                        <th nowrap>Washing Inward/Bal<br/> E-F</th>
                        <th nowrap>Packing <br/>(All Vendor)<br/> G</th>
                        <th>Packing/Rej</th>
                        <th>Total IN<br/> G+D</th> 
                        <th nowrap>Cut to Pack %<br/> G/A</th>
                        <th nowrap>Cut to Reject %<br/> D/A</th> 
                     </tr> 
                  </thead>
                  <tbody>
                     @php
                        $total_cutting_qty = 0;
                        $total_input_qty = 0;
                        $total_output_qty = 0;
                        $total_line_rej_qty = 0;
                        $total_all_output_qty = 0;
                        $total_line_bal_qty = 0;
                        $total_washing_inward = 0;
                        $total_washing_inward_bal = 0;
                        $total_fi_inward = 0;
                        $total_fi_bal_inward = 0;
                        $total_packing = 0;
                        $total_inward = 0;
                        $total_deviation = 0;
                        $total_packing_rej = 0;
                     @endphp
                     @foreach($ProductionOrderDetailList as $row)     
                     @php 
                           
                         
                             if($row->total_packing_qty > 0 && $row->total_cutting_qty > 0)
                             {
                                $cutToPack_per =  ($row->total_packing_qty/$row->total_cutting_qty);
                             }
                             else
                             {
                                $cutToPack_per = 0;
                             }
                             
                             if($row->total_qcstitching_reject_qty > 0 && $row->total_cutting_qty > 0)
                             {
                                $cutToReject_per = $row->total_qcstitching_reject_qty/$row->total_cutting_qty;
                             }
                             else
                             { 
                                $cutToReject_per = 0;
                             } 
                            if($row->total_cut_panel_issue != 0 && ($row->total_cut_panel_issue - ($row->total_packing_qty + $row->total_qcstitching_reject_qty)) != 0)
                            {
                             
                     @endphp
                     <tr>
                        <td style="white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="white-space:nowrap"> {{ $row->ac_short_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->fg_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->color_name  }} </td> 
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_cutting_qty))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_cut_panel_issue))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_stitching_qty))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_qcstitching_reject_qty))  }} </td> 
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_stitching_qty + $row->total_qcstitching_reject_qty))  }} </td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_cut_panel_issue - ($row->total_stitching_qty + $row->total_qcstitching_reject_qty)))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_washing_qty))  }}</td> 
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_stitching_qty - $row->total_washing_qty)) }}</td>   
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_fi_washing_qty))  }}</td>      
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_washing_qty - $row->total_fi_washing_qty))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_packing_qty))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_packing_rej_qty))  }}</td>  
                        <td style="text-align:right;"> {{ money_format("%!.0n",($row->total_packing_qty + $row->total_qcstitching_reject_qty)) }}</td>  
                        <td style="text-align:right;"> {{ number_format($cutToPack_per * 100, 2)  }}</td> 
                        <td style="text-align:right;"> {{ number_format($cutToReject_per * 100,2)  }}</td>    
                     </tr>
                     @php
                            $total_cutting_qty += $row->total_cutting_qty;
                            $total_input_qty += $row->total_cut_panel_issue;
                            $total_output_qty += $row->total_stitching_qty;
                            $total_line_rej_qty += $row->total_qcstitching_reject_qty;
                            $total_all_output_qty += $row->total_stitching_qty + $row->total_qcstitching_reject_qty;
                            $total_line_bal_qty += ($row->total_cut_panel_issue - ($row->total_stitching_qty + $row->total_qcstitching_reject_qty));
                            $total_washing_inward += $row->total_washing_qty;
                            $total_washing_inward_bal += $row->total_stitching_qty - $row->total_washing_qty;
                            $total_fi_inward += $row->total_fi_washing_qty;
                            $total_fi_bal_inward += $row->total_washing_qty - $row->total_fi_washing_qty;
                            $total_packing += $row->total_packing_qty;
                            $total_packing_rej += $row->total_packing_rej_qty;
                            $total_inward += $row->total_packing_qty + $row->total_qcstitching_reject_qty;
                            $total_deviation += $row->total_cut_panel_issue - ($row->total_packing_qty + $row->total_qcstitching_reject_qty);
                         }
                     @endphp
                     @endforeach 
                  </tbody>
                  <tfoot
                      <tr>
                      @php
                                if($total_packing > 0 && $total_cutting_qty > 0)
                                {
                                    $avg_inward = ($total_packing/$total_cutting_qty) * 100;
                                }
                                else
                                {
                                    $avg_inward = 0;
                                }
                                
                                
                                if($total_line_rej_qty > 0 && $total_cutting_qty > 0)
                                {
                                    $avg_deviation = ($total_line_rej_qty/$total_cutting_qty) * 100;
                                }
                                else
                                {
                                    $avg_deviation = 0;
                                }
                                
                      @endphp
                          <th></th>
                          <th></th>
                          <th></th>
                          <th class="text-center">Total</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_cutting_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_input_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_output_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_line_rej_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_all_output_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_line_bal_qty))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_washing_inward))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_washing_inward_bal))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_fi_inward))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_fi_bal_inward))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_packing))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_packing_rej))}}</th>
                          <th style="text-align:right;">{{ money_format("%!.0n",($total_inward))}}</th> 
                          <th style="text-align:right;">{{ number_format($avg_inward, 2) }}</th>
                          <th style="text-align:right;">{{ number_format($avg_deviation, 2) }}</th>  
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
<script>

    $("#Cutting").html($('#total_cutting_qty').val()).toLocaleString('en-IN');
    $("#line_issue").html($('#total_line_issue').val()).toLocaleString('en-IN');
    $("#stitching").html($('#total_stitching_qty').val()).toLocaleString('en-IN');
    $("#rejection").html($('#total_rejection_qty').val()).toLocaleString('en-IN');
    $("#packing").html($('#total_packing_qty').val()).toLocaleString('en-IN');
    $("#shipement").html($('#total_shipment_qty').val()).toLocaleString('en-IN');
     
    // $(function()
    // {
    //     GetSalesOrderListFromVendor();
    //     GetBuyerListFromVendor();
    //     GetMainStyleListFromVendor();    
    // });
    
    function GetLineNoFromVendor(obj)
    {   
        if (obj == 1) 
        {   
            $("#outsource").select2('destroy');
            $("#outsource").val("");
            $("#outsource").select2();
        } 
        else if (obj == 2) 
        {  
            $("#vendorId").select2('destroy');
            $("#vendorId").val("");
            $("#vendorId").select2();
        } 
        else 
        {
            $("#outsource").select2();
            $("#vendorId").select2(); 
        } 
        
        var Ac_code = $("#vendorId").val();
        $.ajax({
             dataType: "json",
             contentType: "application/json; charset=utf-8",
             url: "{{ route('GetLineList') }}",
             data:{'Ac_code':Ac_code},
             success: function(data)
             {
                  $("#line_id").html(data.html); 
             }
        });
         
    }
    
     
    
    // function GetSalesOrderListFromVendor()
    // {
        
    //     var vendorId = $("#vendorId").val();
    //     $.ajax({
    //          dataType: "json",
    //          contentType: "application/json; charset=utf-8",
    //          url: "{{ route('GetSalesOrderListFromVendor') }}",
    //          data:{'vendorId':vendorId},
    //          success: function(data)
    //          {
    //               $("#sales_order_no").html(data.html); 
    //          }
    //     }); 
    // }
    
    // function GetBuyerListFromVendor()
    // {
        
    //     var vendorId = $("#vendorId").val();
    //     $.ajax({
    //          dataType: "json",
    //          contentType: "application/json; charset=utf-8",
    //          url: "{{ route('GetBuyerListFromVendor') }}",
    //          data:{'vendorId':vendorId},
    //          success: function(data)
    //          {
    //               $("#Ac_code").html(data.html); 
    //          }
    //     }); 
    // }
    
    // function GetMainStyleListFromVendor()
    // {
        
    //     var vendorId = $("#vendorId").val();
    //     $.ajax({
    //          dataType: "json",
    //          contentType: "application/json; charset=utf-8",
    //          url: "{{ route('GetMainStyleListFromVendor') }}",
    //          data:{'vendorId':vendorId},
    //          success: function(data)
    //          {
    //               $("#mainstyle_id").html(data.html); 
    //          }
    //     }); 
    // }
</script>
@endsection