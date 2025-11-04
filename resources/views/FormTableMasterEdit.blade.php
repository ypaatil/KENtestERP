@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Form-Table Association</h4>
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
            @if(isset($FormTableMaster))
            <form action="{{ route('FormTableMaster.update',$FormTableMaster) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="form_id" class="form-label">Form Name</label>
                        <select name="form_id" class="form-select select2" id="form_id"   required>
                           <option>--Select--</option>
                           @foreach($FormList as  $row) 
                                <option value="{{ $row->form_code }}"
                                {{ $row->form_code == $FormTableMaster->form_id ? 'selected="selected"' : '' }}
                                >{{ $row->form_label }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="form_detail" class="form-label">Form Description</label>
                        <input type="text" name="form_detail" class="form-control" id="form_detail" value="{{$FormTableMaster->form_detail}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  
               </div>
               
                
               <div class="col-md-12">
                  <h4><b>Sewing Trims</b></h4>
               </div>
               <div class="table-responsive">
                  <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                     <thead> 
                        <tr>
                           <th>Sr No</th>
                           <th>Current Year DB</th>
                           <th>Backup DB</th>
                           <th>Table Name</th> 
                           <th>Primary Key Name</th> 
                           <th>Add/Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                         @php 
                            $no2 = 1;
                            
                         @endphp
                         @foreach($FormTableDetailList as $row)
                         @php
                         
                               

                         @endphp
                         <tr>
                           <td><input type="text" name="id[]" value="{{$no2}}" id="id0" style="width:50px;"/></td>
                           <td>
                                 <input type="text"    name="last_year_database_name[]"   value="{{$row->last_year_database_name}}" id="last_year_database_name" style="width:250px; height:30px;" readOnly required />
                           </td>
                           <td><input type="text"    name="new_year_database_name[]"   value="{{$row->new_year_database_name}}" id="new_year_database_name" style="width:250px; height:30px;" required readOnly/></td> 
                           <td><input type="text"    name="table_name[]"   value="{{$row->table_name}}" id="table_name" style="width:150px; height:30px;" required /></td> 
                           <td><input type="text"    name="p_key_name[]"   value="{{$row->p_key_name}}" id="p_key_name" style="width:150px; height:30px;" required /></td> 
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
              
              
                
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Update</button>
                     <a href="{{ Route('FormTableMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
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
       
        // clonedRow.find('input').not('input[type="button"]').val(0);
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