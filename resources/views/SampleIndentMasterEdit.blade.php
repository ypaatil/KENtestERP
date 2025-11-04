@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sample Indent Edit</h4>
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
            @if(isset($SampleIndent))
            <form action="{{ route('SampleIndent.update',$SampleIndent) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_indent_date" class="form-label">Date</label>
                        <input type="date" name="sample_indent_date" class="form-control" id="sample_indent_date" value="{{ $SampleIndent->sample_indent_date}}" required>
                        <input type="hidden" name="sample_indent_id" class="form-control" id="sample_indent_id" value="{{ $SampleIndent->sample_indent_id}}">
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN No</label>
                        <input type="text" name="sample_indent_code" class="form-control" id="sample_indent_code" value="{{ $SampleIndent->sample_indent_code}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" disabled>
                           <option>--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}" {{ $row->ac_code == $SampleIndent->Ac_code ? 'selected="selected"' : '' }}  >{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select select2" id="brand_id">
                           <option>--Select--</option> 
                           @foreach($BrandList as  $row) 
                                <option value="{{ $row->brand_id }}" {{ $row->brand_id == $SampleIndent->brand_id ? 'selected="selected"' : '' }}  >{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" class="form-select" id="mainstyle_id" onchange="GetSubStyleList();" required>
                           <option value="">--- Select ---</option>
                           @foreach($MainStylelist as  $row) 
                                <option value="{{ $row->mainstyle_id }}"  {{ $row->mainstyle_id == $SampleIndent->mainstyle_id ? 'selected="selected"' : '' }} >{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select" id="substyle_id" required>
                           <option value="">--- Select ---</option>
                           @foreach($SubStylelist as  $row) 
                                <option value="{{ $row->substyle_id }}" {{ $row->substyle_id == $SampleIndent->substyle_id ? 'selected="selected"' : '' }}  >{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $SampleIndent->style_description}}" required >
                        <input type="hidden" name="userId" value="{{ $SampleIndent->userId}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="{{ $SampleIndent->sam}}" required> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" required onchange="GetDepartmentType();" required>
                           <option value="">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}" {{ $row->sample_type_id == $SampleIndent->sample_type_id ? 'selected="selected"' : '' }}  >{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select " id="dept_type_id" disabled>
                           <option value="">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}" {{ $row->dept_type_id == $SampleIndent->dept_type_id ? 'selected="selected"' : '' }}  >{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select select2" id="sz_code" onchange="SizeSampleIndentList(this.value);" disabled>
                           <option value="">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}" {{ $row->sz_code == $SampleIndent->sz_code ? 'selected="selected"' : '' }}  >{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_required_date" class="form-label">Sample Required Date</label>
                        <input type="date" name="sample_required_date" class="form-control" id="sample_required_date" value="{{ $SampleIndent->sample_required_date}}"  required> 
                     </div>
                  </div>
               </div>
                <div class="col-md-12" id="order_qty_label">
                  <h4><b>Order Qty</b></h4>
               </div>
                <div class="table-wrap">
                   <div class="table-responsive" id="order_qty">
                        <table id="footable_1" class="table table-bordered table-striped m-b-0 footable_1">
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Color</th>
                                    @foreach ($SizeDetailList as $sz)
                                        <th>{{$sz->size_name}}</th>
                                    @endforeach
                                    <th>Total Qty</th>
                                </tr>
                            </thead>
                            <tbody id="SampleIndent">
                                @if(count($SampleIndentDetailList) > 0)
                                    @php $no = 1; $n = 1; @endphp
                                    @foreach($SampleIndentDetailList as $List)
                                        <tr>
                                            <td><input type="text" name="id[]" value="{{$no}}" id="id{{$no}}" style="width:50px;" /></td>
                                            <td><input type="text" name="color[]" class="color" value="{{$List->color}}" id="color{{$no}}" style="width:250px; height:30px;" onchange="CheckColorExist(this);" required /></td>
                                            @php   
                                                $n = 1;  
                                                $SizeQtyList = explode(',', $List->size_qty_array); 
                                                
                                                $QcData = DB::SELECT("SELECT sum(size_qty_total) as total_Stitiching FROM sample_qc_stitching_detail WHERE sample_indent_code='".$List->sample_indent_code."'");
                                                
                                                $min_qty = isset($QcData[0]->total_Stitiching) ? $QcData[0]->total_Stitiching : 0;
                                            @endphp
                                            @foreach($SizeQtyList as $key => $szQty)
                                                <td>
                                                    <input type="number" name="s{{$n}}[]" class="size_id" value="{{$szQty}}" id="size_id{{$no}}_{{$n}}" min="{{$min_qty}}" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);" />
                                                    <input type="hidden" name="olds{{$n}}[]" class="old_size_id" value="{{$min_qty}}" id="old_size_id{{$no}}_{{$n}}" min="{{$min_qty}}" style="width:80px; height:30px;" />
                                                </td>
                                                @php $n++; @endphp
                                            @endforeach
                                            <td>
                                                <input type="number" name="order_qty[]" class="QTY" value="{{$List->size_qty_total}}" id="size_qty_total{{$no}}" style="width:80px; height:30px;" />
                                                <input type="hidden" name="size_array[]" class="size_array" value="{{$List->size_array}}" id="size_array{{$no}}" />
                                                <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="{{$List->size_qty_array}}" id="size_qty_array{{$no}}" />
                                            </td>
                                        </tr>
                                        @php $no++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                   </div> 
                </div> 
                <div class="col-md-12">
                  <h4><b>Fabric</b></h4> 
               </div>
               <div class="table-responsive">
                  <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                     <thead>
                        <tr>
                           <th>Sr No</th>
                           <th>Item</th>
                           <th>Unit</th>
                           <th>Qty</th> 
                           <th>Add/Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                         @php 
                            $no1 = 1;
                            $ItemList1 =  DB::table('item_master')->where('cat_id','=', 1)->where('delflag','=', '0')->get();
                         @endphp
                         @foreach($SampleIndentFabricList as $fabrics)
                         @php
                         
                                $trimsOutward = DB::SELECT("SELECT ifnull(SUM(item_qty),0) as qty FROM trimsOutwardDetail WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$fabrics->fabric_item_code);
                                $fabricOutward = DB::SELECT("SELECT ifnull(SUM(meter),0) as meter FROM fabric_outward_details WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$fabrics->fabric_item_code);
                                
                                $item_qty1 = isset($trimsOutward[0]->qty) ? $trimsOutward[0]->qty : 0;
                                $meter1 = isset($fabricOutward[0]->meter) ? $fabricOutward[0]->meter : 0;
                                $isDisabled1 = '';
                                $min1 = 0;
                                
                                if($item_qty1 > 0 || $meter1 > 0)
                                {
                                    $isDisabled1 = 'disabled';
                                    $min1 = $item_qty1 ? $item_qty1 : $meter1;
                                }
                         

                                $unitData = DB::SELECT("SELECT unit_name FROM item_master INNER JOIN unit_master ON unit_master.unit_id = item_master.unit_id WHERE item_code=".$fabrics->fabric_item_code);
                                $unit_name = isset($unitData[0]->unit_name) ? $unitData[0]->unit_name : 0;
                                
                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="{{$no1++}}" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="fabric_item_code[]" class="form-select select2" id="fabric_item_code" onchange="CheckExist(this);GetItemUnits(this);"  style="width:250px;" {{$isDisabled1}}>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList1 as  $row)
                                        <option value="{{ $row->item_code  }}" {{ $row->item_code == $fabrics->fabric_item_code ? 'selected="selected"' : '' }} >{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units">{{$unit_name}}</td>
                           <td><input type="number" step="any"  name="fabric_qty[]" class="QTY" value="{{ $fabrics->fabric_qty }}" min="{{$min1}}" id="fabric_qty" style="width:150px; height:30px;" /></td> 
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left" > 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;"  {{$isDisabled1}}>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div class="col-md-12">
                  <h4><b>Sewing Trims</b></h4>
               </div>
               <div class="table-responsive">
                  <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                     <thead>
                        <tr>
                           <th>Sr No</th>
                           <th>Item</th>
                           <th>Unit</th>
                           <th>Qty</th> 
                           <th>Add/Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                         @php 
                            $no2 = 1;
                            $ItemList2 =  DB::table('item_master')->where('cat_id','=', 2)->where('delflag','=', '0')->get();
                         @endphp
                         @foreach($SampleIndentSewingList as $sewing)
                         @php
                         
                                $trimsOutward1 = DB::SELECT("SELECT ifnull(SUM(item_qty),0) as qty FROM trimsOutwardDetail WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$sewing->sewing_trims_item_code);
                                $fabricOutward1 = DB::SELECT("SELECT ifnull(SUM(meter),0) as meter FROM fabric_outward_details WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$sewing->sewing_trims_item_code);
                                
                                $item_qty2 = isset($trimsOutward1[0]->qty) ? $trimsOutward1[0]->qty : 0;
                                $meter2 = isset($fabricOutward1[0]->meter) ? $fabricOutward1[0]->meter : 0;
                                $isDisabled2 = '';
                                $min2 = 0;
                                if($item_qty2 > 0 || $meter2 > 0)
                                {
                                    $isDisabled2 = 'disabled';
                                    $min2 = $item_qty2 ? $item_qty2 : $meter2;
                                }
                         

                                $unitData = DB::SELECT("SELECT unit_name FROM item_master INNER JOIN unit_master ON unit_master.unit_id = item_master.unit_id WHERE item_code=".$sewing->sewing_trims_item_code);
                                $unit_name = isset($unitData[0]->unit_name) ? $unitData[0]->unit_name : 0;

                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="{{$no2++}}" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="sewing_trims_item_code[]" class="form-select select2" id="sewing_trims_item_code" onchange="CheckExist(this);GetItemUnits(this);" style="width:250px;" {{$isDisabled2}}>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList2 as  $row)
                                        <option value="{{ $row->item_code }}" {{ $row->item_code == $sewing->sewing_trims_item_code ? 'selected="selected"' : '' }} >{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units">{{$unit_name}}</td>
                           <td>
                              <input type="number" step="any" name="sewing_trims_qty[]" class="QTY" value="{{$sewing->sewing_trims_qty}}" min="{{$min2}}" id="sewing_trims_qty" style="width:150px; height:30px;"  /> 
                           </td> 
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;"  {{$isDisabled2}}>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div class="col-md-12">
                  <h4><b>Packing Trims</b></h4>
               </div>
               <div class="table-responsive">
                  <table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
                     <thead>
                        <tr>
                           <th>Sr No</th>
                           <th>Item</th>
                           <th>Unit</th>
                           <th>Qty</th> 
                           <th>Add/Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                         @php 
                            $no3 = 1;
                            $ItemList3 =  DB::table('item_master')->where('cat_id','=', 3)->where('delflag','=', '0')->get();
                         @endphp
                         @foreach($SampleIndentPackingList as $packing)
                         @php
                         
                                $trimsOutward2 = DB::SELECT("SELECT ifnull(SUM(item_qty),0) as qty FROM trimsOutwardDetail WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$packing->packing_trims_item_code);
                                $fabricOutward2 = DB::SELECT("SELECT ifnull(SUM(meter),0) as meter FROM fabric_outward_details WHERE sample_indent_code='".$SampleIndent->sample_indent_code."' AND item_code=".$packing->packing_trims_item_code);
                                
                                $item_qty3 = isset($trimsOutward2[0]->qty) ? $trimsOutward2[0]->qty : 0;
                                $meter3 = isset($fabricOutward2[0]->meter) ? $fabricOutward2[0]->meter : 0;
                                
                                $isDisabled3 = '';
                                $min3 = 0;
                                
                                if($item_qty3 > 0 || $meter3 > 0)
                                {
                                    $isDisabled3 = 'disabled';
                                    $min3 = $item_qty3 ? $item_qty3 : $meter3;
                                } 
                         
                                $unitData = DB::SELECT("SELECT unit_name FROM item_master INNER JOIN unit_master ON unit_master.unit_id = item_master.unit_id WHERE item_code=".$packing->packing_trims_item_code);
                                $unit_name = isset($unitData[0]->unit_name) ? $unitData[0]->unit_name : 0;
        
                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="{{$no3++}}" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="packing_trims_item_code[]" class="form-select select2" id="packing_trims_item_code" onchange="CheckExist(this);GetItemUnits(this);"  style="width:250px;" {{$isDisabled3}}>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList3 as  $row)
                                        <option value="{{ $row->item_code  }}" {{ $row->item_code == $packing->packing_trims_item_code ? 'selected="selected"' : '' }} >{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units">{{$unit_name}}</td>
                           <td>
                              <input type="number" step="any" name="packing_trims_qty[]" class="QTY" value="{{$packing->packing_trims_qty}}"  min="{{$min3}}" id="packing_trims_qty" style="width:150px; height:30px;"  /> 
                           </td>
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" {{$isDisabled3}}>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               </br>
               </br>
               <!-- end row -->
               <div class="row">
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="remark" class="form-control" id="remark"  value="{{$SampleIndent->remark}}" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Update</button>
                     <a href="{{ Route('SampleIndent.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script> 
   $(document).ready(function() {
      $('#frmData').submit(function() {
          $('#Submit').prop('disabled', true);
      }); 
      
      if ($("#SampleIndent > tr").length === 0)
      {
          SizeSampleIndentList($("#sz_code").val());
          $("#order_qty_label").addClass("hide");
      }
      else
      {
          $("#order_qty_label").removeClass("hide");
      }
   });
   
      
   
   function GetItemUnits(row)
   {
        var item_code = $(row).val();
        $.ajax({
             dataType: "json",
             contentType: "application/json; charset=utf-8",
             url: "{{ route('GetItemUnits') }}",
             data:{'item_code':item_code},
             success: function(data)
             {
                  $(row).parent().parent('tr').find('td.units').html(data.unit_name); 
             }
        });
   }
   
    function CheckExist(row) 
    {
        var itemCode = $(row).val().trim();
        var duplicateCount = 0;
    
        $(row).closest('table').find('select').each(function() {
            if ($(this).val().trim() === itemCode) {
                duplicateCount++;
            }
        });
    
        if (duplicateCount > 1) 
        { 
            alert("Item code already exists!");
            $(row).select2('destroy');
            $(row).val('');  
            $(row).select2();
        }
    }
    
    function CheckColorExist(row) 
    {
        var itemCode = $(row).val().trim();
        var duplicateCount = 0;
    
        $(row).closest('table').find('input').each(function() {
            if ($(this).val().trim() === itemCode) {
                duplicateCount++;
            }
        });
    
        if (duplicateCount > 1) 
        { 
            alert("Color already exists!"); 
            $(row).val('');   
        }
    }
    
   function EnableFeilds()
   {
       $('select').removeAttr('disabled'); 
   }
   
    function GetSubStyleList()
    {
       var mainstyle_id = $("#mainstyle_id").val(); 
       $("#substyle_id").select2('destroy');
       $.ajax({
         dataType: "json",
         contentType: "application/json; charset=utf-8",
         url: "{{ route('SubStyleList') }}",
         data:{'mainstyle_id':mainstyle_id},
         success: function(data)
         {
              $("#substyle_id").html(data.html); 
              $("#substyle_id").select2();
         }
     });
    }
   
    // function GetSizeQtyArray(row)
    // {
    //     var size_array = [];
    //     var total_qty = 0;
    //     $(row).parent().parent('tr').find('.size_id').each(function()
    //     {
    //         var size_id = $(this).val(); 
    //         size_array.push(size_id); 
    //         total_qty += parseFloat(size_id);
    //     });
    
    //     var unique_size_array = size_array.join(','); 
    //     $(row).parent().parent('tr').find('input[name="size_qty_array[]"]').val(unique_size_array); 
    //     $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
    // }

   
    function GetSizeQtyArray(row)
    {
        var size_array = [];
        var total_qty = 0;
        $(row).parent().parent('tr').find('.size_id').each(function()
        {
            var size_id = $(this).val(); 
            size_array.push(size_id); 
            total_qty += parseFloat(size_id);
        });
    
        var unique_size_array = size_array.join(','); 
        $(row).parent().parent('tr').find('input[name="size_qty_array[]"]').val(unique_size_array); 
        var min =  $(row).attr('min');
        
        if(parseInt(total_qty) < parseInt(min))
        {
            alert("Total qty should not be less...!");
            var old_qty = $(row).parent().find('.old_size_id').val();
            $(row).val(old_qty);
            
            var size_array = [];
            var total_qty = 0;
            $(row).parent().parent('tr').find('.size_id').each(function()
            {
                var size_id = $(this).val(); 
                size_array.push(size_id); 
                total_qty += parseFloat(size_id);
            });
        
            var unique_size_array = size_array.join(','); 
            $(row).parent().parent('tr').find('input[name="size_qty_array[]"]').val(unique_size_array); 
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(parseInt(total_qty)); 
        
        }
        else
        {
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
        }
      
    }
    
   
   function SizeSampleIndentList(str)
   {
     $.ajax({
         dataType: "json",
         contentType: "application/json; charset=utf-8",
         url: "{{ route('SizeSampleIndentList') }}",
         data:{'sz_code':str},
         success: function(data)
         {
              $("#order_qty").html(data.html1); 
         }
     });
   }
   
   function GetDepartmentType()
   {
      
     var sample_type_id = $("#sample_type_id").val();
     $.ajax(
     {
         type:"GET", 
         url: "{{ route('GetDepartmentType') }}",
         data:{sample_type_id:sample_type_id},
         success:function(res)
         { 
             console.log(res.dept_type_id);
             $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
         }
     });
   }
   
   
   function deleteRow(btn) 
   { 
      var row = btn.parentNode.parentNode;
      row.parentNode.removeChild(row);  
      recalcId1();
      recalcId2();
      recalcId3();
      recalcId4();
   }
   
   function recalcId1()
   {
         $.each($("#footable_1 tr"),function (i,el)
         {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
         });
   }
   
   function recalcId2()
   {
         $.each($("#footable_2 tr"),function (i,el)
         {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
         });
   }
   
   function recalcId3()
   {
         $.each($("#footable_3 tr"),function (i,el)
         {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
         });
   }
   
   function recalcId4()
   {
         $.each($("#footable_4 tr"),function (i,el)
         {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
         });
   }
   
    function addNewRow(row) 
    {
        var table = $(row).closest('table');
        var lastRow = table.find('tr:last');
    
        // Destroy select2 instances before cloning
        lastRow.find('select').each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });
    
        // Clone the row and append it to the table
        var clonedRow = lastRow.clone();
        table.append(clonedRow);
    
        clonedRow.find('select').val('');
        clonedRow.find('select').removeAttr('disabled'); 
        clonedRow.find('.btn-danger').removeAttr('disabled'); 
        clonedRow.find('input[name="sewing_trims_qty[]"]').removeAttr('min'); 
        clonedRow.find('input').not('input[type="button"]').val(0);
        // Reinitialize select2 for the cloned row
        clonedRow.find('select').each(function() {
            $(this).select2();  // Reinitialize select2
        });
        lastRow.find('select').each(function() {
            $(this).select2();  // Reinitialize select2
        });
        
        // Recalculate IDs or any other attributes as needed
        recalcId1();
        recalcId2();
        recalcId3();
        recalcId4();
    }
</script>
@endsection