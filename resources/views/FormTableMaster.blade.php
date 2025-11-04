@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Form Table Associate </h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Form Table Associate</li>
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
            <h4 class="card-title mb-4">orm Table Associate</h4>
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
            <form action="{{route('FormTableMaster.store')}}" method="POST" id="frmData"  enctype="multipart/form-data">
              
               @csrf 
               <div class="row">
                  
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="form_id" class="form-label">Form Name</label>
                        <select name="form_id" class="form-select select2" id="form_id"   required>
                           <option>--Select--</option>
                           @foreach($FormList as  $row) 
                                <option value="{{ $row->form_code }}">{{ $row->form_label }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="form_detail" class="form-label">Form Description</label>
                        <input type="text" name="form_detail" class="form-control" id="form_detail" value="" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  
               </div>
 
               <div class="table-responsive">
                  <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
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
                        
                        <tr>
                           <td><input type="text" name="id[]" value="1" id="id0" style="width:50px;"/></td>
                           <td>
                                 <input type="text"    name="last_year_database_name[]"   value="kenerp_KenGlobalERP_2025_2026" id="last_year_database_name" style="width:250px; height:30px;" readOnly required />
                           </td>
                           
                           <td><input type="text"    name="new_year_database_name[]"   value="kenerp_CrossBackupKenGlobalERP2526" id="new_year_database_name" style="width:250px; height:30px;" required readOnly/></td> 
                           <td><input type="text"    name="table_name[]"   value="" id="table_name" style="width:150px; height:30px;" required /></td> 
                           <td><input type="text"    name="p_key_name[]"   value="" id="p_key_name" style="width:150px; height:30px;" required /></td> 
                           
                           <td>
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
                
               
                  <button type="submit" class="btn btn-success w-md" id="Submit"  >Save</button>
                  <a href="{{ Route('FormTableMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
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
   
    
     
   function deleteRow(btn) 
   { 
      var row = btn.parentNode.parentNode;
      row.parentNode.removeChild(row);  
      recalcId1();
      
   }
   
   function recalcId1()
   {
         $.each($("#footable_1 tr"),function (i,el)
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
        
    }

   
</script>
@endsection