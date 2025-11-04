@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample Customer Feedback</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample Customer Feedback</li>
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
            <h4 class="card-title mb-4">Sample Customer Feedback</h4>
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
            <form action="{{route('SampleCustomerFeedback.store')}}" method="POST" id="frmData" enctype="multipart/form-data"> 
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_cust_feed_date" class="form-label">Date</label>
                        <input type="date" name="sample_cust_feed_date" class="form-control" id="sample_cust_feed_date" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="GetSINCodeList();">
                           <option value="0">--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleIndentMasterData();" >
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
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" disabled>
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
               <div class="row">
                   <div class="col-md-8">
                       <div class="table-responsive" id="BOMTbl">
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="table-responsive" id="AttachmentTbl">
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cust_feed_status_id" class="form-label">Customer Feedback</label>
                        <select name="cust_feed_status_id" class="form-select" id="cust_feed_status_id" required>
                           <option value="0">-- Select --</option>
                           @foreach($CustFeedList as  $row)
                                <option value="{{ $row->cust_feed_status_id  }}">{{ $row->cust_feed_status_name }}</option>
                           @endforeach
                        </select>
                     </div>
                   </div>
                      <div class="col-md-6">
                         <div class="mb-3">
                            <label for="cust_comments" class="form-label">Customer Comments</label>
                            <input type="text" name="cust_comments" class="form-control" id="cust_comments" value="" >
                            <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                         </div>
                      </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-success w-md" id="Submit"  onclick="EnableFeilds();">Save</button>
                  <a href="{{ Route('SampleCustomerFeedback.index') }}" class="btn btn-warning w-md">Cancel</a>
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
   
    function EnableFeilds()
    {
       $('select,input').removeAttr('disabled');
    }
    
   function GetSINCodeList()
   {
       var  Ac_code = $("#Ac_code").val();
       $.ajax({
         dataType: "json",
         contentType: "application/json; charset=utf-8",
         url: "{{ route('GetSINCodeList') }}",
         data:{'Ac_code':Ac_code},
         success: function(data)
         {
            $("#sample_indent_code").html(data.html);
         }
       });
   }
   
    function GetSampleIndentMasterData()
    {
        var sample_indent_code = $('#sample_indent_code').val();
        $("#Ac_code").attr('disabled', true);
        $.ajax({
             dataType: "json",
             contentType: "application/json; charset=utf-8",
             url: "{{ route('GetSampleIndentMasterCustomerData') }}",
             data:{'sample_indent_code':sample_indent_code},
             success: function(data)
             { 
                var res = data.MasterData;
                $("#order_qty").html(data.DetailHtml);
                $("#stitching_qty").html(data.StitchingHtml);
                $("#AttachmentTbl").html(data.AttachmentHtml);
                $("#BOMTbl").html(data.BOMHtml);
                $("#style_description").val(res.style_description);
                $("#sam").val(res.sam);  
                $('#mainstyle_id option[value="' + res.mainstyle_id + '"]').prop('selected', true).change();
                $('#substyle_id option[value="' + res.substyle_id + '"]').prop('selected', true).change();
                $('#sample_type_id option[value="' + res.sample_type_id + '"]').prop('selected', true).change();
                $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
                $('#sz_code option[value="' + res.sz_code + '"]').prop('selected', true).change(); 
                
                let colorGroups = {};

                // Group rows by color
                $('#footable_2 tbody tr').each(function() {
                    const $row = $(this);
                    const color = $row.find('.color').val();
            
                    if (!colorGroups[color]) {
                        colorGroups[color] = [];
                    }
            
                    colorGroups[color].push($row);
                });
            
                // Merge rows and sum quantities
                $.each(colorGroups, function(color, rows) {
                    if (rows.length > 1) {
                        let $firstRow = rows[0];
                        let sizeQtySums = [];
            
                        // Initialize the sizeQtySums array with zeros
                        $firstRow.find('.size_id').each(function(index) {
                            sizeQtySums[index] = 0;
                        });
            
                        let totalQty = 0;
            
                        // Iterate over each row of the same color
                        rows.forEach(function($row, index) {
                            $row.find('.size_id').each(function(i) {
                                sizeQtySums[i] += parseInt($(this).val()) || 0;
                            });
            
                            totalQty += parseInt($row.find('.QTY').val()) || 0;
            
                            if (index !== 0) {
                                $row.remove(); // Remove all rows except the first one
                            }
                        });
            
                        // Set the summed quantities in the first row
                        $firstRow.find('.size_id').each(function(index) {
                            $(this).val(sizeQtySums[index]);
                        });
            
                        $firstRow.find('.QTY').val(totalQty);
                    }
                });
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
        $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
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
      var lastRow = table.find('tr:last').clone();  
      table.append(lastRow); 
      recalcId1();
   }
</script>
@endsection