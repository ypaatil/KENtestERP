@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cutting Entry Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Cutting Entry Master</li>
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
         <h4 class="card-title mb-4">Cutting Entry Master</h4>
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
         <form action="{{route('CuttingEntry.store')}}" method="POST" id="frmCuttingEntry">
         <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
            @csrf 
            <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-cuttingEntryDate" class="form-label">Date</label>
                      <input type="date" name="cuttingEntryDate" class="form-control" id="formrow-cuttingEntryDate-input" value="{{date('Y-m-d')}}" required>  
                  </div>
               </div>  
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-sales_order_no" class="form-label">KDPL</label>
                     <select name="sales_order_no" class="form-select select2" id="sales_order_no" onchange="GetBuyerPurchaseData(this.value);" required>
                        <option value="">--KDPL--</option>
                        @foreach($SalesOrderList as  $row)
                        <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-buyer_name" class="form-label">Buyer Name</label>
                      <input type="text" name="Ac_name" class="form-control" id="Ac_name" value="" readonly>  
                  </div>
               </div> 
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-brand" class="form-label">Buyer Brand</label>
                      <input type="text" name="brand_name" class="form-control" id="brand_name" value="" readonly>  
                  </div>
               </div>  
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-main_style_id" class="form-label">Main Style</label>
                     <select name="main_style_id" class="form-select select2" id="main_style_id"  disabled>
                        <option value="">--Main Style--</option>
                        @foreach($MainStyleList as  $row)
                        <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>   
               <div class="col-md-3">
                  <div class="mb-3">
                      <label for="formrow-fg_name" class="form-label">Style Name</label>
                      <input type="hidden" name="fg_id" class="form-control" id="fg_id" value=""> 
                      <input type="text" name="fg_name" class="form-control" id="fg_name" value="" readonly> 
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-style_no" class="form-label">Style No.</label>
                      <input type="text" name="style_no" class="form-control" id="style_no" value="" readonly>  
                  </div>
               </div> 
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-sam" class="form-label">SAM</label>
                      <input type="text" name="sam" class="form-control" id="sam" value="" readonly>  
                  </div>
               </div>  
               <div class="col-md-12 table-responsive">
                  <table id="opertionTbl" class="table table-bordered nowrap w-100">
                   <thead>
                       <tr>
                           <th>Sr No.</th> 
                           <th>Garment Color</th>
                           <th>Lot No</th>
                           <th>Bundle No</th>
                           <th>Bundle Track Code</th>
                           <th>Slip No.</th>
                           <th>Size</th>
                           <th>Cut Panel Qty</th> 
                           <th>Action</th>
                       </tr>
                   </thead>
                   <tbody>
                       @php
                            $cuttingData = DB::SELECT('select tr_no+1 as count FROM counter_number WHERE code="B" AND type="BundleTrackCode"');
                            $sr_no = isset($cuttingData[0]->count) ? $cuttingData[0]->count : 0;
                       @endphp
                       <tr>
                            <td> 
                                 <input type="hidden" name="tr_no[]" class="form-control"  value="{{$sr_no}}">  
                                 <input type="text" name="srno" class="form-control" id="formrow-srno-input" value="1" style="width:60px;">  
                            </td> 
                            <td> 
                                 <select name="color_id[]" class="form-select select2"  style="width:230px;" onchange="checkDuplicateBundleNo(this);" required>
                                    <option value="">--Select--</option>  
                                 </select>
                            </td>
                            <td>  
                                <input type="text" name="lotNo[]" class="form-control" value="" style="width:100px;" required>  
                            </td>
                            <td>
                                 <input type="number" step="any" name="bundleNo[]" class="form-control" value="" style="width:100px;" onchange="checkDuplicateBundleNo(this);" required>  
                            </td>
                            <td>
                                 <input type="text" name="bundle_track_code[]" class="form-control" value="B{{$sr_no}}" style="width:100px;" readonly >  
                            </td>
                            <td>
                                 <input type="text" name="slipNo[]" class="form-control" value="0" style="width:100px;" >  
                            </td>
                            <td> 
                                  <select name="size[]" class="form-select"  style="width:100px;" required>
                                    <option value="0">--Select--</option>  
                                 </select>
                            </td>
                            <td>
                                 <input type="text" name="cut_panel_issue_qty[]" class="form-control" value="" style="width:100px;" required onchange="calculateCutQty();">  
                            </td>
                            <td nowrap>
                                <a href="javascript:void(0);" class="btn btn-warning" onclick="AddNewRow(this);">+</a>
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a>
                            </td>
                       </tr>
                   </tbody>
               </table>
            </div> 
               <div class="col-md-3">
                  <div class="mt-3 mb-3">
                     <label for="total_cut_qty-sam" class="form-label">Total Cut Qty.</label>
                      <input type="text" name="total_cut_qty" class="form-control" id="total_cut_qty" value="" readonly>  
                  </div>
               </div>
            </div>
            <div class="row">
             <div class="col-sm-6">
    <div class="mb-3">
        <label for="jpart_id" class="form-label">For Which Job Parts Do You want Slip Print..?</label>
       <select name="jpart_id[]" class="form-select" id="jpart_id" size="10" required multiple>
        <option value="0">--All Part--</option>
        @foreach($JobPartList as  $row)
        {
            <option value="{{ $row->jpart_id }}">{{ $row->jpart_name }} ({{ $row->jpart_description }})</option>
        }
        @endforeach
        </select>
    </div>
