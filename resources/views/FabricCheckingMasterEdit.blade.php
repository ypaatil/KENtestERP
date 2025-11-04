@extends('layouts.master') 
@section('content')
<!-- end page title -->
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Fabric Checking Update</h4>
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
            @if(isset($FabricCheckingMasterList))
            <form action="{{ route('FabricChecking.update',$FabricCheckingMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="in_date" class="form-label">Check Date</label>
                        <input type="hidden" name="chk_code" class="form-control" id="chk_code" value="{{ $FabricCheckingMasterList->chk_code }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricCheckingMasterList->c_code }}">
                        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FabricCheckingMasterList->created_at }}">
                        <input type="date" name="chk_date" class="form-control" id="chk_date" value="{{ $FabricCheckingMasterList->chk_date }}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="in_date" class="form-label">GRN No</label>
                        <select name="in_code" class="form-select select2" id="in_code" required onchange="getDetails(this.value);getMasterdata(this.value);">
                           <option value="">--GRN No--</option>
                           @foreach($GRNList as  $row_grn)
                           {
                           <option value="{{ $row_grn->in_code }}"
                           {{ $row_grn->in_code == $FabricCheckingMasterList->in_code ? 'selected="selected"' : '' }} 
                           >{{ $row_grn->in_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">CP Type</label>
                        <select name="cp_id" class="form-select" id="cp_id" required disabled>
                           <option value="">--Select CP Type--</option>
                           @foreach($CPList as  $rowCP)
                           {
                           <option value="{{ $rowCP->cp_id }}"
                           {{ $rowCP->cp_id == $FabricCheckingMasterList->cp_id ? 'selected="selected"' : '' }}                 
                           >{{ $rowCP->cp_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-invoice_no-input" class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ $FabricCheckingMasterList->invoice_no }}" id="formrow-invoice_no-input" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $FabricCheckingMasterList->invoice_date }}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">PO Code</label>   
                        <select name="po_code" class="form-select" id="po_code" onchange="getPODetails();" disabled  >
                           <option value="">PO code</option>
                           @foreach($POList as  $rowpol)
                           {
                           <option value="{{ $rowpol->pur_code  }}"
                           {{ $rowpol->pur_code == $PONo[0]->pur_code ? 'selected="selected"' : '' }} 
                           >{{ $rowpol->pur_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">PO Type</label>
                        <select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();" disabled>
                           <option value="">Type</option>
                           @foreach($POTypeList as  $rowpo)
                           {
                           <option value="{{ $rowpo->po_type_id  }}"
                           {{ $rowpo->po_type_id == $FabricCheckingMasterList->po_type_id ? 'selected="selected"' : '' }}             
                           >{{ $rowpo->po_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select" id="Ac_code" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $FabricCheckingMasterList->Ac_code ? 'selected="selected"' : '' }}           
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                      <div class="mb-3">
                         <label for="bill_to" class="form-label">Bill To</label>
                         <select name="bill_to" class="form-select" id="bill_to" disabled>
                            @foreach($BillToList as  $row) 
                                <option value="{{ $row->sr_no }}" {{ $row->sr_no == $FabricCheckingMasterList->bill_to ? 'selected="selected"' : '' }} >{{ $row->trade_name }}({{$row->site_code}})</option> 
                            @endforeach
                         </select>
                      </div>
                   </div> 
                  <div class="col-md-2">
                     <div class="form-check form-check-primary mb-3">
                        <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening"  style="font-size: 30px;margin-top: 30px;margin-left: 0px;"
                        @if($FabricCheckingMasterList->is_opening==1)checked @endif>
                        <label class="form-check-label" for="is_opening" style="margin-top: 30px;position: absolute;margin-left: 20px;font-size: 20px;">
                        Opening Stock
                        </label>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="shrinkage" class="form-label">Shrinkage</label>
                        <input type="number" class="form-control" id="shrinkage" name="shrinkage" value="0" onchange="SetShrinkage();" >
                     </div>
                  </div>
               </div>
               <input type="number" value="{{ count($FabricCheckingDetails) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                        <thead>
                           <tr>
                              <th>Roll No</th>
                              <th>Item Name</th>
                              <th>Part</th>
                              <th>Supplier Roll No</th>
                              <th>GRN Meter</th>
                              <th>QC Meter</th>
                              <th>Width</th>
                              <th>Shade</th>
                              <th>Status</th>
                              <th>Defect</th>
                              <th>Rejected</th>
                              <th>Short Meter</th>
                              <th>Extra Meter</th>
                              <th>Shrinkage</th>
                              <th>TrackCode</th>
                              <th>Rack Location</th>
                              <th>Print Barcode</th>
                              <th>Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                           @if(count($FabricCheckingDetails)>0)
                           @php $no=1; @endphp
                           @foreach($FabricCheckingDetails as $List) 
                           @php
                           $outwardData = DB::SELECT("SELECT count(*) as total_count FROM fabric_outward_details 
                           INNER JOIN fabric_outward_master ON fabric_outward_master.fout_code = fabric_outward_details.fout_code
                           WHERE fabric_outward_details.track_code='".$List->track_code."' AND fabric_outward_master.delflag=0");
                           $total_count = isset($outwardData[0]->total_count) ? $outwardData[0]->total_count : 0;
                           if($total_count > 0)
                           {
                           $dis = 'disabled';
                           }
                           else
                           {
                           $dis = '';
                           }
                           //DB::enableQueryLog();
                            $grnSummaryData = DB::SELECT("SELECT count(chk_code) as count FROM fabric_summary_grn_master WHERE chk_code='".$FabricCheckingMasterList->chk_code."'");
                            //dd(DB::getQueryLog());
                            $grn_count = isset($grnSummaryData[0]->count) ? $grnSummaryData[0]->count : 0;
                            
                            if($grn_count > 0)
                            {
                                $grn_status = 'readonly';
                            }
                            else
                            {
                                $grn_status = '';
                            }
                    
                           @endphp
                           <tr>
                              <td><input type="text" name="id[]" value="@php echo $no; @endphp" id="id" style="width:50px;" {{$dis}} /></td>
                              <td>
                                 <select name="item_code[]"  id="item_code" style="width:200px;height:30px;" required  disabled>
                                    <option value="{{ $List->item_code }}">{{ $List->item_name }}</option>
                                 </select>
                              </td>
                              <td>
                                 <select name="part_id[]"  id="part_id" style="width:200px;height:30px;" required  disabled>
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    {
                                    <option value="{{ $row->part_id }}"
                                    {{ $row->part_id == $List->part_id ? 'selected="selected"' : '' }}        
                                    >{{ $row->part_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="text"    name="roll_no[]"  value="{{ $List->roll_no }}" id="roll_no" style="width:80px;" required  {{$dis}} /></td>
                              <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="{{ $List->taga_qty }}" id="taga_qty1" style="width:50px;height:30px;"/>
                                 <input type="text" readOnly  name="old_meter[]" onkeyup="mycalc();" value="{{ $List->old_meter }}" id="old_meter1" style="width:80px;" required  {{$dis}}/>
                              </td>
                              <td><input type="text"   class="METER" name="meter[]" onkeyup="mycalc();" value="{{ $List->meter }}" id="meter1" style="width:80px;height:30px;" required  {{$dis}} {{$grn_status}} />
                              <td><input type="text"   name="width[]"  value="{{ $List->width }}" id="width" style="width:80px;" required  {{$dis}} /></td>
                              <input type="hidden"   class="KG" name="kg[]" onkeyup="mycalc();" value="{{ $List->kg }}" id="kg" style="width:80px;height:30px;" /> 
                              <input type="hidden"   name="item_rate[]"   value="{{ $List->item_rate }}" id="item_rate" style="width:80px;height:30px;" /> 
                              </td>
                              <td>
                                 <select name="shade_id[]"  id="shade_id" style="width:100px;height:30px;"   {{$dis}} >
                                 <option value="">--Shade--</option>
                                 @foreach($ShadeList as  $row)
                                 {
                                 <option value="{{ $row->shade_id }}"
                                 {{ $row->shade_id == $List->shade_id ? 'selected="selected"' : '' }} >   
                                 {{ $row->shade_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="fcs_id[]"  id="fcs_id" style="width:100px;height:30px;"  {{$dis}} >
                                 <option value="">--Fabric Status--</option>
                                 @foreach($FabCheckList as  $row)
                                 {
                                 <option value="{{ $row->fcs_id }}"
                                 {{ $row->fcs_id == $List->status_id ? 'selected="selected"' : '' }}   
                                 >{{ $row->fcs_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="defect_id[]"  id="defect_id" style="width:100px;" required {{$dis}}>
                                 <option value="0">--Fabric Defect--</option>
                                 @foreach($DefectList as  $row)
                                 {
                                 <option value="{{ $row->fdef_id }}"
                                 {{ $row->fdef_id == $List->defect_id ? 'selected="selected"' : '' }}  
                                 >{{ $row->fabricdefect_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              @php
                              
                                if($List->reject_short_meter > 0)
                                {
                                    $short_meter = $List->reject_short_meter;
                                }
                                else if($List->short_meter > 0)
                                {
                                    $short_meter = $List->short_meter;
                                }
                                else
                                {
                                    $short_meter = 0;
                                }
                              
                              @endphp
                              <td><input type="text" name="reject_short_meter[]"  value="{{ $List->reject_short_meter }}" id="reject_short_meter" style="width:80px;height:30px;" required {{$dis}} /></td>
                              <td><input type="text" name="short_meter[]"  value="{{  $short_meter }}" id="short_meter" style="width:80px;height:30px;" required {{$dis}} /></td>
                              <td><input type="text" name="extra_meter[]"  value="{{ $List->extra_meter }}" id="extra_meter" style="width:80px;" required {{$dis}} /></td>
                              <td><input type="number" name="shrinkage[]"  value="{{ $List->shrinkage }}" id="shrinkage" style="width:80px;" /></td>
                              <td><input type="text" name="track_code[]"  value="{{ $List->track_code }}" id="track_code" style="width:80px;height:30px;" readOnly {{$dis}}/></td>
                              <td>
                                 <select name="rack_id[]"  id="rack_id" style="width:100px;height:30px;" {{$dis}} >
                                 <option value="0">--Racks--</option>
                                 @foreach($RackList as  $row)
                                 {
                                 <option value="{{ $row->rack_id }}"
                                 {{ $row->rack_id == $List->rack_id ? 'selected="selected"' : '' }}   
                                 >{{ $row->rack_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td><i   style="font-size:25px;" onclick="CalculateRowPrint(this);" name="print"  class="fa fa-print" ></td>
                              <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                           </tr>
                           @php $no=$no+1;  @endphp
                           @endforeach
                           @else
                           <tr>
                              <td><input type="text" name="id[]" value="1" id="id" style="width:50px;" {{$dis}} /></td>
                              <td>
                                 <select name="item_code[]"  id="item_code" style="width:100px;" required {{$dis}}>
                                 <option value="">--Item--</option>
                                 @foreach($ItemList as  $row)
                                 {
                                 <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="part_id[]"  id="fg_id" style="width:100px;" required {{$dis}}>
                                 <option value="">--Job Style--</option>
                                 @foreach($FGList as  $row)
                                 {
                                 <option value="{{ $row->fg_id }}">{{ $row->fg_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;"/>
                                 <input type="text"   name="oldmeter[]" onkeyup="mycalc();" value="0" id="oldmeter1" style="width:80px;" required {{$dis}}/>
                              </td>
                              <td><input type="text" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;" required {{$dis}} {{$grn_status}} /></td>
                              <td><input type="text" name="width[]"  value="" id="width" style="width:80px;" required {{$dis}}/></td>
                              <input type="hidden" step="any" class="KG" name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;"/>
                              <input type="hidden"   name="item_rate[]"   value="0" id="item_rate" style="width:80px;height:30px;"/> 
                              <td>
                                 <select name="shade_id[]"  id="shade_id" style="width:100px;" required {{$dis}}>
                                 <option value="">--Shade--</option>
                                 @foreach($ShadeList as  $row)
                                 {
                                 <option value="{{ $row->shade_id }}">{{ $row->shade_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="fcs_id[]"  id="fcs_id" style="width:100px;" required {{$dis}}>
                                 <option value="">--Fabric Status--</option>
                                 @foreach($FabCheckList as  $row)
                                 {
                                 <option value="{{ $row->fcs_id }}">{{ $row->fcs_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="defect_id[]"  id="defect_id" style="width:100px;" required {{$dis}}>
                                 <option value="0">--Defect--</option>
                                 @foreach($DefectList as  $row)
                                 {
                                 <option value="{{ $row->fdef_id }}">{{ $row->fabricdefect_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td><input type="text" name="reject_short_meter[]"  value="0" id="reject_short_meter" style="width:80px;" required {{$dis}}/></td>
                              <td><input type="text" name="short_meter[]"  value="0" id="short_meter" style="width:80px;" required {{$dis}}/></td>
                              <td><input type="text" name="extra_meter[]"  value="0" id="extra_meter" style="width:80px;" required {{$dis}}/></td>
                              <td><input type="number" name="shrinkage[]"  value="{{ $List->shrinkage }}" id="shrinkage" style="width:80px;" /></td>
                              <td><input type="text" name="track_code[]"  value="" id="track_code" style="width:80px;" readOnly /></td>
                              <td>
                                 <select name="rack_id[]"  id="rack_id" class="select2" style="width:100px;" required {{$dis}}>
                                 <option value="">--Racks--</option>
                                 @foreach($RackList as  $row)
                                 {
                                 <option value="{{ $row->rack_id }}">{{ $row->rack_name }}</option>
                                 }
                                 @endforeach
                                 </select>
                              </td>
                              <td><i   style="font-size:25px;" onclick="CalculateRowPrint(this);" name="print"  class="fa fa-print" ></td>
                              <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                           </tr>
                           @endif
                        </tbody>
                        <tfoot>
                           <tr>
                              <th>Sr No</th>
                              <th>Item Name</th>
                              <th>Part</th>
                              <th>GRN Meter</th>
                              <th>QC Meter</th>
                              <th>Width</th>
                              <th>Shade</th>
                              <th>Status</th>
                              <th>Defect</th>
                              <th>Rejected</th>
                              <th>Short Meter</th>
                              <th>Extra Meter</th>
                              <th>Shrinkage</th>
                              <th>TrackCode</th>
                              <th>Rack Location</th>
                              <th>Remove</th>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
         </div>
         <div class="col-md-12 m-2">
         <div class="row">
         <div class="col-md-2">
         <div class="mb-3">
         <label for="total_meter" class="form-label">Total Meter</label>
         <input type="number" step="0.01"  name="total_meter" class="form-control" id="total_meter" value="{{ $FabricCheckingMasterList->total_meter }}" readOnly>
         </div>
         </div>
         <div class="col-md-2">
         <div class="mb-3">
         <label for="total_kg" class="form-label">Total KG</label>
         <input type="number" step="0.01"  name="total_kg" class="form-control" id="total_kg" value="{{ $FabricCheckingMasterList->total_kg }}" readOnly>
         </div>
         </div>
         <div class="col-md-2">
         <div class="mb-3">
         <label for="total_qty" class="form-label">Total Taga</label>
         <input type="number"   name="total_taga_qty" class="form-control" id="total_taga_qty" value="{{ $FabricCheckingMasterList->total_taga_qty }}" readOnly>
         </div>
         </div>
         <div class="col-sm-4">
         <div class="mb-3">
         <label for="formrow-inputState" class="form-label">Narration</label>
         <input type="text" name="in_narration" class="form-control" id="in_narration"  value="{{ $FabricCheckingMasterList->in_narration }}"   />
         </div>
         </div>
         <div class="col-sm-6">
         <label for="formrow-inputState" class="form-label"></label>
         <div class="form-group">
         <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
         <a href="{{ Route('FabricChecking.index') }}" class="btn btn-warning w-md">Cancel</a>
         </div>
         </div>
         </div>
         </div>
         </form>
         @endif
      </div>
      <!-- end card body -->
   </div>
   <!-- end card -->
</div>
<!-- end col -->
<!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>

   $(document).ready(function() {
      $('#frmData').submit(function() {
          $('#Submit').prop('disabled', true);
      }); 
   });
   
    function GetPurchaseBillDetails()
    {
       var po_code = $("#po_code").val(); 
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetPurchaseBillToDetails') }}",
           data:{'po_code':po_code},
           success: function(data)
           { 
               $("#bill_to").html(data.detail); 
           }
        }); 
    } 
   
   
   $(document).on("change", 'input[name^="meter[]"]', function (event) {
      CalculateRow($(this).closest("tr"));
      
   });
   
   function CalculateRow(row)
   { 
   var old_meter=+row.find('input[name^="old_meter[]"]').val();
      var meter=+row.find('input[name^="meter[]"]').val();
      var extra_meter=+row.find('input[name^="extra_meter[]"]').val();
      var short_meter=+row.find('input[name^="short_meter[]"]').val();
       if(old_meter<meter)
       {   
           var em=parseFloat(meter - old_meter - short_meter).toFixed(2);
           row.find('input[name^="extra_meter[]"]').val(em);
           row.find('input[name^="short_meter[]"]').val(0);
       }
       else if(old_meter>meter)
       {
          var rm=parseFloat(old_meter - (meter+extra_meter)).toFixed(2);
          row.find('input[name^="short_meter[]"]').val(rm);
          row.find('input[name^="extra_meter[]"]').val(0);
   
       }
     
      
    	mycalc();
   }
   
   
   $(document).on("change", 'input[name^="extra_meter[]"]', function (event) {
      CalculateRow1($(this).closest("tr"));
   });
   
   function CalculateRow1(row)
   { 
   var old_meter=+row.find('input[name^="old_meter[]"]').val();
      //var meter=+row.find('input[name^="meter[]"]').val();
      var extra_meter=+row.find('input[name^="extra_meter[]"]').val();
      var short_meter=+row.find('input[name^="short_meter[]"]').val();
      var meter=parseFloat(old_meter + extra_meter).toFixed(2);
      row.find('input[name^="meter[]"]').val(meter);
      mycalc();
   }
   
   
   $(document).on("change", 'input[name^="short_meter[]"]', function (event) 
   {CalculateRow2($(this).closest("tr"));});
   
   function CalculateRow2(row)
   { 
   var old_meter=+row.find('input[name^="old_meter[]"]').val();
      var meter=+row.find('input[name^="meter[]"]').val();
      var extra_meter=+row.find('input[name^="extra_meter[]"]').val();
      var short_meter=+row.find('input[name^="short_meter[]"]').val();
      var meter=parseFloat(old_meter + extra_meter-short_meter).toFixed(2);
      row.find('input[name^="meter[]"]').val(meter);
   mycalc();
   }
   
   function EnableFields()
   { 
   $("select").prop('disabled', false);
   $("input").prop('disabled', false);
   }
   
   
   
   function getDetails(str)
   {
   
   $.ajax({
   type: "GET",
   url: "{{ route('InwardList') }}",
   data:'in_code='+str,
   success: function(data){
   $("#footable_2").html(data.html);
   
   setTimeout(function(){
   $("#footable_2 tr td  select[name='shade_id[]']").each(function() {
        $(this).closest("tr").find('select[name="shade_id[]"]').select2();
        $(this).closest("tr").find('select[name="defect_id[]"]').select2();
        $(this).closest("tr").find('select[name="fcs_id[]"]').select2();
        $(this).closest("tr").find('select[name="rack_id[]"]').select2();
       });
   }, 1000);
   
   }
   });
   }
   
   
   
   function getMasterdata(in_code)
   {
   
   $.ajax({
      type: "GET",
      dataType:"json",
      url: "{{ route('InwardMasterList') }}",
      data:'in_code='+in_code,
      success: function(data){
          
          console.log(data);
          
         $("#cp_id").val(data[0]['cp_id']);
         $("#Ac_code").val(data[0]['Ac_code']);
         $("#po_code").val(data[0]['po_code']);
         $("#total_taga_qty").val(data[0]['total_taga_qty']);
         $("#total_meter").val(data[0]['total_meter']);
         $("#total_kg").val(data[0]['total_kg']);
         $("#invoice_no").val(data[0]['invoice_no']);
         $("#po_type_id").val(data[0]['po_type_id']); 
         $("#invoice_date").val(data[0]['invoice_date']);
         $("#in_narration").val(data[0]['in_narration']);
         if(data[0]['is_opening']){ $('#is_opening').prop('checked', true);}
           
          document.getElementById('Ac_code').disabled =true;
          document.getElementById('po_type_id').disabled=true;
          document.getElementById('po_code').disabled=true;
          document.getElementById('cp_id').disabled=true;
         GetPurchaseBillDetails();
       
       
      }
      });
   }
   
   var indexcone = 2;
   function insertcone(){
   
   var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.value=indexcone;
   
   cell1.appendChild(t1);
   
   var cell2 = row.insertCell(1);
   var t2=document.createElement("input");
   t2.style="display: table-cell; width:80px;";
   t2.type="text";
   t2.required="true";
   t2.id = "style_no"+indexcone;
   t2.name="style_no[]";
   t2.onkeyup=mycalc();
   t2.value="0";
   cell2.appendChild(t2);
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#fg_id"),
   y = x.clone();
   y.attr("id","fg_id");
   y.attr("name","fg_id[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell4 = row.insertCell(3);
   var t4=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   y.attr("id","item_code");
   y.attr("name","item_code[]");
   y.width(100);
   y.appendTo(cell4);
   
   var cell5 = row.insertCell(4);
   var t5=document.createElement("select");
   var x = $("#color_id"),
   y = x.clone();
   y.attr("id","color_id");
   y.attr("name","color_id[]");
   y.width(100);
   y.appendTo(cell5);
   
   var cell6 = row.insertCell(5);
   var t6=document.createElement("input");
   t6.style="display: table-cell; width:80px;";
   t6.type="text";
   t6.required="true";
   t6.id = "width"+indexcone;
   t6.name="width[]";
   t6.onkeyup=mycalc();
   t6.value="0";
   cell6.appendChild(t6);
   
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;";
   t7.type="hidden";
   t7.className="TAGAQTY";
   t7.required="true";
   t7.id = "taga_qty"+indexcone;
   t7.name="taga_qty[]";
   t7.onkeyup=mycalc();
   t7.value="1";
   cell6.appendChild(t7);
   
   
   var cell7 = row.insertCell(6);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;";
   t8.type="text";
   t8.id = "oldmeter"+indexcone;
   t8.name="oldmeter[]";
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
   var cell9 = row.insertCell(7);
   var t9=document.createElement("input");
   t9.style="display: table-cell; width:80px;";
   t9.type="text";
   t9.className="METER";
   t9.id = "meter"+indexcone;
   t9.name="meter[]";
   t9.onkeyup=mycalc();
   cell9.appendChild(t9);
   
   var cell10 = row.insertCell(8);
   var t10=document.createElement("input");
   t10.style="display: table-cell; width:80px;";
   t10.type="text";
   t10.id = "track_code"+indexcone;
   t10.name="track_code[]";
   cell10.appendChild(t10);
   
   var cell11=row.insertCell(9);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone(this)");
   cell11.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_3').find('tr').eq(indexcone);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   
   indexcone++;
   mycalc();
   recalcIdcone();
   }
   
   function mycalc()
   { 
   
   // sum1 = 0.0;
   // var amounts = document.getElementsByClassName('TAGAQTY');
   // //alert("value="+amounts[0].value);
   // for(var i=0; i<amounts .length; i++)
   // { 
   // var a = +amounts[i].value;
   // sum1 += parseFloat(a);
   // }
   // document.getElementById("total_taga_qty").value = sum1.toFixed(2);
   document.getElementById("total_taga_qty").value =document.getElementById('cntrr').value;
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('METER');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_meter").value = sum1.toFixed(2);
   
   
   }
   
   
   
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   mycalc();
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
   
   }
   }
   
   function recalcIdcone(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   
   
   $(document).on("click", 'input[name^="print[]"]', function (event) {
   
        
        $("select").prop('disabled', false);
        setTimeout(CalculateRowPrint($(this).closest("tr")), 1000)
        
      
    });
        
    function CalculateRowPrint(btn)
    { 
        
        var row = $(btn).closest("tr");
        var width=+row.find('input[name^="width[]"]').val();
        var meter=+row.find('input[name^="meter[]"]').val();
        var part_id=+row.find('select[name^="part_id[]"]').val();
        var shade_id=+row.find('select[name^="shade_id[]"]').val();
        var fcs_id=+row.find('select[name^="fcs_id[]"]').val();
        var item_code=+row.find('select[name^="item_code[]"]').val();
        var track_code=row.find('input[name^="track_code[]"]').val();
        var roll_no=row.find('input[name^="roll_no[]"]').val();
       
        
        var po_code=$("#po_code").val();
        
            if(meter>0 && width!=0 && shade_id!='' && item_code!='' && part_id!='' && po_code!='' && track_code!='')
            {
                    $.ajax({
                        type: "GET",
                        dataType:"json",
                        url: "{{ route('PrintBarcode') }}",
                        data:{'po_code':po_code, 'item_code':item_code,'roll_no':roll_no, 'width':width,'meter':meter, 'shade_id':shade_id,'part_id':part_id,'track_code':track_code, 'fcs_id':fcs_id},
                        success: function(data){
                             
                         if((data['result'])=='success')
                        {
                          alert('Barcode For Roll: '+track_code+' is Ready');
                           window.open('https://kenerp.com/barcode/index.php?id=2', '_blank').focus();
                        }
                        else
                        {
                            $alert('Data Can Not Be Printed');
                        }
                          $("select").prop('disabled', true);
                    }
                    });
            }
            else
            {
                alert("Please Fill Required Fields!");
                  $("select").prop('disabled', true);
            }
            
        
        
        
   }
   
</script>
<!-- end row -->
@endsection