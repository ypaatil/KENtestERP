@extends('layouts.master') 
@section('content')
<style>
    .form-popup-bg {
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      flex-direction: column;
      align-content: center;
      justify-content: center;
    }
    .form-popup-bg {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(94, 110, 141, 0.9);
      opacity: 0;
      visibility: hidden;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      transition: opacity 0.3s 0s, visibility 0s 0.3s;
      overflow-y: auto;
      z-index: 10000;
    }
    .form-popup-bg.is-visible {
      opacity: 1;
      visibility: visible;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
      transition: opacity 0.3s 0s, visibility 0s 0s;
    }
    .form-container {
        background-color: #011b3285;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 825px;
        margin-left: auto;
        margin-right: auto;
        position:relative;
        padding: 40px;
        color: #fff;
        height: fit-content;
        max-height: -webkit-fill-available;
    }
    .close-button {
      background:none;
      color: #fff;
      width: 40px;
      height: 40px;
      position: absolute;
      top: 0;
      right: 0;
      border: solid 1px #fff;
    }
    
    .form-popup-bg:before{
      content:'';
      background-color: #fff;
      opacity: .25;
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Outward - Cutting Department</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims Outward - Cutting Department</li>
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
            <form action="{{route('TrimsOutwardCuttingDepartment.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tocd_date" class="form-label">Date</label>
                        <input type="date" name="tocd_date" class="form-control" id="tocd_date" value="{{date('Y-m-d')}}" required>
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="tocd_code" class="form-control" id="tocd_code" value="{{ 'TOCD'.$row->tr_no }}"> 
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <!-- this is for dc no-->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dc_no" class="form-label">DC No</label>
                        <select name="dc_no" class="form-select select2" id="dc_no" onchange="GetTrimsOutwardData();">
                           <option value="">-- Select --</option>
                           @foreach($toutList as $row)
                                <option value="{{$row->trimOutCode}}">{{$row->trimOutCode}}({{$row->vpo_code}})</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <!-- this is for outward date-->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="outward_date" class="form-label">Outward Date</label>
                        <input type="date" name="outward_date" class="form-control" id="outward_date" value="{{date('Y-m-d')}}" readonly>
                        </div>
                  </div>
                  
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-select select2" id="vendorId" disabled>
                           <option value="">-- Select --</option>
                           @foreach($ledger_master as $row)
                                <option value="{{$row->ac_code}}">{{$row->ac_short_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cutting_po_no" class="form-label">Cutting PO No</label>
                        <input type="text" name="cutting_po_no" class="form-control" id="cutting_po_no" value="" readonly>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-select select2" id="mainstyle_id" disabled>
                           <option value="">-- Select --</option>
                           @foreach($main_style_master as $row)
                                <option value="{{$row->mainstyle_id}}">{{$row->mainstyle_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" disabled>
                           <option value="">-- Select --</option>
                           @foreach($sub_style_master as $row)
                                <option value="{{$row->substyle_id}}">{{$row->substyle_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-select select2" id="fg_id" disabled>
                           <option value="">-- Select --</option>
                           @foreach($fg_master as $row)
                                <option value="{{$row->fg_id}}">{{$row->fg_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" readonly>
                     </div>
                  </div>
                  <div id="Error" class="alert alert-success"></div>
               </div>
               <div class="row">
                 <h4><b></b></h4>
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_1" class="table table-bordered table-striped m-b-0  footable_1">
                           <thead>
                              <tr>
                                 <th>Sr No</th>  
                                 <th>Item Code</th> 
                                 <th>Item Name</th>  
                                 <th>Item Qty</th>  
                                 <th>Outward Qty</th>
                              </tr>
                           </thead>
                           <tbody></tbody> 
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_outward_meter" class="form-label">Total Item Qty</label>
                        <input type="number" step="any"  name="total_outward_meter" class="form-control" id="total_outward_meter" value="0" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_received_meter" class="form-label">Total Outward Qty</label>
                        <input type="number" step="any"  name="total_received_meter" class="form-control" id="total_received_meter" value="0" readonly>
                     </div>
                  </div> 
                  <div class="col-sm-4">
                     <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark" value="" />
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                        <a href="{{ Route('TrimsOutwardCuttingDepartment.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->

<script>

    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
    function checkMax(row)
    {
        let max = parseFloat($(row).attr('max'));
        let value = parseFloat($(row).val());
        
        if (value > max)
        {
            alert('Value cannot be more than ' + max); 
            $(row).val(max);  
        } 
    }
    
    function SelectAll(masterCheckbox) {
        var isChecked = $(masterCheckbox).is(":checked");
    
        // Check/uncheck all checkboxes and trigger GetApprovedStatus for each
        $("input[type='checkbox'].approve-checkbox").each(function () {
            $(this).prop('checked', isChecked);
            GetApprovedStatus(this);
        });
    }

    
    function GetApprovedStatus(row) 
    {
        var checkbox = $(row);
        var hiddenInput = checkbox.siblings("input[type='hidden']");
    
        if (checkbox.is(":checked")) {
            checkbox.attr("name", "is_approved[]");       
            hiddenInput.removeAttr("name");               
            checkbox.val(1);                                      
            hiddenInput.val(1);              
        } else {
            checkbox.removeAttr("name");                  
            hiddenInput.attr("name", "is_approved[]");    
            checkbox.val(0);                             
            hiddenInput.val(0); 
        }
    }

    
    function GetTrimsOutwardData() 
    {
        var dc_no = $("#dc_no").val();
        $.ajax({
            dataType: "json",
            url: "{{ route('GetTrimsOutwardCuttingData') }}",
            data: { 'trimOutCode': dc_no },
            success: function(data) { 
                $("#mainstyle_id").val(data.mainstyle_id).trigger('change');
                $("#substyle_id").val(data.substyle_id).trigger('change');
                $("#vendorId").val(data.vendorId).trigger('change');
                $("#fg_id").val(data.fg_id).trigger('change');
                $("#style_no").val(data.style_no).trigger('change');
                $("#style_description").val(data.style_description).trigger('change');
                $("#cutting_po_no").val(data.vpo_code).trigger('change');
                $("#outward_date").val(data.outward_date);
                
                $("tbody").html(data.html);
                setTimeout(function()
                {
                    calTotals();
                },500);
            } 
        });
    }
    
    function calTotals() 
    {
        let outward_total = 0;
        let total_challan = 0;
        let total_received = 0;
        let total_roll = 0;
    
        $("#footable_1 > tbody > tr").each(function() 
        {
            outward_total +=parseFloat($(this).find("td input[name='item_qty[]']").val()) || 0; 
            total_received += parseFloat($(this).find("td input[name='outward_qty[]']").val()) || 0; 
        });
         

        $('#total_outward_meter').val(outward_total); 
        $("#total_received_meter").val(total_received); 
    }

    
    function GetItemDetail(row)
    {
        var item_code = $(row).parent().parent('tr').find('td select[name="item_code[]"]').val();
        $.ajax({
              dataType: "json",
              url: "{{ route('GetItemDetails') }}",
              data:{'item_code':item_code},
              success: function(data)
              { 
                  $(row).parent().parent('tr').find('td input[name="item_codes[]"]').val(item_code); 
                  $(row).parent().parent('tr').find('td input[name="item_description[]"]').val(data[0].item_description);  
              }
        });
    } 
    
    function AddRow(btn) 
    {
        var currentRow = $(btn).closest('tr');
    
        // Destroy Select2 before cloning
        currentRow.find('select.select2').select2('destroy');
    
        // Clone the row
        var clonedRow = currentRow.clone();
    
        // Clear inputs and selects
        clonedRow.find('input').val('');
        clonedRow.find('select').val('').removeAttr('data-select2-id').removeClass('select2-hidden-accessible').next('.select2').remove();
    
        // Append the cloned row
        $('#footable_2').append(clonedRow);
    
        // Reinitialize Select2 for cloned row
        clonedRow.find('select.select2').select2();
    }

    
    function RemoveRow(btn) 
    {
        var rowCount = $('#footable_2 tr').length;
        if (rowCount > 1) {
          $(btn).closest('tr').remove();
        } else {
          alert("⚠️ You must keep at least one row.");
        }
    }
     
   function EnableFields()
   {
       $("select").prop('disabled', false);
       $("input").prop('readonly', false);
       $("input").prop('disabled', false);
   }
   
   function selselect()
   {
       setTimeout(
        function() 
        {
            $("#footable_2 tr td  select[name='item_code[]']").each(function() 
            {
                $(this).closest("tr").find('select[name="item_code[]"]').select2();
            });
       }, 2000);
   }
   
</script>
<!-- end row -->
@endsection