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
         <h4 class="mb-sm-0 font-size-18">Spares - Material Transfer Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spares - Material Transfer Inward</li>
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
            <h4 class="card-title mb-4">Spares - Material Transfer Inward</h4>
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
            <form action="{{route('MaterialTransferFromInward.store')}}" method="POST" id="frmData" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="materialTransferFromInwardDate" class="form-label">Date</label>
                        <input type="date" name="materialTransferFromInwardDate" class="form-control" id="materialTransferFromInwardDate" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <label for="materialTransferFromCode" class="form-label">Transfer From Code</label>
                     <select name="materialTransferFromCode" class="form-select select2" id="materialTransferFromCode" required onchange="GetTransferFromData();">
                        <option value="">--Select--</option>
                        @foreach($materialFromData as  $row)
                        <option value="{{ $row->materialTransferFromCode }}">{{ $row->materialTransferFromCode }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label for="from_loc_id" class="form-label">From Location</label>
                     <select name="from_loc_id" class="form-select" id="from_loc_id" disabled>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label for="to_loc_id" class="form-label">To Location</label>
                     <select name="to_loc_id" class="form-select" id="to_loc_id" disabled>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" id="driver_name" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vehical_no" class="form-label">Vehical No</label>
                        <input type="text" name="vehical_no" class="form-control" id="vehical_no" value="" readonly>
                     </div>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>Item Name</th>
                              <th>Item Code</th>
                              <th>Description</th> 
                              <th>GRN Code</th> 
                              <th>Stock Qty</th>
                              <th>Quantity</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="totalqty" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark" value="">
                     </div>
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
                  <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
                  <a href="{{ Route('MaterialTransferFromInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    
    function addNewRow(row)
    {
        var table = $(row).closest('table');
        var lastRow = table.find('tr:last').clone();  
        
        lastRow.find('input[type="text"]').val('');  
        lastRow.find('input[type="file"]').val(''); 
    
        table.append(lastRow); 
    }
    
    function GetTransferFromData()
    {
        var materialTransferFromCode = $("#materialTransferFromCode").val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetMaterialTransferFromData') }}",
            data:{ 'materialTransferFromCode' : materialTransferFromCode},
            success: function(data)
            { 
                $("#from_loc_id").val(data.materialMasterData.from_loc_id);
                $("#to_loc_id").val(data.materialMasterData.to_loc_id);
                $("#driver_name").val(data.materialMasterData.driver_name);
                $("#vehical_no").val(data.materialMasterData.vehical_no);
                $('#footable_2 > tbody').html(data.html);
                
            } 
        });
    }
    
    function CheckStock(row)
    {
        var item_qty = $(row).val();
        var max = $(row).attr('max');
        if(parseInt(item_qty) > parseInt(max))
        {
            alert("Quantity should be less than "+max);
            $(row).val(0);
        }
    }
    
    function GetMaterialStock(row)
    {
        var spare_item_code =$(row).parent().parent('tr').find('td select[name="spare_item_codes[]"]').val();
        
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetMaterialInwardOutwardStock') }}",
            data:{ 'spare_item_code' : spare_item_code},
            success: function(data)
            { 
                $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val(data.stock);
                $(row).parent().parent('tr').find('td input[name="item_qtys[]"]').attr('max', data.stock);
            } 
        });
        
    }
    
    $(document).ready(function() 
    {
        $('#frmData').submit(function() 
        {
            $('#Submit').prop('disabled', true);
        }); 
        
        
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() 
        {
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 3000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
    });
    
    function insertRow() 
    {
        let lastRow = $(".tr_clone:last");
        
        // Clone the last row
        let clonedRow = lastRow.clone();
    
        // Reset input values in the cloned row
        clonedRow.find("input[type='text']").val("0");
        clonedRow.find("input[type='hidden']").val("0");
        clonedRow.find("select").prop("selectedIndex", 0);
        
        // Append the cloned row after the last row
        lastRow.after(clonedRow);
        recalcId();
        mycalc(); 
        
        
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() {
            // Initialize select2 if it's not already initialized
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 3000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
    }
    
    function GetItemDescription(row)
    {
        var spare_item_code = $(row).parent().parent('tr').find('td select[name="spare_item_codes[]"]').val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetItemDescriptionForMachine') }}",
            data:{ 'spare_item_code' : spare_item_code},
            success: function(data)
            { 
                $(row).parent().parent('tr').find('.spare_item_code').html(data.spare_item_code);
                $(row).parent().parent('tr').find('.item_desc').html(data.item_description);
            }
        });
    }
   
    
    function deleteRow(btn) 
    { 
          var row = btn.parentNode.parentNode;
          row.parentNode.removeChild(row); 
          recalcId();
          mycalc(); 
    }
      
    function recalcId()
    {
        $.each($("#footable_2 tr"),function (i,el){
            $(this).find("td:first input").val(i);  
        });
    }
      
    function mycalc()
    {   
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('QTY');
          for(var i=0; i<amounts .length; i++)
          { 
              var a = +amounts[i].value;
              sum1 += parseFloat(a);
          }
          document.getElementById("totalqty").value = sum1.toFixed(2);
    }
      
    function EnableFields()
    {         
         $("select").prop('disabled', false); 
         $("input").prop('disabled', false); 
    }
    
</script>
@endsection