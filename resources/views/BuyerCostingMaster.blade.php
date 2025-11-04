@extends('layouts.master') 
@section('content')
<style>
   .hide
   {
   display:none;
   }
   
    input[type="number"], .value, .per {
      text-align: right!important;
    } 
    
    input[type="text"] {
      text-align: left!important;
    }
       
    @media (max-width: 600px) 
    {
        .breadcumbCls 
        {
            display: none;
        }
        
        .navbar-header
        {
            background: #703eb385;
        }
        .titleCls
        { 
            text-align: center;
        }
        
        #vertical-menu-btn
        {
            display: none;
        }
    }
    
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Buyer Order Costing</h4>
            @if ($errors->any())
            <div class="col-md-6">
               <div class="alert alert-danger">
                  <ul>
                     @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            </div>
            @endif
            <form action="{{route('BuyerCosting.store')}}" method="POST"  enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="entry_date" class="form-label">Entry Date</label>
                        <input type="date" name="entry_date" class="form-control" id="entry_date" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" >
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="buyer_name" class="form-label">Buyer/Party</label>
                        <input type="text" id="buyer_name" name="buyer_name" value="" class="form-control" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_name" class="form-label">Brand</label>
                        <input type="text" id="brand_name" name="brand_name" value="" class="form-control" required >
                     </div>
                  </div>
                  <!--<div class="col-md-2">-->
                  <!--   <div class="mb-3">-->
                  <!--      <label for="exchange_rate" class="form-label">Exchange Rate</label>-->
                  <!--      <input type="text" name="exchange_rate" class="form-control" id="exchange_rate" value="" required >-->
                  <!--   </div>-->
                  <!--</div>-->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="og_id" class="form-label">Order Group</label>
                        <select name="og_id" class="form-control" id="og_id">
                            <option value="0">--Select--</option>
                            @foreach($OrderGroupList as $row)
                                <option value="{{$row->og_id}}">{{$row->order_group_name}}</option>
                            @endforeach
                        </select> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input  type="number" step="any" step="any"  name="sam" class="form-control" id="sam" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cur_id" class="form-label">Currency</label>
                        <select name="cur_id" class="form-control" id="cur_id">
                            <option value="0">--Select--</option>
                            @foreach($CurrencyList as $row)
                                <option value="{{$row->cur_id}}">{{$row->currency_name}}</option>
                            @endforeach
                        </select> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inr_rate" class="form-label">FOB</label>
                        <input type="number" step="any" name="inr_rate" class="form-control" id="inr_rate" value="" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" step="any" name="exchange_rate" class="form-control" id="exchange_rate" value="1" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fob_rate" class="form-label">FOB Rate (INR)</label>
                        <input type="number" step="any" step="any" name="fob_rate" class="form-control" id="fob_rate" value="" onchange="CalTotalValue();" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input  type="number" step="any" step="any"  name="total_qty" class="form-control" id="total_qty" onchange="CalTotalValue();" value="" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_value" class="form-label">Total Value</label>
                        <input  type="number" step="any" step="any"  name="total_value" class="form-control" id="total_value" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_name" class="form-label">Style Category</label>
                        <input type="text" name="style_name" class="form-control" id="style_name" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_image" class="form-label">Style Image</label>
                        <input type="file" name="style_image" class="form-control" id="style_image">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <label class="form-label">Fabric Costing: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Name</th>
                                 <th>Cons(Mtr/Kg)</th>
                                 <th>Rate</th>
                                 <th>Wastage %</th>
                                 <th>Total Amount</th>
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="id" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td><input type="text" name="item_name[]" value="" id="item_name" style="width:250px;height:30px;" required  /></td>
                                 <td><input type="number" step="any" step="any" name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px;height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="wastage[]" value="0" id="wastage" style="width:80px;height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" class="FABRIC"   name="total_amount[]" value="0" id="total_amount" style="width:80px;height:30px;" required readOnly/></td>
                                 <td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label class="form-label">Sewing Trims Costing: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Name</th>
                                 <th>Cons(Mtr/Nos)</th>
                                 <th>Rate</th>
                                 <th>Wastage %</th>
                                 <th>Total Amount</th>
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="ids" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td><input type="text" name="item_names[]" value="" id="item_names" style="width:250px;height:30px;" required  /></td>
                                 <td><input type="number" step="any" step="any" name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" required readOnly/></td>
                                 <td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label class="form-label">Packing Trims Costing: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Name</th>
                                 <th>Cons(Mtr/Nos)</th>
                                 <th>Rate</th>
                                 <th>Wastage %</th>
                                 <th>Total Amount</th>
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="idss" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td><input type="text" name="item_namess[]" value="" id="item_namess" style="width:250px;height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" step="any" class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;" required readOnly /></td>
                                 <td><button type="button" onclick="insertcone3();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <style>
                  table
                  {
                      border-collapse: collapse;
                      width: 100%;   
                  }
                  #footable_5 td
                  {
                      padding: 0px!important;
                  }
               </style>
               <!-- end row -->
               <div class="col-12">
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_5" class="table  table-bordered table-striped m-b-0  footable_5">
                           <thead>
                              <tr>
                                 <th>Cost Break Up</th>
                                 <th>Value   </th>
                                 <th>% On FOB Value</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>Total Fabric Cost</td>
                                 <td><input type="number" step="any" name="fabric_value" class="form-control gar_val tmcv" id="fabric_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" readonly></td>
                                 <td><input type="number" step="any" name="fabric_per" class="form-control gar_per tmcp" id="fabric_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Sewing Trims Cost</td>
                                 <td><input type="number" step="any" name="sewing_trims_value" class="form-control gar_val tmcv" id="sewing_trims_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" readonly></td>
                                 <td><input type="number" step="any" name="sewing_trims_per" class="form-control gar_per tmcp" id="sewing_trims_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Packing Trims Cost</td>
                                 <td><input type="number" step="any" name="packing_trims_value" class="form-control gar_val tmcv" id="packing_trims_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" readonly></td>
                                 <td><input type="number" step="any" name="packing_trims_per" class="form-control gar_per tmcp" id="packing_trims_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Manufacturing Cost</td>
                                 <td><input type="number" step="any" name="production_value" class="form-control gar_val tmcv value" id="production_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="production_per" class="form-control gar_per tmcp per" id="production_per" style="width:100px;" value="0.00"  onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Garment Washing Cost</td>
                                 <td><input type="number" step="any" name="dbk_value" class="form-control gar_val tmcv value" id="dbk_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="dbk_per" class="form-control gar_per tmcp per" id="dbk_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Printing Cost</td>
                                 <td><input type="number" step="any" name="printing_value" class="form-control tmcv value" id="printing_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="printing_per" class="form-control tmcp per" id="printing_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Embroidery Cost</td>
                                 <td><input type="number" step="any" name="embroidery_value" class="form-control gar_val tmcv value" id="embroidery_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="embroidery_per" class="form-control gar_per tmcp per" id="embroidery_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>Total Making Cost</td>
                                 <td><input type="number" step="any" name="total_making_value" class="form-control" id="total_making_value" style="width:100px;" value="0" readonly></td>
                                 <td><input type="number" step="any" name="total_making_per" class="form-control" id="total_making_per" style="width:100px;" value="0.00" readonly></td>
                              </tr>
                              <tr>
                                 <td>Garment Rejection %</td>
                                 <td><input type="number" step="any" name="garment_reject_value" class="form-control value" id="garment_reject_value" style="width:100px;" value="0" readonly></td>
                                 <td><input type="number" step="any" name="garment_reject_per" class="form-control per" id="garment_reject_per" style="width:100px;" value="0.00" onchange="calculateGarmentRejectionValue(this);" ></td>
                              </tr>
                              <tr>
                                 <td>IXD Cost</td>
                                 <td><input type="number" step="any" name="ixd_value" class="form-control value value1" id="ixd_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="ixd_per" class="form-control per per1" id="ixd_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Commission Cost</td>
                                 <td><input type="number" step="any" name="agent_commission_value" class="form-control value value1" id="agent_commission_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="agent_commission_per" class="form-control per per1" id="agent_commission_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Transport Cost</td>
                                 <td><input type="number" step="any" name="transport_value" class="form-control value value1" id="transport_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="transport_per" class="form-control per per1" id="transport_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Over Head Cost</td>
                                 <td><input type="number" step="any" name="other_value" class="form-control value value1" id="other_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="other_per" class="form-control per per1" id="other_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Testing Charges</td>
                                 <td><input type="number" step="any" name="testing_charges_value" class="form-control value value1" id="testing_charges_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="testing_charges_per" class="form-control per per1" id="testing_charges_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Finance Cost</td>
                                 <td><input type="number" step="any" name="finance_cost_value" class="form-control value value1" id="finance_cost_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="finance_cost_per" class="form-control per per1" id="finance_cost_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Other Cost</td>
                                 <td><input type="number" step="any" name="extra_value" class="form-control value value1" id="extra_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="extra_per" class="form-control per per1" id="extra_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Total Cost</td>
                                 <td><input type="number" step="any" name="total_cost_value" class="form-control" id="total_cost_value" style="width:100px;" value="0" onchange="calculatepercentage(this);" readonly></td>
                                 <td><input type="number" step="any" name="total_cost_per" class="form-control" id="total_cost_per" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td>
                              </tr>
                              <tr>
                                 <td>DBK Value 1</td>
                                 <td><input type="number" step="any" name="dbk_value1" class="form-control value" id="dbk_value1" style="width:100px;" value="0" onchange="calculatepercentage(this);" ></td>
                                 <td><input type="number" step="any" name="dbk_per1" class="form-control per" id="dbk_per1" style="width:100px;" value="0.00" onchange="calculate_percentage_value(this);" ></td>
                              </tr>
                              <tr>
                                 <td>Profit</td>
                                 <td><input type="number" step="any" name="profit_value" class="form-control" id="profit_value" style="width:100px;" value="0" readonly></td>
                                 <td><input type="number" step="any" name="profit_per" class="form-control" id="profit_per" style="width:100px;" value="0.00" readonly></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="col-sm-10">
                        <table id="footable_6" class="table  table-bordered table-striped m-b-0  footable_6">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th>Attachment</th>
                                 <th>Add</th> 
                                 <th>Remove</th> 
                              </tr>
                           </thead>
                           <tbody>
                              <tr>    
                                <td><input type="text" name="attachment_name[]" class="form-control" /></td>
                                <td><input type="file" name="attachment_image[]" class="form-control" /></td>
                                <td><button type="button" onclick="insertcone4();" class="btn btn-warning pull-left">+</button></td>
                                <td> 
                                    <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone4(this);" value="X">
                                </td>
                              </tr>
                           </tbody>
                        </table>
                  </div>
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Submit</button>
                     <a href="{{ Route('BuyerCosting.index') }}" class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </div>
         </form>
      </div>
      <!-- end card body -->
   </div>
   <!-- end card -->