</div>   
                
                
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md" onclick="removeDisabled();" id="submitBtn">Submit</button>
                     <a href="{{ Route('CuttingEntry.index') }}"  class="btn btn-warning w-md">Cancel</a>
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
<input type="hidden" id="sr_no" value="{{isset($sr_no) ? $sr_no : 1}}">
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<!-- end row -->
<script>

  function calculateCutQty()
  {
     var total_qty = 0;
     $('input[name="cut_panel_issue_qty[]"]').each(function(){
         total_qty += parseFloat($(this).val());
     });
     
     $("#total_cut_qty").val(total_qty);
  }
    $(document).ready(function() {
        $('#frmCuttingEntry').submit(function() {
            $('#submitBtn').prop('disabled', true);
        });
    });
   function checkDuplicateBundleNo(row)
   {
        var sales_order_no = $("#sales_order_no").val();
        var color_id = $(row).closest('tr').find('select[name="color_id[]"]').val();
        var bundleNo = $(row).closest('tr').find('input[name="bundleNo[]"]').val();
         
        var bundle_count = 0;
        

        
        $("#opertionTbl tr").each(function(index)
        {
         
               const bundleNoThis = $(this).find("input[name='bundleNo[]']").val();
              const color_idThis = $(this).find("select[name='color_id[]']").val();
         
            
          if(bundleNoThis == bundleNo && color_idThis==color_id)
          {
              bundle_count++;
          }
          if(bundle_count >= 2)
          { 
              alert("Already Exist Bundle No. And color.!");
             
           $(row).closest('tr').find('td input[name="bundleNo[]"]').val("");   
             
          }
        }); 
            
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('checkDuplicateBundleNo') }}",
          data:{'sales_order_no':sales_order_no,'color_id':color_id,'bundleNo':bundleNo},
          success: function(data)
          { 
              if(data.total_count > 0)
              {
                    alert("Already Exist Bundle No. And color.!");
                    $(row).val("");
              }
          }
        });
   }
   
   function removeDisabled()
   {
       $('select').removeAttr('disabled');
   }
   function GetBuyerPurchaseData(sales_order_no)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetBuyerPurchaseData') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          { 
               $('#main_style_id').val(data.main_style_id).trigger('change'); 
               $('#fg_id').val(data.fg_id);
               $('#fg_name').val(data.fg_name);
               $('#Ac_name').val(data.Ac_name);
               $('#style_no').val(data.style_no);
               $('#sam').val(data.sam);
               $('#brand_name').val(data.brand_name);
               $('select[name="size[]"]').html(data.sizehtml); 
               $('select[name="color_id[]"]').html(data.colorHtml); 
              // GetOperationList(data.main_style_id);
               GetPartList(data.fg_id);
               
          }
        });
   }
//   function GetOperationList(main_style_id)
//   {
//         var sales_order_no = $('#sales_order_no').val();
//         $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GetDailyProductionOperationList') }}",
//           data:{'main_style_id':main_style_id,'sales_order_no':sales_order_no},
//           success: function(data)
//           { 
//               $('select[name="operationNameId[]"]').html(data.html);
//               $('select[name="color_id[]"]').html(data.colorHtml);
//           }
//         });
//   }
   
   function GetPartList(fg_id)
   { 
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetPartList') }}",
          data:{'fg_id':fg_id},
          success: function(data)
          { 
               $('select[name="cut_part_id[]"]').html(data.html);
          }
        });
   }
//   function GetEmployeeList()
//   {
        
//         $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GetEmpList') }}", 
//           success: function(data)
//           { 
//               $('select[name="employeeCode[]"]').html(data.html);
//           }
//         });
//   }
   
   function AddNewRow(row)
   { 
        var sr_no = parseInt($("#sr_no").val()) + parseInt(1);
        $('.select2').select2('destroy'); 
        var tr = $(row).closest('tr'); 
        var clone = tr.clone();
        tr.after(clone);
        recalcIdcone();
        clone.find('select[name="color_id[]"]').val(tr.find('select[name="color_id[]"]').val());
         clone.find('select[name="size[]"]').val(tr.find('select[name="size[]"]').val());
        $(clone).find('td input[name="bundle_track_code[]"]').val('B'+sr_no);
        
        
            $(clone).find('td input[name="bundleNo[]"]').val(function(index, currentValue) {
            var newValue = parseInt($(row).closest('tr').find('td input[name="bundleNo[]"]').val(), 10) + 1;
            return newValue;
            });
        
        
        $(clone).find('td input[name="cut_panel_issue_qty[]"]').val('');
    
        $('.select2').select2(); 
        $("#sr_no").val(sr_no);
   } 
   
   function removeRow(row)
   {  
      $(row).parents('tr').remove(); 
      var sr_no = parseInt($("#sr_no").val()) - parseInt(1); 
      $("#sr_no").val(sr_no);
      
      calculateCutQty();
   }
   
   function recalcIdcone()
   {
       $.each($("#opertionTbl tr"),function (i,el)
       {
             $(this).find("td:first input").val(i);  
       })
   }
   
   
   
</script>

@endsection