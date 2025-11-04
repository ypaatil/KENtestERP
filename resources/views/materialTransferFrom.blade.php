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
         <h4 class="mb-sm-0 font-size-18">Spares - Material Transfer From</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spares - Material Transfer From</li>
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
            <h4 class="card-title mb-4">Spares - Material Transfer From</h4>
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
            <form action="{{route('MaterialTransferFrom.store')}}" method="POST" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="materialTransferFromDate" class="form-label">Date</label>
                        <input type="date" name="materialTransferFromDate" class="form-control" id="materialTransferFromDate" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <label for="from_loc_id" class="form-label">From Location</label>
                     <select name="from_loc_id" class="form-select select2" id="from_loc_id" required>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label for="to_loc_id" class="form-label">To Location</label>
                     <select name="to_loc_id" class="form-select select2" id="to_loc_id" required>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" id="driver_name" value="" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vehical_no" class="form-label">Vehicle No</label>
                        <input type="text" name="vehical_no" class="form-control" id="vehical_no" value="" >
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
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr class="tr_clone">
                              <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
                              <td>
                                 <select name="spare_item_codes[]"  id="spare_item_code" class="" style="width:250px; height:30px;" onchange="GetItemDescription(this);CheckPair(this);" required>
                                    <option value="">--Select Item--</option>
                                    @foreach($itemlist as  $row1)
                                    <option value="{{$row1->spare_item_code}}">{{$row1->item_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td class="spare_item_code"> </td>
                              <td class="item_desc"> </td> 
                              <td>
                                 <select name="materiralInwardCode[]"  id="materiralInwardCode" class="" style="width:250px; height:30px;" required onchange="GetSpareStock(this);CheckPair(this);">
                                    <option value="">--Select--</option>
                                 </select>
                              </td>
                              <td><input type="number" step="any" class="stock"  name="stock_qty[]"   value="0" id="stock_qty" style="width:80px;" disabled /></td>
                              <td><input type="number" step="any" class="QTY"  name="item_qtys[]"   value="0" id="item_qty" style="width:80px;" onkeyup="mycalc();"  onchange="Recalc(this);" />
                              </td>
                              <td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" style="margin-left:10px;" onclick="deleteRow(this);" value="X" ></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="totalqty" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark" value="" >
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
                  <a href="{{ Route('MaterialTransferFrom.index') }}" class="btn btn-warning w-md">Cancel</a>
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
    
    function GetMaterialStock(row)
    {
        var spare_item_code =$(row).parent().parent('tr').find('td select[name="spare_item_codes[]"]').val();
        var materiralInwardCode =$(row).parent().parent('tr').find('td select[name="materiralInwardCode[]"]').val();
        
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetMaterialInwardOutwardStock') }}",
            data:{ 'spare_item_code' : spare_item_code, 'materiralInwardCode': materiralInwardCode},
            success: function(data)
            { 
                $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val(data.stock);
                $(row).parent().parent('tr').find('td input[name="item_qtys[]"]').attr('max', data.stock);
            } 
        });
        
    }
    
    function CheckPair(row)
    {
        let duplicateFound = false;
        
        // Create an array to store the pairs of perticular_id and sales_order_no
        let selectedPairs = [];
    
        // Iterate through all rows and collect the select values
        $('tbody > tr').each(function () {
            let spare_item_codes = $(this).find('select[name="spare_item_codes[]"]').val();
            let materiralInwardCode = $(this).find('select[name="materiralInwardCode[]"]').val();
    
            // Only check if both fields are selected
            if (materiralInwardCode && spare_item_codes) {
                let pair = `${materiralInwardCode}-${spare_item_codes}`;
    
                // Check if the pair already exists
                if (selectedPairs.includes(pair)) {
                    duplicateFound = true;
                    return false; // Break out of the loop
                }
    
                // Add the pair to the list
                selectedPairs.push(pair);
            }
        }); 
        // Show an alert if duplicate pairs are found
        if (duplicateFound) 
        {
            let selectBox = $(row).parent().parent('tr').find('select[name="spare_item_codes[]"]');
            let selectBox1 = $(row).parent().parent('tr').find('select[name="materiralInwardCode[]"]');
            selectBox1.val(''); // Reset the value
            $(row).parent().parent('tr').find('input[name="stock_qty[]"]').val(0);
            selectBox1.select2('destroy'); // Safely destroy Select2
            alert('Duplicate combination of GRN Code and Item Name detected!');
            selectBox.select2(); // Reinitialize Select2
            selectBox1.select2(); // Reinitialize Select2
        } 
    }
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
            }, 10000); 
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
        clonedRow.find("input[type='number']").val("");
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
            }, 10000); 
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
         
        var loc_id = $("#from_loc_id").val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetGRNListFromSpareItemCode') }}",
            data:{ 'spare_item_code' : spare_item_code, 'loc_id':loc_id},
            success: function(data)
            { 
                $(row).parent().parent('tr').find('td select[name="materiralInwardCode[]"]').html(data.html); 
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
    
    function GetSpareStock(row)
    {
        var spare_item_code = $(row).parent().parent('tr').find('td select[name="spare_item_codes[]"]').val();
        var materiralInwardCode = $(row).parent().parent('tr').find('td select[name="materiralInwardCode[]"]').val();
        var loc_id = $("#from_loc_id").val();
        var to_loc_id = $("#to_loc_id").val();
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetSpareMaterialTransferFromStock') }}",
            data:{ 'materiralInwardCode': materiralInwardCode, 'spare_item_code' : spare_item_code, 'loc_id' : loc_id, 'to_loc_id' : to_loc_id},
            success: function(data)
            {   
                $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val(data.stock_qty); 
              // $(row).parent().parent('tr').find('td input[name="item_qtys[]"]').attr('max', data.stock_qty); 
                
            //     let item_qty = 0;
            //     var stock_qty = $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val();
                
            //     $('tbody').find('select[name="materiralInwardCode[]"] option:selected').each(function()
            //     {
            //         if($(this).val() == materiralInwardCode)
            //         {
            //             item_qty += parseFloat($(this).closest('tr').find('input[name="item_qtys[]"]').val()) ?? 0;
            //         }
            //     });
                  
            //     var stock = parseFloat(data.stock_qty) - parseFloat(item_qty); 
                        
            //     $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val(stock); 
            //   $(row).parent().parent('tr').find('td input[name="item_qtys[]"]').attr('max', stock); 
               
                let item_qty = 0;
                var stock_qty = $(row).parent().parent('tr').find('td input[name="stock[]"]').val();
                
                $('tbody').find('select[name="materiralInwardCode[]"] option:selected').each(function()
                {
                    if($(this).val() == materiralInwardCode)
                    {
                        item_qty += parseFloat($(this).closest('tr').find('input[name="item_qtys[]"]').val()) ?? 0;
                    }
                });
                  
                var stock = parseFloat(data.stock_qty) - parseFloat(item_qty); 
                        
                $(row).parent().parent('tr').find('td input[name="stock[]"]').val(stock); 
                
            }
        }); 
    }
    
    
    function Recalc(row) 
    { 
        
        let item_qty = 0;
        const stock_qty = $(row).closest('tr').find('td input[name="stock_qty[]"]').val(); 
        if(stock_qty == 0)
        {
            alert("Stock are not avaliable");
            $(row).val(0); 
        }
        else if(parseInt(stock_qty) <  $(row).val())
        {
            alert("Limited stock avaliable");
            $(row).val(0);
        }
        else
        {
            // Loop through selected options except the current row
            $('tbody').find('select[name="materiralInwardCode[]"] option:selected').each(function() 
            {
                if ($(this).closest('tr')[0] !== $(row).closest('tr')[0]) 
                { 
                    if ($(this).val() === $(row).find('select[name="materiralInwardCode[]"]').val()) 
                    {
                        item_qty += parseFloat($(this).closest('tr').find('input[name="item_qtys[]"]').val()) || 0;
                    }
                }
            });
        
            const stock = parseFloat(stock_qty) - parseFloat(item_qty);        
            $(row).closest('tr').find('td input[name="stock_qty[]"]').val(stock);  
            //$(row).closest('tr').find('td input[name="item_qtys[]"]').attr('max', stock); 
        
            // Trigger change for other rows except the current one
            $('tbody tr').not($(row).closest('tr')).find('select[name="materiralInwardCode[]"]').trigger('change');  
        }
    }
    
    function EnableFields()
    {         
         $("input").prop('disabled', false); 
         $("select").prop('disabled', false); 
    }
    
</script>
@endsection