</div>
<!-- end col -->
<!-- end col -->
</div>
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>
  
 
   function calOrderRate()
   {
       var exchange_rate=$('#exchange_rate').val();
       var inr_rate=$('#inr_rate').val();
       var order_rate=(parseFloat(inr_rate) * parseFloat(exchange_rate)).toFixed(2);
       $('#fob_rate').val(order_rate);
       calculateGarmentRejectionValue($("#garment_reject_per"));
   }
   
   
   function insertcone1()
   {
        var $lastRow = $("#footable_2 tr:last"); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val(''); 
        $newRow.appendTo("#footable_2"); // Append the cloned row to the table
        recalcIdcone1();
   }
   function insertcone2()
   {
        var $lastRow = $("#footable_3 tr:last"); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val('');
        $newRow.appendTo("#footable_3"); // Append the cloned row to the table
        recalcIdcone2();
   }
   function insertcone3()
   {
        var $lastRow = $("#footable_4 tr:last"); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val('');
        $newRow.appendTo("#footable_4"); // Append the cloned row to the table
        recalcIdcone3();
   }
   function insertcone4()
   {
        var $lastRow = $("#footable_6 tr:last"); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input').not('.btn-danger').val(''); // Clear the input values
        $newRow.appendTo("#footable_6"); // Append the cloned row to the table
   }
   function CalTotalValue()
   {
        var fob_rate = $("#fob_rate").val() ? $("#fob_rate").val() : 0;
        var total_qty = $("#total_qty").val() ? $("#total_qty").val() : 0;
       
        $("#total_value").val(parseFloat(total_qty) * parseFloat(fob_rate));
        $('#footable_2 > tbody > tr').each(function() {
            CalculateQtyRowPro($(this));
        });
       
        $('.per').each(function()
        { 
            calculate_percentage_value($(this)); 
        });
         
       
        GetTotalmakingCost();
        calculateTotalCost();
   }
   
    $(document).ready(function() {
    //   $('#frmData').submit(function() {
    //       $('#Submit').prop('disabled', true);
    //   }); 
       CalFabricSewingPacking();
       setTimeout(function() {
           //calculatepercentage($("#transport_value"));
           calculate_percentage_value(0); 
           GetTotalmakingCost();
           calculateTotalCost();
       }, 500);
   });
   
   function GetTotalmakingCost()
   {
       var total_making_value = 0;
       var total_making_per = 0;
       $(".tmcv").each(function()
       {
           total_making_value += parseFloat($(this).val());
       });
        var value = total_making_value ? total_making_value : 0;
        $("#total_making_value").val(value.toFixed(2));
       
       $(".tmcp").each(function()
       {
           total_making_per += parseFloat($(this).val());
       });
       
        var per = total_making_per ? total_making_per : 0; 
        $("#total_making_per").val(per.toFixed(2));
   }
   
   function calculateTotalCost()
   {
       
         var total_cost_value = 0;
         var total_cost_per = 0;
         
         var fob_rate = $("#fob_rate").val();
         var dbk_value1 = $("#dbk_value1").val();
         var dbk_per1 = $("#dbk_per1").val();
         $(".value1").not("#dbk_value1").each(function()
         {
                total_cost_value += parseFloat($(this).val());   
         });
         
         $(".per1").not("#dbk_per1").each(function()
         {
                total_cost_per += parseFloat($(this).val());   
         });
         var tcp = (parseFloat($("#total_cost_value").val())/parseFloat(fob_rate)) * 100;
         var tcv = parseFloat(total_cost_value) + parseFloat($("#total_making_value").val()) +  parseFloat($("#garment_reject_value").val());
         var tp = tcp ? tcp : 0;
         var tv = tcv ? tcv : 0;
         $("#total_cost_per").val(tp.toFixed(2)); 
         $("#total_cost_value").val(tv.toFixed(2));
         
         var pv = (parseFloat(fob_rate) - parseFloat($("#total_cost_value").val()) + parseFloat(dbk_value1));
         var pp = (parseFloat(100) - parseFloat($("#total_cost_per").val()) + parseFloat(dbk_per1));
         var v = pv ? pv : 0;
         var p = pp ? pp : 0;
         $("#profit_value").val(v.toFixed(2)); 
         $("#profit_per").val(p.toFixed(2));
         
         
   }
   
   function CalFabricSewingPacking()
   {
       var total_fabric_cost = 0;
       var total_sewing_cost = 0;
       var total_packing_cost = 0;
       $(".FABRIC").each(function(){
           total_fabric_cost += parseFloat($(this).val());
       });
       $(".SEWING").each(function()
       {
           total_sewing_cost += parseFloat($(this).val());
       });
       $(".PACKING").each(function(){
           total_packing_cost += parseFloat($(this).val());
       }); 
       $("#fabric_value").val(total_fabric_cost.toFixed(2));
       $("#sewing_trims_value").val(total_sewing_cost.toFixed(2));
       $("#packing_trims_value").val(total_packing_cost.toFixed(2));
   }
   function calculate_percentage_value(row)
   {    
        var fob_rate = $('#fob_rate').val();
        var value = $(row).val();
        var total_value = ((value * fob_rate)/100).toFixed(2);
        $(row).parent().parent('tr').find('.value').val(total_value ? total_value : 0); 
        CalFabricSewingPacking();
        setTimeout(function() 
        {
           calculateGarmentRejectionValue();
        }, 500);
        GetTotalmakingCost();
        calculateTotalCost();
   }
   
      
   function calculatepercentage(row)
   {  
     var fob_rate = $('#fob_rate').val(); 
     var per = $(row).val(); 
     var total_per = ((per/fob_rate) * 100).toFixed(2); 
     $(row).parent().parent('tr').find('.per').val(total_per ? total_per : 0);  
     
     CalFabricSewingPacking();
     setTimeout(function() 
     {
        calculateGarmentRejectionValue();
     }, 500);
     GetTotalmakingCost();
     calculateTotalCost();
   }
   
        
   function calculateGarmentRejectionValue()
   {   
     var value = $("#garment_reject_per").val(); 
     var total_making_value = $('#total_making_value').val();
     
     $("#garment_reject_value").val(((total_making_value * value)/100).toFixed(2) ? ((total_making_value * value)/100).toFixed(2) : 0); 
     calculateTotalCost();
   }
   
   
   function SalesOrderDisable(type)
   {
         if(type==1)
         {
              document.getElementById('sales_order_no').disabled=false;
         }
         else
         {
               document.getElementById('sales_order_no').disabled=true;
         }
   }
 
   
   
   function EnableFields()
   { 
     $("input").removeAttr('disabled');
   }
   
   
   
   
   $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
      
      mycalc();
   
   });
   
   
   
   $('table.footable_2').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"]', function()
   { 
        CalculateQtyRowPro($(this).closest("tr"));
   
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPro(row)
   {   
       var consumption=+row.find('input[name^="consumption[]"]').val();
       var wastage=+row.find('input[name^="wastage[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       row.find('input[name^="total_amount[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   $('table.footable_3').on('keyup', 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"]', function()
   {
       CalculateQtyRowPros($(this).closest("tr"));
       
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPros(row)
   {   
       var consumption=+row.find('input[name^="consumptions[]"]').val();
       var wastage=+row.find('input[name^="wastages[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       
       row.find('input[name^="total_amounts[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   
   
    
   $('table.footable_4').on("keyup", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"]', function()
   { 
        CalculateQtyRowPross($(this).closest("tr"));
   
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPross(row)
   {   
       var consumption=+row.find('input[name^="consumptionss[]"]').val();
       var wastage=+row.find('input[name^="wastagess[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       row.find('input[name^="total_amountss[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   function mycalc()
   {   
   
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('FABRIC');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
       }
       document.getElementById("fabric_value").value = sum1.toFixed(2);
       
       sum2 = 0.0;
       var amounts = document.getElementsByClassName('SEWING');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum2 += parseFloat(a);
       }
       document.getElementById("sewing_trims_value").value = sum2.toFixed(2);
       
       sum3 = 0.0;
       var amounts = document.getElementsByClassName('PACKING');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum3 += parseFloat(a);
       }
       document.getElementById("packing_trims_value").value = sum3.toFixed(2);
       
        var fob_rate=$('#fob_rate').val();  
       
       
       var fabricpercentage= ((sum1.toFixed(2) / fob_rate) * 100).toFixed(2);
       var sewing_trimspercentage= ((sum2.toFixed(2) / fob_rate) * 100).toFixed(2);
       var packing_trimspercentage= ((sum3.toFixed(2) / fob_rate) * 100).toFixed(2);
       
       $('#fabric_per').val(fabricpercentage ? fabricpercentage : 0);
       $('#sewing_trims_per').val(sewing_trimspercentage ? sewing_trimspercentage : 0);
       $('#packing_trims_per').val(packing_trimspercentage ? packing_trimspercentage : 0);
    
   
   }
   
   
   function calculateamount()
   { 
      var prod_qty=document.getElementById('prod_qty').value;
      var rate_per_piece=document.getElementById('rate_per_piece').value;
      
      
      var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
      $('#total_amount').val(total_amount.toFixed(2));
   }
    
   
   function deleteRowcone1(btn) 
   { 
       var tbl_count = $('#footable_2 > tbody > tr').length;
       if(tbl_count > 1)
       {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row); 
           recalcIdcone1(); 
           
           CalFabricSewingPacking();
           setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
           }, 500); 
       } 
   }
    
   function deleteRowcone2(btn)
   { 
       var tbl_count = $('#footable_3 > tbody > tr').length;
       if(tbl_count > 1)
       {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row);
           recalcIdcone2();
           CalFabricSewingPacking();
           setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
           }, 500); 
       }
   }
   
   function deleteRowcone3(btn) 
   {
       var tbl_count = $('#footable_4 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            recalcIdcone3();
            // document.getElementById('Submit').disabled=true; 
            CalFabricSewingPacking();
            setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
            }, 500);
       }
   }
   
   function deleteRowcone4(btn) 
   {
       var tbl_count = $('#footable_6 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
       }
   }
   
   
   function recalcIdcone1()
   {
       $.each($("#footable_2 tr"),function (i,el)
       {
            $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
   function recalcIdcone2()
   {
       $.each($("#footable_3 tr"),function (i,el)
       {
            $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
   function recalcIdcone3()
   {
       $.each($("#footable_4 tr"),function (i,el)
       {
          $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
   
</script>
<!-- end row -->
@endsection