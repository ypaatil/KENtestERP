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
         <h4 class="mb-sm-0 font-size-18">Spares - Material Return Edit</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spares - Material Return Edit</li>
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
            <h4 class="card-title mb-4">Spares - Material Return Edit</h4>
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
            <form action="{{route('MaterialReturn.update', base64_encode($MaterialReturnMasterList))}}" method="POST" enctype="multipart/form-data">
               @method('put')    
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="materialReturnDate" class="form-label">Date</label>
                        <input type="date" name="materialReturnDate" class="form-control" id="materialReturnDate" value="{{$MaterialReturnMasterList->materialReturnDate}}" required>
                        <input type="hidden" name="materialReturnCode" class="form-control" id="materialReturnCode" value="{{$MaterialReturnMasterList->materialReturnCode}}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <label for="loc_id" class="form-label">Location/Warehouse</label>
                     <select name="loc_id" class="form-select select2  " id="loc_id" disabled>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}"   {{ $row->loc_id == $MaterialReturnMasterList->loc_id ? 'selected="selected"' : '' }} >{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>Machine No</th>
                              <th>Item Name</th>
                              <th>Item Code</th>
                              <th>Description</th> 
                              <th>Stock Qty</th>
                              <th>Quantity</th>
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                            @php
                                $srno = 1;
                            @endphp
                            @foreach($materialReturnDetailslist as $rows)
                           <tr class="tr_clone">
                              <td><input type="text" name="id[]" value="{{$srno++}}" id="id" style="width:50px;"  disabled /></td>
                              <td>
                                    <select name="machine_id[]" class="form-control " id="machine_id" style="width:250px;"   disabled>
                                       <option value="">--Select--</option>
                                       @foreach($machineList as  $row)
                                            <option value="{{ $row->MachineID }}"  {{ $row->MachineID == $rows->machine_id ? 'selected="selected"' : '' }} >{{ $row->MachineID }} ({{ $row->MachineSrNo }})</option>
                                       @endforeach
                                    </select>
                              </td>
                              <td>
                                 <select name="spare_item_codes[]"  id="spare_item_code" class="" style="width:250px; height:30px;" onchange="GetItemDescription(this);"  disabled>
                                    <option value="">--Select Item--</option>
                                    @foreach($itemlist as  $row1)
                                    <option value="{{$row1->spare_item_code}}" {{ $row1->spare_item_code == $rows->spare_item_code ? 'selected="selected"' : '' }} >{{$row1->item_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td class="spare_item_code">{{$rows->spare_item_code}}</td>
                              <td class="item_desc">{{$rows->item_description}}</td> 
                              <td> 
                                 <select name="spare_return_material_status_id[]"  id="spare_return_material_status_id" class="" style="width:250px; height:30px;"  disabled>
                                    <option value="">--Select--</option>
                                    @foreach($statusList as  $row3)
                                    <option value="{{$row3->spare_return_material_status_id}}" {{ $row3->spare_return_material_status_id == $rows->spare_return_material_status_id ? 'selected="selected"' : '' }}>{{$row3->spare_return_material_status_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="number" step="any" class="QTY"  name="item_qtys[]" max="{{$rows->item_qty}}" value="{{$rows->item_qty}}" id="item_qty" style="width:80px;" onkeyup="mycalc();"  /></td>
                              <td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left"  disabled>+</button><input type="button" class="btn btn-danger pull-left" style="margin-left:10px;" onclick="deleteRow(this);" value="X" ></td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" value="{{$MaterialReturnMasterList->totalqty}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark" value="{{ $MaterialReturnMasterList->remark }}" >
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Update</button>
                  <a href="{{ Route('MaterialReturn.index') }}" class="btn btn-warning w-md">Cancel</a>
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
 
    $(document).ready(function() 
    {
        $('#frmData').submit(function() 
        {
            $('#Submit').prop('disabled', true);
        }); 
        
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
         $("input").prop('disabled', false);
         $("select").prop('disabled', false); 
    }
    
</script>
@endsection