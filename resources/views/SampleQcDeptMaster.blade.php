@extends('layouts.master') 
@section('content')
<style>
     #overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        display: none; /* Hidden by default */
        z-index: 1000; /* Ensure it's on top */
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample QC Department</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample QC Department</li>
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
            <h4 class="card-title mb-4">Sample QC Department</h4>
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
            <form action="{{route('SampleQcDept.store')}}" method="POST" id="frmData"  enctype="multipart/form-data"> 
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_qc_dept_date" class="form-label">Date</label>
                        <input type="date" name="sample_qc_dept_date" class="form-control" id="sample_qc_dept_date" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleIndentMasterData();" required >
                           <option value="0">--Select--</option>
                           @foreach($SampleIndentMasterList as  $row) 
                                <option value="{{ $row->sample_indent_code }}">{{ $row->sample_indent_code }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" disabled>
                           <option value="0">--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select select2" id="brand_id" disabled>
                           <option value="0">--Select--</option> 
                           @foreach($BrandList as  $row) 
                                <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" class="form-select" id="mainstyle_id" disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($MainStylelist as  $row) 
                                <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select" id="substyle_id" disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($SubStylelist as  $row) 
                                <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" readonly>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="" readonly> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" disabled >
                           <option value="0">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}">{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select " id="dept_type_id" disabled>
                           <option value="0">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}">{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select " id="sz_code" disabled>
                           <option value="0">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="actual_etd" class="form-label">Actual ETD (Complete Date)</label>
                        <input type="date" name="actual_etd" class="form-control" id="actual_etd" value="" required> 
                     </div>
                  </div>
               </div>
               <div class="table-wrap">
                   <div class="col-md-12">
                      <h4><b>Order Qty</b></h4>
                   </div>
                  <div class="table-responsive" id="order_qty">  
                  </div>
               </div> 
               <div class="table-wrap">
                   <div class="col-md-12">
                      <h4><b>Stitching Qty</b></h4>
                   </div>
                  <div class="table-responsive" id="stitching_qty">  
                  </div>
               </div> 
               <div class="table-responsive">
                  <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                     <thead>
                        <tr>
                           <th>Attachment Name</th>
                           <th>Attachment</th>
                           <th>Add/Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr> 
                           <td> 
                               <input type="text" name="attachment_name[]" class="form-control" id="attachment_name" style="width:300px;"/>
                           </td>
                           <td> 
                               <input type="file" name="upload_attachment[]" id="upload_attachment" style="width:200px;"/>
                           </td>
                           <td> 
                              <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                              <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" style="margin-left:5px;" >
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div>
                  <button type="submit" class="btn btn-success w-md" id="Submit"  onclick="EnableFeilds();" >Save</button>
                  <a href="{{ Route('SampleQcDept.index') }}" class="btn btn-warning w-md">Cancel</a>
               </div>
            </form>
          <div id="overlay"></div>
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
      OrderQty();
   });
   
   function OrderQty()
   {
      var total = 0;  
      $('input[name="order_qty[]"]').each(function()
      {
             total += parseFloat($(this).val());
      });  
      
      if(parseFloat(total) == 0)
      {
           $("#Submit").attr("disabled", true);  
      }
      else
      {
          
           $("#Submit").attr("disabled", false);  
      }
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
    
    function EnableFeilds()
    {
       $('select,input').removeAttr('disabled');
    }
    
    function GetSampleIndentMasterData()
    {
        var sample_indent_code = $('#sample_indent_code').val();
        $.ajax({
             dataType: "json",
             contentType: "application/json; charset=utf-8",
             url: "{{ route('GetSampleIndentMasterQCData') }}",
             data:{'sample_indent_code':sample_indent_code},
             success: function(data)
             { 
                var res = data.MasterData;
                $("#order_qty").html(data.DetailHtml);
                $("#stitching_qty").html(data.StitchingHtml);
                $("#style_description").val(res.style_description);
                $("#sam").val(res.sam); 
                $('#Ac_code option[value="' + res.Ac_code + '"]').prop('selected', true).change();
                $('#brand_id option[value="' + res.brand_id + '"]').prop('selected', true).change();
                $('#mainstyle_id option[value="' + res.mainstyle_id + '"]').prop('selected', true).change();
                $('#substyle_id option[value="' + res.substyle_id + '"]').prop('selected', true).change();
                $('#sample_type_id option[value="' + res.sample_type_id + '"]').prop('selected', true).change();
                $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
                $('#sz_code option[value="' + res.sz_code + '"]').prop('selected', true).change(); 
                if(data.actual_etd != '')
                {
                     $("#actual_etd").val(data.actual_etd);
                     $("#actual_etd").attr('readonly', true);
                }
               
             }
        });
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
        var max =  $(row).parent().parent('tr').find('input[name="order_qty[]"]').attr('max');
        if(parseInt(total_qty) > parseInt(max))
        {
            alert("Total qty exceeded...!");
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(parseInt(total_qty) - parseInt($(row).val())); 
            $(row).val(0);
            
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
        
        }
        else
        {
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
        }
      
        OrderQty();
    }
    
     function deleteRow(btn) 
     {
        var row = btn.parentNode.parentNode;
        var table = row.parentNode;
        
        if (table.rows.length > 1) 
        {
            table.removeChild(row);  
            recalcId1(); 
        }
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
        var lastRow = table.find('tr:last').clone();  
        
        lastRow.find('input[type="text"]').val('');  
        lastRow.find('input[type="file"]').val(''); 
    
        table.append(lastRow); 
        recalcId1();
    }

</script>
@endsection