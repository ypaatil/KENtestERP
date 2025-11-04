@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample Indent</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample Indent</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sample</h4>
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
            <form action="{{route('SampleIndent.store')}}" method="POST" id="frmData"  enctype="multipart/form-data">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'SampleIndent' ?>" /> 
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_indent_date" class="form-label">Date</label>
                        <input type="date" name="sample_indent_date" class="form-control" id="sample_indent_date" value="{{date('Y-m-d')}}" required> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="GetBrandList();" required>
                           <option>--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select select2" id="brand_id" required>
                           <option>--Select--</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" class="form-select select2" id="mainstyle_id" onchange="GetSubStyleList();" required>
                           <option value="">--- Select ---</option>
                           @foreach($MainStylelist as  $row) 
                                <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" required>
                           <option value="">--- Select ---</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="" required> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" required onchange="GetDepartmentType();" >
                           <option value="">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}">{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select" id="dept_type_id" disabled>
                           <option value="">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}">{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select  select2" id="sz_code" onchange="SizeSampleIndentList(this.value);" required>
                           <option value="">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_required_date" class="form-label">Sample Required Date</label>
                        <input type="date" name="sample_required_date" class="form-control" id="sample_required_date" value="" required> 
                     </div>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive" id="order_qty">  
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive" id="stithing_qty">  
                  </div>
               </div>
               <div class="col-md-12">
                  <h4><b>Fabric</h4>
                  </b>
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
                             $ItemList1 =  DB::table('item_master')->where('cat_id','=', 1)->where('delflag','=', '0')->get();
                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="1" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="fabric_item_code[]" class="form-select select2" id="fabric_item_code" onchange="CheckExist(this);GetItemUnits(this);"  style="width:300px;" required>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList1 as  $row)
                                        <option value="{{ $row->item_code  }}">{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units"></td>
                           <td><input type="number" step="any"  name="fabric_qty[]" class="QTY" value="0" id="fabric_qty" style="width:150px; height:30px;" required /></td> 
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="col-md-12">
                  <h4><b>Sewing Trims</h4>
                  </b>
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
                             $ItemList2 =  DB::table('item_master')->where('cat_id','=', 2)->where('delflag','=', '0')->get();
                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="1" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="sewing_trims_item_code[]" class="form-select select2" id="sewing_trims_item_code" onchange="CheckExist(this);GetItemUnits(this);" style="width:300px;" required>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList2 as  $row)
                                        <option value="{{ $row->item_code }}">{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units"></td>
                           <td>
                              <input type="number" step="any" name="sewing_trims_qty[]" class="QTY" value="0" id="sewing_trims_qty" style="width:150px; height:30px;" required /> 
                           </td>  
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
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
                             $ItemList3 =  DB::table('item_master')->where('cat_id','=', 3)->where('delflag','=', '0')->get();
                         @endphp
                        <tr>
                           <td><input type="text" name="id[]" value="1" id="id0" style="width:50px;"/></td>
                           <td>
                                <select name="packing_trims_item_code[]" class="form-select select2" id="packing_trims_item_code"  onchange="CheckExist(this);GetItemUnits(this);"  style="width:300px;" required>
                                   <option value="">-- Select --</option>
                                   @foreach($ItemList3 as  $row)
                                        <option value="{{ $row->item_code  }}">{{ $row->item_name }} ({{ $row->item_code  }})</option>
                                   @endforeach
                                </select>
                           </td>
                           <td class="units"></td>
                           <td>
                              <input type="number" step="any" name="packing_trims_qty[]" class="QTY" value="0" id="packing_trims_qty" style="width:150px; height:30px;" required /> 
                           </td>  
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <br/>
               <div class="row"> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="narration" class="form-label">Narration / Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark"  >
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-success w-md" id="Submit" onclick="EnableFeilds();" >Save</button>
                  <a href="{{ Route('SampleIndent.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script> 
<script> 
   $(document).ready(function() {
      $('#frmData').submit(function() {
          $('#Submit').prop('disabled', true);
      }); 
   });
    
   
   
   function GetBrandList()
   {
       var Ac_code = $("#Ac_code").val();  
       
       $.ajax({
         dataType: "json",
         contentType: "application/json; charset=utf-8",
         url: "{{ route('BrandList') }}",
         data:{'Ac_code':Ac_code},
         success: function(data)
         {
              $("#brand_id").html(data.html);  
         }
     });
   }
   
   
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
   
   function EnableFeilds()
   {
       $('select').removeAttr('disabled');
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
        $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
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